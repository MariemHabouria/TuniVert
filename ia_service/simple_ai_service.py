from flask import Flask, request, jsonify
import logging
import re
import random

# Configuration du logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = Flask(__name__)

def extract_main_topic(question):
    """Extrait le sujet principal de la question"""
    question_lower = question.lower()
    
    # Détection des sujets principaux
    topics = {
        "plastique": ["plastique", "emballage", "bouteille", "sac plastique", "déchet plastique"],
        "énergie": ["énergie", "électricité", "chauffage", "consommation énergétique", "économiser énergie"],
        "transport": ["transport", "voiture", "vélo", "bus", "métro", "déplacement"],
        "alimentation": ["manger", "alimentation", "nourriture", "viande", "végétarien", "bio"],
        "eau": ["eau", "consommation eau", "économiser eau", "douche", "robinet"],
        "déchet": ["déchet", "recyclage", "compost", "tri", "poubelle"],
        "jardin": ["jardin", "plante", "potager", "arbre", "verdure"],
        "maison": ["maison", "logement", "appartement", "habitation", "écologique"]
    }
    
    detected_topic = "général"
    for topic, keywords in topics.items():
        if any(keyword in question_lower for keyword in keywords):
            detected_topic = topic
            break
    
    return detected_topic

def get_specific_fallback(topic, question, texte_courant):
    """Retourne des réponses très spécifiques selon le sujet"""
    
    specific_responses = {
        "plastique": [
            "Privilégiez les contenants en verre réutilisables et les achats en vrac.",
            "Utilisez des sacs en tissu pour les courses et refusez les emballages inutiles.",
            "Optez pour une gourde en inox plutôt que des bouteilles en plastique.",
            "Participez à des ateliers de fabrication de produits ménagers pour éviter les emballages.",
            "Choisissez des produits avec consigne ou des emballages biodégradables."
        ],
        "énergie": [
            "Installez des ampoules LED et des multiprises avec interrupteur.",
            "Baissez le chauffage d'un degré et isolez portes et fenêtres.",
            "Utilisez des appareils électroménagers de classe énergétique A+++.",
            "Éteignez complètement les appareils en veille avec une multiprise.",
            "Profitez de la lumière naturelle et séchez le linge à l'air libre."
        ],
        "transport": [
            "Utilisez le vélo ou la marche pour les trajets de moins de 3 km.",
            "Optez pour le covoiturage via des applications dédiées.",
            "Privilégiez les transports en commun aux heures creuses.",
            "Planifiez vos déplacements pour optimiser vos trajets.",
            "Envisagez l'achat d'un véhicule électrique ou hybride."
        ],
        "alimentation": [
            "Achetez des produits locaux et de saison au marché.",
            "Réduisez votre consommation de viande à 2-3 fois par semaine.",
            "Compostez vos déchets alimentaires pour enrichir votre sol.",
            "Cultivez vos propres herbes aromatiques sur le balcon.",
            "Évitez le gaspillage en cuisinant les bonnes quantités."
        ],
        "eau": [
            "Installez des mousseurs sur les robinets pour réduire le débit.",
            "Récupérez l'eau de pluie pour arroser le jardin.",
            "Prenez des douches de 5 minutes maximum.",
            "Utilisez un lave-vaisselle seulement quand il est plein.",
            "Réutilisez l'eau de cuisson pour arroser les plantes."
        ],
        "déchet": [
            "Mettez en place le tri sélectif avec des bacs colorés.",
            "Compostez vos déchets organiques dans un bac dédié.",
            "Donnez ou vendez les objets dont vous ne vous servez plus.",
            "Réparez plutôt que jetez en suivant des tutoriels en ligne.",
            "Participez à des ramassages de déchets dans votre quartier."
        ],
        "jardin": [
            "Plantez des espèces locales adaptées à votre climat.",
            "Utilisez le paillage pour réduire l'arrosage et les mauvaises herbes.",
            "Créez un hôtel à insectes pour favoriser la biodiversité.",
            "Installez un récupérateur d'eau de pluie pour l'arrosage.",
            "Pratiquez la rotation des cultures dans votre potager."
        ],
        "maison": [
            "Améliorez l'isolation avec des matériaux naturels comme la laine de bois.",
            "Installez des panneaux solaires pour produire votre électricité.",
            "Utilisez des peintures écologiques sans COV.",
            "Optez pour des meubles en bois certifié FSC.",
            "Créez un système de récupération des eaux grises."
        ],
        "général": [
            "Commencez par identifier vos principales sources de gaspillage.",
            "Établissez un plan d'action avec des objectifs mesurables.",
            "Impliquez votre entourage pour multiplier l'impact positif.",
            "Documentez vos progrès pour rester motivé dans votre démarche.",
            "Rejoignez une association locale pour échanger des bonnes pratiques."
        ]
    }
    
    responses = specific_responses.get(topic, specific_responses["général"])
    
    # Si l'utilisateur a commencé une réponse, essayer de compléter logiquement
    if texte_courant.strip():
        texte_lower = texte_courant.lower()
        if "sac" in texte_lower:
            return "choisissez des sacs en tissu réutilisables plutôt qu'en plastique."
        elif "transport" in texte_lower or "voiture" in texte_lower:
            return "privilégiez le covoiturage ou les transports en commun."
        elif "eau" in texte_lower:
            return "installez des réducteurs de débit sur tous vos robinets."
        elif "énergie" in texte_lower or "électricité" in texte_lower:
            return "remplacez vos ampoules par des LED et éteignez les appareils en veille."
        elif "déchet" in texte_lower:
            return "mettez en place un système de tri sélectif dans votre cuisine."
        elif "manger" in texte_lower or "alimentation" in texte_lower:
            return "achetez local et de saison pour réduire l'impact environnemental."
    
    return random.choice(responses)

@app.route("/suggestion", methods=["POST"])
def suggestion():
    try:
        if not request.is_json:
            return jsonify({"error": "Content-Type must be application/json"}), 400
        
        data = request.get_json()
        forum_contenu = data.get("forum_contenu", "").strip()
        texte_courant = data.get("texte_courant", "").strip()
        
        if not forum_contenu:
            return jsonify({"error": "Le champ 'forum_contenu' est requis"}), 400
        
        logger.info(f"Question: {forum_contenu}")
        logger.info(f"Réponse partielle: {texte_courant}")
        
        # Générer une suggestion basée sur le sujet
        topic = extract_main_topic(forum_contenu)
        suggestion_text = get_specific_fallback(topic, forum_contenu, texte_courant)
        
        # Format final
        if suggestion_text and suggestion_text[0].islower():
            suggestion_text = suggestion_text[0].upper() + suggestion_text[1:]
        
        logger.info(f"Suggestion finale: {suggestion_text}")
        
        return jsonify({
            "suggestion": suggestion_text,
            "topic": topic,
            "timestamp": __import__('datetime').datetime.utcnow().isoformat()
        })
        
    except Exception as e:
        logger.error(f"Erreur: {str(e)}")
        return jsonify({
            "suggestion": "Commencez par une action simple comme utiliser des sacs réutilisables.",
            "error": "fallback"
        })

@app.route("/health", methods=["GET"])
def health_check():
    return jsonify({
        "status": "ready",
        "model_loaded": False
    })

if __name__ == "__main__":
    logger.info("Service de suggestions spécifiques démarré (mode simplifié)")
    app.run(host='0.0.0.0', port=5000, debug=False)