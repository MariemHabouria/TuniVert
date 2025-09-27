# 🌱 TuniVert - Plateforme EcoEvents

TuniVert est une plateforme en ligne dédiée à l’organisation et à la promotion d’événements autour de l’écologie et du développement durable.  
Elle soutient les initiatives citoyennes et associatives en facilitant la participation collective à des actions de sensibilisation et de mobilisation pour la protection de l’environnement.

---

## 🚀 Fonctionnalités principales

### 1. Gestion des utilisateurs
**Front-office**  
- **Visiteur** : consulter événements, forums, formations (lecture seule).  
- **Utilisateur** : inscription/connexion sécurisées, modification du profil, participation aux événements, forums, challenges et formations.  
- **Association / Organisateur** : créer et gérer événements, challenges et formations, suivi des participants.  

**Back-office (Admin)**  
- Gestion des comptes utilisateurs (ajout, suspension, suppression).  
- Validation des associations et gestion des rôles.  
- Statistiques : nombre d’utilisateurs par rôle, associations validées vs en attente.  

---

### 2. Gestion des événements
**Front-office**  
- **Organisateur** : créer, modifier, supprimer événements, gérer participants.  
- **Utilisateur** : inscription/annulation, dépôt de preuve (photo/vidéo), recherche d’événements.  

**Back-office (Admin)**  
- Validation et suppression des événements inappropriés.  
- Mise en avant d’événements.  
- Statistiques globales par mois et catégorie.  

---

### 3. Gestion des donations
**Front-office**  
- Utilisateur : effectuer un don ponctuel ou récurrent, choisir moyen de paiement, consulter historique.  
- Organisateur : suivi des dons liés à ses événements, notifications automatiques.  

**Back-office (Admin)**  
- Gestion et suivi des donations.  
- Statistiques globales (total dons, récurrents/ponctuels).  
- Vérification de fraudes ou anomalies.  

---

### 4. Gestion des forums / communautés
**Front-office**  
- Création de sujets, réponses aux messages.  
- Signalement d’alertes urgentes (incendie, pollution, etc.).  

**Back-office (Admin / Organisateur)**  
- Modération (suppression de messages, blocage d’utilisateurs).  
- Gestion des alertes urgentes selon priorité.  
- Statistiques : nombre de messages et alertes par mois.  

---

### 5. Gestion des challenges
**Front-office**  
- Organisateur : création et suivi des défis écologiques, validation des preuves.  
- Utilisateur : participation aux challenges et soumission de preuves.  

**Back-office (Admin)**  
- Supervision des challenges et contrôle du contenu.  
- Statistiques : nombre de participants et challenges actifs.  

---

### 6. Gestion des formations
**Front-office**  
- Organisateur : création de formations, ajout de ressources pédagogiques, gestion des participants.  
- Utilisateur : inscription, accès aux ressources, notes et avis.  

**Back-office (Admin)**  
- Supervision des formations, gestion des inscriptions en attente.  
- Statistiques globales : nombre de formations créées et participants.  

---

## 🛠️ Stack Technique
- **Backend** : Laravel 12 (PHP 8.2)  
- **Base de données** : MySQL (via XAMPP)  
- **Frontend** : Blade  

---

## Payments: Paymee (e-DINAR), Stripe, PayPal

Cette application gère plusieurs moyens de paiement pour les donations. Pour activer Paymee (e-DINAR), ajoutez ceci à votre `.env` :

```
PAYMEE_API_KEY=your_sandbox_or_live_key
PAYMEE_MODE=sandbox
PAYMEE_CURRENCY=TND
```

Ensuite, videz le cache de configuration et redémarrez le serveur.

Phase de test sandbox :
- Le flux utilise l'intégration "redirection" de Paymee. On crée un paiement via `POST /api/v2/payments/create` puis on redirige le donateur vers Paymee.
- Configurez un tunnel public (ex. ngrok) pour exposer le webhook `/webhooks/paymee`.
- Après le paiement, Paymee appelle le webhook avec `payment_status` et `check_sum`. Nous vérifions et enregistrons le don (idempotence via `transaction_id`).

Stripe et PayPal sont également intégrés : renseignez leurs clés dans `.env` pour les tester.
