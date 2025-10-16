import pandas as pd
from sklearn.metrics.pairwise import cosine_similarity
import json
import mysql.connector
from datetime import datetime
import os


# =======================
# Connexion à la base Laravel
# =======================
db = mysql.connector.connect(
    host="127.0.0.1",
    user="root",
    password="",   # ton mot de passe MySQL
    database="tunivert"  # nom exact de ta base Laravel
)

# =======================
# Récupérer les participants
# =======================
cursor = db.cursor(dictionary=True)
cursor.execute("SELECT user_id, event_id FROM participants")
rows = cursor.fetchall()
participants = pd.DataFrame(rows)

# =======================
# Récupérer les événements
# =======================
cursor.execute("SELECT id, category, date FROM events")
rows = cursor.fetchall()
events = pd.DataFrame(rows)
events['date'] = pd.to_datetime(events['date'])

# =======================
# Matrice utilisateur x événement
# =======================
user_event_matrix = participants.pivot_table(
    index='user_id',
    columns='event_id',
    aggfunc='size',
    fill_value=0
)

# =======================
# Similarité cosinus entre utilisateurs
# =======================
if not user_event_matrix.empty:
    similarity = cosine_similarity(user_event_matrix)
    similarity_df = pd.DataFrame(similarity,
                                 index=user_event_matrix.index,
                                 columns=user_event_matrix.index)
else:
    similarity_df = pd.DataFrame()

# =======================
# Popularité des événements
# =======================
event_popularity = participants.groupby('event_id').size()

# =======================
# Fonction de recommandation
# =======================
def recommend_events(user_id, top_n=5):
    if user_id not in user_event_matrix.index or similarity_df.empty:
        return []  # nouvel utilisateur ou pas de participants
    
    # -----------------------
    # Collaborative Filtering
    # -----------------------
    sim_scores = similarity_df.loc[user_id].drop(user_id)
    sim_scores = sim_scores.reindex(user_event_matrix.index, fill_value=0)
    cf_scores = user_event_matrix.T.dot(sim_scores)
    cf_scores = pd.Series(cf_scores, index=user_event_matrix.columns)

    # -----------------------
    # Content-based (catégories)
    # -----------------------
    participated_events = user_event_matrix.loc[user_id]
    user_categories = events.loc[
        events['id'].isin(participated_events[participated_events > 0].index),
        'category'
    ].unique()
    content_scores = events.set_index('id')['category'].apply(lambda c: 1 if c in user_categories else 0)

    # -----------------------
    # Popularité
    # -----------------------
    popularity_scores = event_popularity.reindex(events['id']).fillna(0)

    # -----------------------
    # Date proche
    # -----------------------
    days_until_event = (events.set_index('id')['date'] - pd.Timestamp(datetime.today())).dt.days
    date_scores = 1 / (1 + days_until_event.clip(lower=0))

    # -----------------------
    # Combiner tous les scores
    # -----------------------
    total_scores = cf_scores.add(content_scores, fill_value=0)
    total_scores = total_scores.add(popularity_scores, fill_value=0)
    total_scores = total_scores.add(date_scores, fill_value=0)

    # -----------------------
    # Exclure les événements déjà participés
    # -----------------------
    already_participated = user_event_matrix.loc[user_id].reindex(total_scores.index, fill_value=0)
    total_scores = total_scores[already_participated == 0]

    recommended = total_scores.sort_values(ascending=False).head(top_n)
    return recommended.index.tolist()

# =======================
# Générer recommandations pour tous les utilisateurs
# =======================
recommendations = {}
for uid in user_event_matrix.index:
    recommendations[uid] = recommend_events(uid, top_n=5)

# =======================
# Sauvegarder dans JSON
# =======================
json_path = os.path.join(os.path.dirname(__file__), 'recommendations.json')

with open('recommendations.json', 'w') as f:
    json.dump(recommendations, f, indent=4)

print("Recommandations générées avec succès dans recommendations.json ✅")
