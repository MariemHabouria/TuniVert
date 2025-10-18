@php
    $newBadges = session('new_badges');
    $points = session('points_earned');
@endphp

<!-- DEBUG: Badge popup partial loaded -->
<script>console.log('Badge popup partial loaded. new_badges:', @json($newBadges), 'points:', @json($points));</script>

@if(!empty($newBadges) || !empty($points))
    <!-- DEBUG: Toast container will render -->
    <script>console.log('Rendering toast container with', @json(is_array($newBadges) ? count($newBadges) : 0), 'badges');</script>
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
        @if(!empty($points))
            <div class="toast mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="6000">
                <div class="toast-header">
                    <i class="fas fa-star text-warning me-2"></i>
                    <strong class="me-auto">Points gagnés</strong>
                    <small>Maintenant</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Vous avez gagné <strong>+{{ (int)$points }}</strong> points. Bravo !
                </div>
            </div>
        @endif

        @if(is_array($newBadges))
            @foreach($newBadges as $b)
                <div class="toast mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="7000">
                    <div class="toast-header">
                        <span class="me-2" style="font-size: 1.25rem; line-height: 1;">{{ $b['icon'] ?? '🏅' }}</span>
                        <strong class="me-auto">Badge débloqué</strong>
                        <small>Maintenant</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        <div class="fw-semibold">{{ $b['name'] ?? ($b['slug'] ?? 'Nouveau badge') }}</div>
                        <div class="text-muted small">{{ $b['description'] ?? '' }}</div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <script>
    (function(){
        function showBadgeToasts() {
            try {
                var container = document.querySelector('.toast-container');
                console.log('Toast container element:', container);
                console.log('Bootstrap available:', !!window.bootstrap);
                if (!container || !window.bootstrap) return;
                var toasts = container.querySelectorAll('.toast');
                console.log('Found', toasts.length, 'toasts to show');
                toasts.forEach(function(el, idx){
                    var t = new bootstrap.Toast(el, { autohide: true, delay: (idx === 0 ? 6000 : 7000) + idx * 400 });
                    console.log('Showing toast', idx, 'at delay', (idx === 0 ? 6000 : 7000) + idx * 400);
                    setTimeout(function(){ t.show(); }, idx * 450);
                });
            } catch (e) { console.error('Toast error:', e); }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showBadgeToasts, { once: true });
        } else {
            console.log('DOM already ready, showing toasts immediately');
            showBadgeToasts();
        }
    })();
    </script>
@endif
