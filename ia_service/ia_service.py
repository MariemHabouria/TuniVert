from flask import Flask, request, jsonify
from transformers import pipeline, AutoTokenizer, AutoModelForCausalLM
import torch
import logging
import re
import random

# Configuration du logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = Flask(__name__)

class Config:
    # Modèle plus performant pour le français
    MODEL_NAME = "microsoft/DialoGPT-medium"
    MAX_LENGTH = 80
    TEMPERATURE = 0.4  # Plus déterministe
    TOP_P = 0.8
    REPETITION_PENALTY = 1.5

# Initialisation du modèle
try:
    logger.info("Utilisation du mode fallback sans modèle lourd...")
    # Pour éviter le téléchargement de modèles lourds, on utilise directement les fallbacks
    tokenizer, model = None, None
    logger.info("Mode fallback activé avec succès")
    
except Exception as e:
    logger.error(f"Erreur de chargement: {e}")
    tokenizer, model = None, None

def create_specific_prompt(forum_contenu, texte_courant):
    """Crée un prompt hyper-spécifique qui force des réponses concrètes"""
    
    # Instructions très précises
    strict_rules = """TU DOIS:
1. Répondre DIRECTEMENT au sujet sans introduction
2. Donner des conseils PRATIQUES et ACTIONNABLES
3. Être SPÉCIFIQUE, pas général
4. Utiliser des exemples CONCRETS
5. Répondre en 1-2 phrases MAXIMUM

EXEMPLE:
Question: "Comment réduire le plastique?"
BONNE RÉPONSE: "Utilisez des sacs réutilisables en coton et achetez en vrac."
MAUVAISE RÉPONSE: "C'est un sujet important, voici quelques conseils..."

MAINTENANT, RÉPONDS À CETTE QUESTION:"""

    if texte_courant.strip():
        prompt = f"""{strict_rules}

QUESTION: {forum_contenu}

RÉPONSE DÉJÀ COMMENCÉE: {texte_courant}

SUITE DIRECTE ET PRATIQUE:"""
    else:
        prompt = f"""{strict_rules}

QUESTION: {forum_contenu}

RÉPONSE DIRECTE ET PRATIQUE:"""

    return prompt

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

def generate_direct_suggestion(forum_contenu, texte_courant):
    """Génère une suggestion directe et spécifique"""
    
    if not model or not tokenizer:
        topic = extract_main_topic(forum_contenu)
        return get_specific_fallback(topic, forum_contenu, texte_courant)
    
    # Créer un prompt hyper-directif
    prompt = create_specific_prompt(forum_contenu, texte_courant)
    
    try:
        # Tokenization
        inputs = tokenizer.encode(prompt, return_tensors="pt", max_length=512, truncation=True)
        
        # Génération avec paramètres stricts
        with torch.no_grad():
            outputs = model.generate(
                inputs,
                max_length=inputs.shape[1] + 50,  # Réponse courte
                num_return_sequences=1,
                temperature=Config.TEMPERATURE,
                top_p=Config.TOP_P,
                repetition_penalty=Config.REPETITION_PENALTY,
                do_sample=True,
                pad_token_id=tokenizer.eos_token_id,
                no_repeat_ngram_size=3,
                early_stopping=True
            )
        
        # Décodage
        generated_text = tokenizer.decode(outputs[0], skip_special_tokens=True)
        suggestion = generated_text.replace(prompt, "").strip()
        
        # Nettoyage agressif
        suggestion = clean_response(suggestion)
        
        # Vérifier que la réponse n'est pas générique
        if is_generic_response(suggestion):
            topic = extract_main_topic(forum_contenu)
            suggestion = get_specific_fallback(topic, forum_contenu, texte_courant)
        
        return suggestion
        
    except Exception as e:
        logger.error(f"Erreur de génération: {e}")
        topic = extract_main_topic(forum_contenu)
        return get_specific_fallback(topic, forum_contenu, texte_courant)

def clean_response(suggestion):
    """Nettoie la réponse pour la rendre plus directe"""
    if not suggestion:
        return ""
    
    # Supprimer les phrases d'introduction génériques
    generic_starts = [
        "Concernant ce sujet,",
        "Pour répondre à votre question,",
        "Je pense que",
        "À mon avis,",
        "Selon moi,",
        "Il est important de",
        "Je voudrais dire que",
        "En ce qui concerne",
        "Pour commencer,",
        "Tout d'abord,"
    ]
    
    for start in generic_starts:
        if suggestion.startswith(start):
            suggestion = suggestion[len(start):].strip()
            # Capitaliser la première lettre
            if suggestion and suggestion[0].islower():
                suggestion = suggestion[0].upper() + suggestion[1:]
            break
    
    # Supprimer les marqueurs de dialogue
    unwanted_patterns = [
        r'^[A-Z]:\s*',
        r'^"\s*',
        r'\.{2,}',
        r'\b(je crois que|je suppose que|peut-être que)\b',
    ]
    
    for pattern in unwanted_patterns:
        suggestion = re.sub(pattern, '', suggestion)
    
    # Nettoyer les espaces
    suggestion = re.sub(r'\s+', ' ', suggestion).strip()
    
    # S'assurer d'une ponctuation correcte
    if suggestion and suggestion[-1] not in ['.', '!', '?']:
        suggestion += '.'
    
    return suggestion

def is_generic_response(suggestion):
    """Détecte les réponses trop génériques"""
    if not suggestion or len(suggestion) < 20:
        return True
    
    generic_phrases = [
        "voici ce qui est important",
        "c'est un sujet important",
        "il faut considérer",
        "plusieurs aspects",
        "différents points",
        "je peux vous dire",
        "concernant ce sujet",
        "pour répondre",
        "à votre question"
    ]
    
    suggestion_lower = suggestion.lower()
    return any(phrase in suggestion_lower for phrase in generic_phrases)

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
        
        # Générer une suggestion directe
        suggestion_text = generate_direct_suggestion(forum_contenu, texte_courant)
        
        # Format final
        if suggestion_text and suggestion_text[0].islower():
            suggestion_text = suggestion_text[0].upper() + suggestion_text[1:]
        
        logger.info(f"Suggestion finale: {suggestion_text}")
        
        return jsonify({
            "suggestion": suggestion_text,
            "topic": extract_main_topic(forum_contenu),
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
        "model_loaded": model is not None
    })

if __name__ == "__main__":
    logger.info("Service de suggestions spécifiques démarré")
    app.run(port=5000, debug=False)