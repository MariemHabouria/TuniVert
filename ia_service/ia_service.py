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
    # Modèle plus polyvalent pour tous types de questions
    MODEL_NAME = "microsoft/DialoGPT-large"  # Bon pour les dialogues généraux
    # Alternative: "facebook/blenderbot-400M-distill" pour plus de polyvalence
    MAX_LENGTH = 80
    TEMPERATURE = 0.7
    TOP_P = 0.9
    REPETITION_PENALTY = 1.2

# Initialisation du modèle
try:
    logger.info("Chargement du modèle de génération polyvalent...")
    
    generator = pipeline(
        "text-generation",
        model=Config.MODEL_NAME,
        tokenizer=Config.MODEL_NAME,
        torch_dtype=torch.float16 if torch.cuda.is_available() else torch.float32,
        device=0 if torch.cuda.is_available() else -1
    )
    
    logger.info("Modèle polyvalent chargé avec succès")
    
except Exception as e:
    logger.error(f"Erreur de chargement: {e}")
    generator = None

def create_universal_prompt(forum_contenu, texte_courant):
    """Crée un prompt universel pour tous types de questions"""
    
    base_instruction = """Tu es un assistant utile qui aide les utilisateurs à formuler leurs réponses sur un forum de discussion. 
Ta tâche est de proposer une suite logique et utile à ce que l'utilisateur est en train d'écrire.

Caractéristiques de tes suggestions:
- Pertinentes par rapport au contexte
- Courtes et concises
- Naturelles et fluides
- Utiles pour avancer la discussion

Réponds toujours en français."""

    if texte_courant.strip():
        prompt = f"""{base_instruction}

Sujet de discussion: {forum_contenu}

Ce que l'utilisateur a déjà écrit: {texte_courant}

Suggestion pour continuer:"""
    else:
        prompt = f"""{base_instruction}

Sujet de discussion: {forum_contenu}

Première suggestion de réponse:"""

    return prompt

def detect_topic(question):
    """Détecte le sujet de la question pour adapter le style de réponse"""
    question_lower = question.lower()
    
    # Détection de catégories générales
    categories = {
        "technique": ["comment", "fonctionne", "installer", "configurer", "bug", "erreur", "programme", "code"],
        "conseil": ["conseil", "avis", "recommander", "meilleur", "quelle", "quel", "choisir"],
        "opinion": ["pensez", "opinion", "avis", "débat", "discuter", "points de vue"],
        "aide": ["aide", "problème", "solution", "résoudre", "difficulté", "souci"],
        "information": ["quoi", "qu'est", "définition", "expliquer", "signifie", "c'est quoi"],
        "comparaison": ["vs", "comparer", "différence", "avantages", "inconvénients"],
        "environnement": ["écologie", "environnement", "climat", "durable", "recyclage", "plastique"]
    }
    
    detected_category = "général"
    max_score = 0
    
    for category, keywords in categories.items():
        score = sum(1 for keyword in keywords if keyword in question_lower)
        if score > max_score:
            max_score = score
            detected_category = category
    
    return detected_category

def adapt_style_to_topic(topic, texte_courant):
    """Adapte le style de réponse au sujet détecté"""
    style_adaptations = {
        "technique": {
            "style": "précis et technique",
            "phrases_type": [
                "Voici les étapes à suivre...",
                "La solution technique recommandée est...",
                "Il faut vérifier d'abord...",
                "Assure-toi que...",
                "La procédure standard est..."
            ]
        },
        "conseil": {
            "style": "bienveillant et pratique", 
            "phrases_type": [
                "Je te recommande de...",
                "Une bonne approche serait...",
                "Pour optimiser tes résultats...",
                "Évite surtout de...",
                "Ce qui fonctionne bien c'est..."
            ]
        },
        "opinion": {
            "style": "nuancé et ouvert",
            "phrases_type": [
                "À mon avis...",
                "Plusieurs points de vue existent...",
                "D'un côté... mais de l'autre...",
                "Certains pensent que...",
                "Personnellement, je trouve que..."
            ]
        },
        "aide": {
            "style": "empathique et solution-oriented",
            "phrases_type": [
                "Je comprends ton problème...",
                "Voici ce que tu peux essayer...", 
                "Une solution possible serait...",
                "As-tu déjà testé de...",
                "Pour résoudre ça..."
            ]
        },
        "information": {
            "style": "clair et informatif",
            "phrases_type": [
                "En résumé...",
                "L'essentiel à savoir...",
                "Concrètement...",
                "Pour faire simple...",
                "La définition précise est..."
            ]
        },
        "comparaison": {
            "style": "équilibré et comparatif", 
            "phrases_type": [
                "Les avantages sont...",
                "Par contre les inconvénients...",
                "Si tu préfères... alors choisis...",
                "Comparé à l'autre option...",
                "Le meilleur choix dépend de..."
            ]
        },
        "environnement": {
            "style": "engagé et pratique",
            "phrases_type": [
                "Une action concrète serait...",
                "Pour contribuer positivement...",
                "Je suggère cette approche écologique...",
                "L'impact environnemental sera...",
                "Voici une alternative durable..."
            ]
        },
        "général": {
            "style": "neutre et utile",
            "phrases_type": [
                "Voici ce que je peux suggérer...",
                "Une idée intéressante serait...",
                "Pour compléter ta pensée...",
                "Tu pourrais aussi considérer...",
                "Voici un point important..."
            ]
        }
    }
    
    return style_adaptations.get(topic, style_adaptations["général"])

def generate_adaptive_suggestion(forum_contenu, texte_courant):
    """Génère une suggestion adaptée au contexte"""
    
    # Détection du sujet
    topic = detect_topic(forum_contenu)
    style_info = adapt_style_to_topic(topic, texte_courant)
    
    logger.info(f"Sujet détecté: {topic}, Style: {style_info['style']}")
    
    # Création du prompt adapté
    prompt = create_universal_prompt(forum_contenu, texte_courant)
    
    if generator:
        try:
            # Génération avec le modèle
            outputs = generator(
                prompt,
                max_length=len(prompt.split()) + 25,
                num_return_sequences=1,
                temperature=Config.TEMPERATURE,
                top_p=Config.TOP_P,
                repetition_penalty=Config.REPETITION_PENALTY,
                do_sample=True,
                pad_token_id=generator.tokenizer.eos_token_id,
                no_repeat_ngram_size=2,
            )
            
            generated_text = outputs[0]['generated_text']
            suggestion = generated_text.replace(prompt, "").strip()
            
        except Exception as e:
            logger.error(f"Erreur de génération: {e}")
            suggestion = ""
    else:
        suggestion = ""
    
    # Nettoyage et amélioration de la suggestion
    cleaned_suggestion = clean_suggestion(suggestion, forum_contenu)
    
    # Si la suggestion est vide ou de mauvaise qualité, utiliser une suggestion adaptée au sujet
    if not cleaned_suggestion or len(cleaned_suggestion) < 15:
        cleaned_suggestion = get_topic_specific_fallback(topic, texte_courant)
    
    return cleaned_suggestion

def clean_suggestion(suggestion, original_question):
    """Nettoie la suggestion générée"""
    if not suggestion:
        return ""
    
    # Supprimer les répétitions du prompt
    unwanted_patterns = [
        r'(?i)(sujet de discussion:|ce que l\'utilisateur a déjà écrit:|suggestion pour continuer:)',
        r'^[A-Z]:\s*',
        r'^"\s*',
        r'\.{2,}',
    ]
    
    for pattern in unwanted_patterns:
        suggestion = re.sub(pattern, '', suggestion)
    
    # Supprimer les phrases trop similaires à la question originale
    original_words = set(re.findall(r'\w+', original_question.lower()))
    suggestion_sentences = re.split(r'[.!?]+\s*', suggestion)
    
    filtered_sentences = []
    for sentence in suggestion_sentences:
        sentence_clean = sentence.strip()
        if not sentence_clean:
            continue
            
        sentence_words = set(re.findall(r'\w+', sentence_clean.lower()))
        # Éviter les phrases qui répètent trop la question
        if len(sentence_words.intersection(original_words)) < len(original_words) * 0.5:
            filtered_sentences.append(sentence_clean)
    
    # Reconstruire avec maximum 2 phrases
    if filtered_sentences:
        result = '. '.join(filtered_sentences[:2])
        if not result.endswith(('.', '!', '?')):
            result += '.'
    else:
        result = ""
    
    return re.sub(r'\s+', ' ', result).strip()

def get_topic_specific_fallback(topic, texte_courant):
    """Suggestions de fallback adaptées au sujet"""
    
    fallback_responses = {
        "technique": [
            "La procédure recommandée consiste à suivre ces étapes précises...",
            "Vérifie d'abord la configuration de base avant d'aller plus loin.",
            "Le problème vient souvent de cette partie, donc concentre-toi là-dessus.",
            "Assure-toi que tous les prérequis sont installés et configurés.",
            "Consulte la documentation officielle pour les détails techniques."
        ],
        "conseil": [
            "Je te recommande cette option qui offre le meilleur rapport qualité-prix.",
            "Pour débuter, choisis une solution simple et évolutive.",
            "Évite les pièges classiques en vérifiant ces points essentiels.",
            "La solution la plus adaptée dépend surtout de ton usage spécifique.",
            "Pense à tester plusieurs approches avant de te décider."
        ],
        "opinion": [
            "À mon avis, il y a plusieurs aspects à considérer dans ce débat.",
            "Certains préfèrent cette approche, tandis que d'autres la critiquent.",
            "Le sujet est complexe avec des arguments valides des deux côtés.",
            "Personnellement, je trouve que cette perspective est intéressante.",
            "Il n'y a pas de réponse unique, tout dépend du contexte."
        ],
        "aide": [
            "Je comprends ta difficulté, voici ce qui pourrait t'aider.",
            "Commence par vérifier ces points qui résolvent souvent le problème.",
            "Une solution simple serait d'essayer cette approche étape par étape.",
            "N'hésite pas à fournir plus de détails pour une aide plus précise.",
            "Le forum pourra certainement t'aider avec des solutions concrètes."
        ],
        "information": [
            "Pour faire simple, l'essentiel à retenir est que...",
            "La définition précise est la suivante, avec ses implications.",
            "En résumé, voici les points clés à comprendre sur ce sujet.",
            "L'information principale est complétée par ces détails importants.",
            "Concrètement, cela signifie que plusieurs aspects sont à considérer."
        ],
        "comparaison": [
            "Les deux options ont leurs avantages et inconvénients spécifiques.",
            "Le meilleur choix dépend vraiment de tes besoins particuliers.",
            "Comparé à l'autre solution, celle-ci offre ces différences majeures.",
            "Si la performance est prioritaire, choisis la première option.",
            "Pour un usage occasionnel, la seconde solution suffira amplement."
        ],
        "environnement": [
            "Une action concrète et efficace serait de mettre en place ce système.",
            "Pour réduire ton impact environnemental, voici des solutions pratiques.",
            "L'approche écologique la plus efficace dans ce cas est...",
            "Chaque petit geste compte, commence par ces actions simples.",
            "La solution durable combine ces différents aspects complémentaires."
        ],
        "général": [
            "Voici quelques points supplémentaires à considérer.",
            "Pour compléter cette discussion, voici une perspective intéressante.",
            "Une approche efficace serait de combiner plusieurs solutions.",
            "N'oublie pas de prendre en compte ce facteur important.",
            "L'expérience montre que cette méthode donne de bons résultats."
        ]
    }
    
    responses = fallback_responses.get(topic, fallback_responses["général"])
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
        
        # Générer la suggestion adaptative
        suggestion = generate_adaptive_suggestion(forum_contenu, texte_courant)
        
        # Format final
        if suggestion and suggestion[0].islower():
            suggestion = suggestion[0].upper() + suggestion[1:]
        
        logger.info(f"Suggestion générée: {suggestion}")
        
        return jsonify({
            "suggestion": suggestion,
            "timestamp": __import__('datetime').datetime.utcnow().isoformat()
        })
        
    except Exception as e:
        logger.error(f"Erreur: {str(e)}")
        return jsonify({
            "suggestion": "Je peux vous aider à formuler votre réponse. Pouvez-vous préciser votre question ?"
        })

@app.route("/health", methods=["GET"])
def health_check():
    return jsonify({
        "status": "ready",
        "model_loaded": generator is not None,
        "service": "assistant_polyvalent"
    })

if __name__ == "__main__":
    logger.info("Service d'assistant polyvalent démarré")
    app.run(port=5000, debug=False)