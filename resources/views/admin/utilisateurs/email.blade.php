<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Association Vérifiée</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .header {
            background: linear-gradient(135deg, #1cc88a 0%, #17a673 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .header .emoji {
            font-size: 64px;
            display: block;
            margin-bottom: 20px;
        }
        .content {
            padding: 40px 30px;
            color: #333;
            line-height: 1.8;
        }
        .content h2 {
            color: #1cc88a;
            font-size: 24px;
            margin: 30px 0 20px;
            border-bottom: 2px solid #1cc88a;
            padding-bottom: 10px;
        }
        .info-box {
            background: #f8f9fc;
            border-left: 4px solid #1cc88a;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .info-box p {
            margin: 10px 0;
            font-size: 15px;
        }
        .info-box strong {
            color: #2c3e50;
            font-weight: 600;
        }
        .benefits {
            margin: 30px 0;
        }
        .benefit-item {
            display: flex;
            align-items: start;
            margin: 15px 0;
            padding: 15px;
            background: #f8f9fc;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .benefit-item:hover {
            background: #e3f9f0;
            transform: translateX(5px);
        }
        .benefit-icon {
            font-size: 24px;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .benefit-text {
            flex: 1;
        }
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 16px 40px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            margin: 30px 0;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }
        .footer {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }
        .footer p {
            margin: 10px 0;
            opacity: 0.8;
        }
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #1cc88a, transparent);
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <span class="emoji">🎉</span>
            <h1>Félicitations !</h1>
            <p style="font-size: 18px; opacity: 0.95; margin-top: 10px;">
                Votre association a été vérifiée avec succès
            </p>
        </div>

        <!-- Content -->
        <div class="content">
            <p style="font-size: 16px;">
                Bonjour <strong>{{ $association->name }}</strong>,
            </p>
            
            <p style="margin-top: 20px;">
                Nous avons le plaisir de vous informer que votre association a été 
                <strong style="color: #1cc88a;">officiellement vérifiée</strong> par notre équipe administrative.
            </p>

            <h2>📋 Informations de vérification</h2>
            
            <div class="info-box">
                <p><strong>Nom de l'association :</strong> {{ $association->name }}</p>
                <p><strong>Email :</strong> {{ $association->email }}</p>
                @if($association->matricule)
                <p><strong>Matricule :</strong> <span style="color: #1cc88a; font-family: monospace;">{{ $association->matricule }}</span></p>
                @endif
                <p><strong>Date de vérification :</strong> {{ now()->format('d/m/Y à H:i') }}</p>
            </div>

            <div class="divider"></div>

            <h2>✨ Vos nouveaux avantages</h2>
            
            <div class="benefits">
                <div class="benefit-item">
                    <span class="benefit-icon">✅</span>
                    <div class="benefit-text">
                        <strong>Accès complet à la plateforme</strong>
                        <p style="margin-top: 5px; color: #6c757d;">Profitez de toutes les fonctionnalités sans restriction</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <span class="benefit-icon">🏆</span>
                    <div class="benefit-text">
                        <strong>Badge "Association Vérifiée"</strong>
                        <p style="margin-top: 5px; color: #6c757d;">Votre profil affiche désormais un badge de confiance</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <span class="benefit-icon">📅</span>
                    <div class="benefit-text">
                        <strong>Création d'événements</strong>
                        <p style="margin-top: 5px; color: #6c757d;">Organisez et promouvez vos événements</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <span class="benefit-icon">💪</span>
                    <div class="benefit-text">
                        <strong>Challenges et forums</strong>
                        <p style="margin-top: 5px; color: #6c757d;">Participez activement à la communauté</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <span class="benefit-icon">👥</span>
                    <div class="benefit-text">
                        <strong>Visibilité accrue</strong>
                        <p style="margin-top: 5px; color: #6c757d;">Augmentez votre notoriété auprès de la communauté</p>
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ route('dashboard') }}" class="btn-primary">
                    🚀 Accéder à mon espace
                </a>
            </div>

            <p style="color: #6c757d; font-size: 14px; margin-top: 30px;">
                Si vous avez des questions ou besoin d'assistance, notre équipe de support est à votre disposition.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="font-size: 16px; font-weight: 600; margin-bottom: 15px;">
                Merci de votre confiance
            </p>
            <p>L'équipe {{ config('app.name') }}</p>
            <div style="margin: 20px 0; height: 1px; background: rgba(255,255,255,0.2);"></div>
            <p style="font-size: 12px;">
                Vous recevez cet email car votre association a été vérifiée sur notre plateforme.<br>
                Si vous pensez qu'il s'agit d'une erreur, veuillez nous contacter immédiatement.
            </p>
        </div>
    </div>
</body>
</html>