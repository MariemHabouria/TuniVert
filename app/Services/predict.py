import pandas as pd
import sys, json
from sklearn.preprocessing import OneHotEncoder
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics.pairwise import cosine_similarity

participant_id = int(sys.argv[1])
challenge_id = int(sys.argv[2]) if len(sys.argv) > 2 else None

# Lire les données JSON générées par Laravel
with open('storage/app/predict_data.json', 'r') as f:
    data = json.load(f)

df = pd.DataFrame(data)

encoder = OneHotEncoder(sparse=False)
X_cat = encoder.fit_transform(df[['categorie','difficulte']])
X = pd.concat([pd.DataFrame(X_cat), df[['participant_id','challenge_id']]], axis=1)
y = df['score']

model = RandomForestRegressor(n_estimators=100, random_state=42)
model.fit(X, y)

predicted_score = None
if challenge_id:
    challenge = df[df['challenge_id'] == challenge_id].iloc[0]
    X_pred_cat = encoder.transform([[challenge['categorie'], challenge['difficulte']]])
    X_pred = pd.concat([pd.DataFrame(X_pred_cat), pd.DataFrame([[participant_id, challenge_id]])], axis=1)
    predicted_score = model.predict(X_pred)[0]

participant_vector = X[df['participant_id']==participant_id].mean().values.reshape(1, -1)
X_challenges = X.drop(df[df['participant_id']==participant_id].index)
similarities = cosine_similarity(participant_vector, X_challenges)[0]
recommended_ids = X_challenges.iloc[similarities.argsort()[::-1]]['challenge_id'].tolist()[:3]

output = {
    'predicted_score': float(predicted_score) if predicted_score else None,
    'recommended_challenges': recommended_ids
}
print(json.dumps(output))
