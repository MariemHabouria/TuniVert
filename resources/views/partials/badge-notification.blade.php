{{--
Global Badge Notification System
Shows immediately when new badges are earned via session flash data
Priority: Shows global popup first, then history page modal can still be used
--}}

@if(session('new_badges'))
    <!-- Debug: Badge notification is active -->
    <script>console.log('Badge notification active with badges:', @json(session('new_badges')));</script>
    
    <!-- Enhanced Badge Unlock Notification -->
    <div id="badgeNotificationOverlay" class="position-fixed w-100 h-100 d-flex align-items-center justify-content-center" 
         style="top: 0; left: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); z-index: 9999;">
        
        <div class="card border-0 shadow-lg badge-notification-card" style="max-width: 500px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; overflow: hidden;">
            <!-- Header -->
            <div class="card-header text-center text-white border-0 p-4" style="background: transparent;">
                <div class="position-relative">
                    <!-- Close button -->
                    <button type="button" class="btn-close btn-close-white position-absolute close-button" 
                            onclick="closeBadgeNotification()" aria-label="Fermer" 
                            style="top: 10px; right: 10px; font-size: 1.2rem; opacity: 0.9; transition: all 0.3s ease; z-index: 10; cursor: pointer; background-size: 1em;">
                    </button>
                    
                    <!-- Alternative close button with custom icon (fallback) -->
                    <div class="position-absolute custom-close-button" 
                         onclick="closeBadgeNotification()" 
                         style="top: 10px; right: 60px; width: 30px; height: 30px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; z-index: 10;"
                         onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                         onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        <i class="fas fa-times text-white" style="font-size: 16px;"></i>
                    </div>
                    
                    <!-- Trophy animation -->
                    <div class="trophy-container mb-3">
                        <i class="fas fa-trophy text-warning trophy-icon" style="font-size: 4rem; animation: trophyBounce 2s ease-in-out infinite;"></i>
                        <div class="sparkles position-absolute w-100 h-100 top-0 start-0 d-flex align-items-center justify-content-center">
                            <span class="sparkle-1" style="position: absolute; top: 20%; left: 20%; font-size: 1.5rem; animation: sparkle 1.5s ease-in-out infinite;">✨</span>
                            <span class="sparkle-2" style="position: absolute; top: 30%; right: 25%; font-size: 1.2rem; animation: sparkle 1.8s ease-in-out infinite 0.3s;">⭐</span>
                            <span class="sparkle-3" style="position: absolute; bottom: 25%; left: 30%; font-size: 1.3rem; animation: sparkle 2s ease-in-out infinite 0.6s;">💫</span>
                            <span class="sparkle-4" style="position: absolute; bottom: 20%; right: 20%; font-size: 1.4rem; animation: sparkle 1.7s ease-in-out infinite 0.9s;">🌟</span>
                        </div>
                    </div>
                    
                    <h3 class="fw-bold mb-2">🎉 Félicitations! 🎉</h3>
                    <p class="mb-0 fs-5">Vous avez débloqué {{ count(session('new_badges')) }} nouveau{{ count(session('new_badges')) > 1 ? 'x' : '' }} badge{{ count(session('new_badges')) > 1 ? 's' : '' }}!</p>
                </div>
            </div>
            
            <!-- Badge Details -->
            <div class="card-body text-white p-4">
                @foreach(session('new_badges') as $index => $badge)
                    <div class="badge-unlock-item d-flex align-items-center gap-3 mb-3 p-3 rounded-3" 
                         style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); animation: slideInUp 0.6s ease-out {{ $index * 0.2 }}s both;">
                        
                        <!-- Badge Icon -->
                        <div class="badge-icon-wrapper position-relative">
                            <div class="badge-icon-container d-flex align-items-center justify-content-center text-white rounded-circle"
                                 style="width: 60px; height: 60px; background: linear-gradient(135deg, #ffd700, #ffb347); box-shadow: 0 4px 20px rgba(255,215,0,0.4);">
                                <span style="font-size: 2rem; animation: iconSpin 3s ease-in-out infinite;">{{ $badge['icon'] ?? '🏅' }}</span>
                            </div>
                            <!-- Glow effect -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 rounded-circle" 
                                 style="background: radial-gradient(circle, rgba(255,215,0,0.4) 0%, transparent 70%); animation: glow 2s ease-in-out infinite alternate;"></div>
                        </div>
                        
                        <!-- Badge Info -->
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">{{ $badge['name'] ?? 'Nouveau Badge' }}</h5>
                            <p class="mb-0 opacity-90 small">{{ $badge['description'] ?? 'Badge débloqué avec succès!' }}</p>
                        </div>
                        
                        <!-- Celebration emoji -->
                        <div class="celebration-emoji" style="font-size: 2rem; animation: celebration 1s ease-in-out infinite alternate;">
                            🎊
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Footer -->
            <div class="card-footer text-center border-0 p-4" style="background: rgba(255,255,255,0.1);">
                <button type="button" class="btn btn-light btn-lg px-4 py-2 fw-bold fantastique-btn" onclick="closeBadgeNotification()"
                        style="border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-check me-2"></i>Fantastique!
                </button>
                <div class="mt-2">
                    <a href="{{ route('donations.history') }}" class="text-white text-decoration-none small opacity-75">
                        <i class="fas fa-trophy me-1"></i>Voir tous mes badges
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced CSS Animations -->
    <style>
        @keyframes trophyBounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0) rotate(0deg); }
            40% { transform: translateY(-20px) rotate(-10deg); }
            60% { transform: translateY(-10px) rotate(5deg); }
        }
        
        @keyframes sparkle {
            0%, 100% { opacity: 0.7; transform: scale(1) rotate(0deg); }
            50% { opacity: 1; transform: scale(1.2) rotate(180deg); }
        }
        
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes iconSpin {
            0%, 90%, 100% { transform: rotate(0deg) scale(1); }
            45% { transform: rotate(10deg) scale(1.1); }
            55% { transform: rotate(-10deg) scale(1.1); }
        }
        
        @keyframes glow {
            from { opacity: 0.3; }
            to { opacity: 0.7; }
        }
        
        @keyframes celebration {
            from { transform: rotate(-10deg) scale(1); }
            to { transform: rotate(10deg) scale(1.1); }
        }
        
        .badge-notification-card {
            animation: popIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }
        
        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.3) rotate(-10deg); }
            70% { transform: scale(1.05) rotate(2deg); }
            100% { opacity: 1; transform: scale(1) rotate(0deg); }
        }
        
        /* Confetti particles */
        .confetti-particle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #ffd700;
            animation: confettiFall 3s linear infinite;
        }
        
        @keyframes confettiFall {
            to {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.8); }
        }
        
        .close-button:hover {
            opacity: 1 !important;
            transform: scale(1.1);
            background-color: rgba(255, 255, 255, 0.2) !important;
        }
        
        .close-button {
            width: 32px !important;
            height: 32px !important;
            border-radius: 50% !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        .close-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25) !important;
        }
        
        .fantastique-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3) !important;
        }
    </style>

    <!-- JavaScript for notification control -->
    <script>
        function closeBadgeNotification() {
            console.log('closeBadgeNotification() called');
            const overlay = document.getElementById('badgeNotificationOverlay');
            console.log('Overlay element:', overlay);
            if (overlay) {
                console.log('Applying fadeOut animation...');
                overlay.style.animation = 'fadeOut 0.5s ease-out forwards';
                setTimeout(() => {
                    overlay.remove();
                    console.log('Badge notification removed successfully');
                }, 500);
            } else {
                console.error('Badge notification overlay not found!');
            }
        }
        
        // Add click event listener directly to the close button for better reliability
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, setting up close buttons...');
            
            // Handle Bootstrap close button
            const closeButton = document.querySelector('.close-button');
            console.log('Bootstrap close button found:', closeButton);
            
            if (closeButton) {
                closeButton.addEventListener('click', function(e) {
                    console.log('Bootstrap close button clicked via event listener');
                    e.preventDefault();
                    e.stopPropagation();
                    closeBadgeNotification();
                });
            }
            
            // Handle custom close button
            const customCloseButton = document.querySelector('.custom-close-button');
            console.log('Custom close button found:', customCloseButton);
            
            if (customCloseButton) {
                customCloseButton.addEventListener('click', function(e) {
                    console.log('Custom close button clicked via event listener');
                    e.preventDefault();
                    e.stopPropagation();
                    closeBadgeNotification();
                });
            }
            
            const overlay = document.getElementById('badgeNotificationOverlay');
            if (overlay) {
                overlay.addEventListener('click', function(e) {
                    if (e.target === this) {
                        console.log('Background clicked');
                        closeBadgeNotification();
                    }
                });
            }
        });
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeBadgeNotification();
            }
        });
        
        // Auto-close after 15 seconds (increased from 10)
        setTimeout(() => {
            closeBadgeNotification();
        }, 15000);
        
        // Add confetti effect
        function createConfetti() {
            const colors = ['#ffd700', '#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7'];
            
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti-particle';
                    confetti.style.left = Math.random() * 100 + 'vw';
                    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDelay = Math.random() * 2 + 's';
                    confetti.style.animationDuration = (2 + Math.random() * 3) + 's';
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => {
                        confetti.remove();
                    }, 5000);
                }, i * 100);
            }
        }
        
        // Start confetti
        createConfetti();
    </script>
@endif
