{{--
    Badge Unlock Popup Component
    Trigger: controller sets session()->flash('new_badges', [ [slug,name,icon,description], ... ])
    Notes: Uses session flash so appears on the next full page load only. If you redirect twice, preserve data manually.
    Example:
            if($awarded) session()->flash('new_badges', $awarded);
    Safe to include multiple times; renders nothing if no badges.
--}}
@php($__newBadges = session('new_badges'))
@if($__newBadges && count($__newBadges))
@php($__badgeCount = count($__newBadges))
<div id="badgeUnlockOverlay" class="badge-unlock-overlay" role="dialog" aria-modal="true" aria-labelledby="badgeUnlockTitle">
    <div class="badge-unlock-modal animate-pop" role="document">
            <button type="button" class="badge-close" aria-label="Fermer" onclick="hideBadgePopup()">&times;</button>
            <div id="badgeLiveRegion" class="visually-hidden" aria-live="assertive" aria-atomic="true">{{ $__badgeCount }} nouveau(x) badge(s) débloqué(s)</div>
            <div class="text-center mb-3">
                    <h3 id="badgeUnlockTitle" class="fw-bold text-gradient mb-1"><i class="fas fa-trophy me-2"></i>
                        @if($__badgeCount === 1)
                            Nouveau Badge Débloqué !
                        @else
                            {{ $__badgeCount }} Badges Débloqués !
                        @endif
                    </h3>
                    <p class="text-muted small mb-0">
                        @if($__badgeCount === 1)
                            Incroyable ! Vous venez de gagner un nouveau badge ✨
                        @else
                            Superbe série ! Vous venez d'en gagner {{ $__badgeCount }} 💎
                        @endif
                    </p>
                    <div class="celebration-sub small mt-1 text-success fw-semibold">Continuez à soutenir les causes pour en collectionner davantage.</div>
            </div>
            <div class="row g-3 justify-content-center">
                    @foreach($__newBadges as $b)
                            <div class="col-6 col-sm-4 col-md-3">
                                    <div class="badge-card h-100 text-center p-2">
                                            <div class="badge-icon-wrapper mx-auto mb-2">
                                                    <span class="badge-icon">{{ $b['icon'] ?? '🏅' }}</span>
                                                    <span class="badge-glow"></span>
                                            </div>
                                            <div class="badge-name fw-semibold small">{{ $b['name'] ?? $b['slug'] }}</div>
                                            @if(!empty($b['description']))
                                                <div class="badge-desc text-muted tiny mt-1">{{ $b['description'] }}</div>
                                            @endif
                                    </div>
                            </div>
                    @endforeach
            </div>
            <div class="text-center mt-4">
                    <a href="{{ route('donations.history') }}" class="btn btn-sm btn-primary px-4 me-2">
                            <i class="fas fa-history me-1"></i>Historique
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-secondary px-4" onclick="hideBadgePopup()">Fermer</button>
            </div>
            <div class="confetti-host" aria-hidden="true"></div>
    </div>
</div>
<noscript>
    <div class="container mt-3">
        <div class="alert alert-success p-3 small"><strong>Bravo !</strong> Vous avez débloqué {{ $__badgeCount }} badge(s). Activez JavaScript pour une expérience animée.</div>
    </div>
</noscript>
@endif

<!-- Badge Popup Inline Assets (intentionally inline; avoiding Blade stack directives) -->
<style>
.badge-unlock-overlay {position:fixed; inset:0; background:rgba(15,23,42,0.70); backdrop-filter: blur(4px); z-index:2000; display:flex; align-items:center; justify-content:center; padding:1.5rem; animation:fadeIn .4s ease forwards;}
.badge-unlock-modal {background:#ffffff; border-radius:24px; padding:2rem 2rem 2.5rem; width:100%; max-width:760px; position:relative; box-shadow:0 15px 35px -10px rgba(0,0,0,.35),0 4px 8px rgba(0,0,0,.07); border:1px solid rgba(0,0,0,0.05);}
.badge-close {position:absolute; top:10px; right:14px; background:transparent; border:none; font-size:1.75rem; line-height:1; cursor:pointer; color:#64748b; transition:all .25s ease;}
.badge-close:hover {color:#334155; transform: rotate(15deg);}
.text-gradient {background:linear-gradient(90deg,#0d6efd,#198754 55%,#0d6efd); -webkit-background-clip:text; color:transparent;}
.badge-card {background:linear-gradient(145deg,#f8fafc,#ffffff); border:1px solid #e2e8f0; border-radius:18px; position:relative; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.06); transition:all .35s cubic-bezier(.4,0,.2,1);} 
.badge-card:before {content:""; position:absolute; inset:0; background:radial-gradient(circle at 30% 20%,rgba(13,110,253,0.10),transparent 70%); opacity:0; transition:opacity .4s;}
.badge-card:hover {transform:translateY(-6px) scale(1.03); box-shadow:0 12px 25px -8px rgba(0,0,0,.18);}
.badge-card:hover:before {opacity:1;}
.badge-icon-wrapper {position:relative; width:80px; height:80px; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#ffffff 0%,#e0f2fe 100%); border-radius:22px; box-shadow:inset 0 2px 6px rgba(255,255,255,.6), 0 6px 14px -4px rgba(14,165,233,.35);}
.badge-icon {font-size:40px; filter: drop-shadow(0 4px 4px rgba(0,0,0,0.15)); animation:floatY 3.5s ease-in-out infinite;}
.badge-glow {position:absolute; inset:-4px; background:conic-gradient(from 0deg,rgba(13,110,253,.4),rgba(25,135,84,.4),rgba(13,110,253,.4)); filter:blur(18px); opacity:.0; animation:spinGlow 6s linear infinite; border-radius:26px;}
.badge-card:hover .badge-glow {opacity:.55;}
.badge-name {font-size:.78rem; letter-spacing:.3px;}
.badge-desc {font-size:.65rem; line-height:1.1;}
.tiny {font-size: .60rem;}
.animate-pop {animation:popScale .55s cubic-bezier(.34,1.56,.64,1) forwards; transform-origin:center center;}
/* Accessibility helper */
.visually-hidden {position:absolute !important;width:1px !important;height:1px !important;padding:0 !important;margin:-1px !important;overflow:hidden !important;clip:rect(0 0 0 0) !important;white-space:nowrap !important;border:0 !important;}
.celebration-sub {letter-spacing:.3px;}
.confetti-host {position:absolute; inset:0; pointer-events:none; overflow:hidden; border-radius:inherit;}
.confetti-piece {position:absolute; width:10px; height:14px; top:-10px; opacity:0; border-radius:2px; will-change:transform,opacity;}
@keyframes popScale {0% {transform:scale(.65) translateY(30px); opacity:0;} 65% {transform:scale(1.03) translateY(-4px); opacity:1;} 100% {transform:scale(1) translateY(0); opacity:1;}}
@keyframes fadeIn {from {opacity:0;} to {opacity:1;}}
@keyframes floatY {0%,100% {transform:translateY(0);} 50% {transform:translateY(-6px);} }
@keyframes spinGlow {from {transform:rotate(0);} to {transform:rotate(360deg);} }
@media (max-width: 575.98px){ .badge-icon-wrapper {width:70px;height:70px;} .badge-icon {font-size:34px;} }
/* Fallback fadeOut keyframes if not present */
@keyframes fadeOut { to { opacity:0; transform:scale(.95); } }
</style>

<script>
function hideBadgePopup(){ const el = document.getElementById('badgeUnlockOverlay'); if(!el) return; el.style.animation='fadeOut .35s ease forwards'; setTimeout(()=>el.remove(), 320);} 
// Auto-close after 12s (extended for reading)
setTimeout(()=>{ hideBadgePopup(); }, 12000);

// Lightweight confetti generator
(function(){
    const host = document.querySelector('#badgeUnlockOverlay .confetti-host');
    if(!host) return;
    const colors = ['#0d6efd','#198754','#ffc107','#20c997','#6610f2','#e83e8c'];
    const pieces = 70;
    for(let i=0;i<pieces;i++){
        const d=document.createElement('div');
        d.className='confetti-piece';
        d.style.left=(Math.random()*100)+'%';
        const clr=colors[Math.floor(Math.random()*colors.length)];
        d.style.background=clr;
        d.style.transform=`translate3d(0,0,0) rotate(${Math.random()*360}deg)`;
        const dur=3+Math.random()*2;
        const delay=Math.random()*0.8;
        d.style.animation=`confettiFall ${dur}s linear ${delay}s forwards`;
        host.appendChild(d);
    }
})();

// Confetti keyframes injection (only once)
(function(){
    if(document.getElementById('confettiKeyframes')) return;
    const style=document.createElement('style');
    style.id='confettiKeyframes';
    style.textContent=`@keyframes confettiFall {0%{opacity:0; transform:translateY(-10px) rotate(0deg);} 10%{opacity:1;} 100%{opacity:1; transform:translateY(110%) rotate(720deg);} }`;
    document.head.appendChild(style);
})();
 </script>
@if(isset($__badgeCount))
<script>
// Also highlight the corresponding badge cards in the history list if present on the page
(function(){
    try {
        const earned = @json( collect($__newBadges)->pluck('slug') );
        if(!earned || !earned.length) return;
        // Wait a tick to allow list rendering (in case component included above list)
        setTimeout(()=>{
            let firstEl = null;
            earned.forEach(slug => {
                const card = document.querySelector('[data-badge="'+slug+'"]');
                if(card){
                    if(!firstEl) firstEl = card;
                    card.classList.add('just-earned-badge');
                    // Add NEW ribbon if not already there
                    if(!card.querySelector('.new-ribbon')) {
                        const r = document.createElement('div');
                        r.className='new-ribbon';
                        r.innerHTML='<span>NEW</span>';
                        card.appendChild(r);
                    }
                }
            });
            if(firstEl){
                // Smooth scroll to first new badge (unless overlay still visible; delay until overlay closes)
                const scrollFn = ()=>{ try { firstEl.scrollIntoView({behavior:'smooth', block:'center'}); } catch(e) {} };
                // If overlay auto-closes after 12s, also schedule scroll after hide to refocus context
                setTimeout(scrollFn, 1400);
                setTimeout(scrollFn, 5000);
            }
        }, 50);
    } catch(e) { console.warn('[BadgePopup] highlight failed', e); }
})();

// Mini toast after overlay closes (gives persistent confirmation)
(function(){
    const earned = @json($__badgeCount);
    if(!earned) return;
    function createToast(){
        if(document.getElementById('badgeMiniToast')) return;
        const t=document.createElement('div');
        t.id='badgeMiniToast';
        t.className='badge-mini-toast';
        t.innerHTML='<div class="toast-inner"><i class="fas fa-trophy me-2 text-warning"></i><strong>'+earned+' badge'+(earned>1?'s':'')+' unlocked</strong><button type="button" aria-label="Close" class="btn-close btn-close-white ms-3" style="filter:invert(1) contrast(200%);"></button></div>';
        document.body.appendChild(t);
        requestAnimationFrame(()=> t.classList.add('show'));
        t.querySelector('button').addEventListener('click',()=>{ t.classList.remove('show'); setTimeout(()=>t.remove(),300); });
        setTimeout(()=>{ if(t.parentNode){ t.classList.remove('show'); setTimeout(()=>t.remove(),350); } }, 10000);
    }
    // When overlay removed, show toast
    const ov = document.getElementById('badgeUnlockOverlay');
    if(ov){
        const obs=new MutationObserver(()=>{ if(!document.getElementById('badgeUnlockOverlay')) { obs.disconnect(); createToast(); } });
        obs.observe(document.body,{childList:true});
    } else {
        // No overlay (edge case) - show toast immediately
        setTimeout(createToast, 600);
    }
})();
</script>
@endif

<style>
/* Highlight newly earned badges embedded in list */
.just-earned-badge { box-shadow:0 0 0 0 rgba(255,193,7,0.9); animation: pulseEarned 2.5s ease-in-out 2, glowBorder 6s linear infinite; position:relative; }
@keyframes pulseEarned { 0%{transform:scale(1);} 25%{transform:scale(1.03);} 50%{transform:scale(.98);} 75%{transform:scale(1.02);} 100%{transform:scale(1);} }
@keyframes glowBorder { 0%{box-shadow:0 0 0 0 rgba(255,193,7,0.4);} 50%{box-shadow:0 0 20px 4px rgba(255,193,7,0.7);} 100%{box-shadow:0 0 0 0 rgba(255,193,7,0.4);} }
.new-ribbon { position:absolute; top:6px; left:-6px; background:linear-gradient(135deg,#dc2626,#f97316); color:#fff; padding:2px 8px; font-size:.55rem; font-weight:600; letter-spacing:.5px; border-radius:4px; box-shadow:0 2px 6px rgba(0,0,0,.2); transform:rotate(-8deg); }
.new-ribbon span { display:inline-block; }

/* Mini toast */
.badge-mini-toast { position:fixed; bottom:18px; right:18px; background:rgba(15,23,42,0.92); color:#fff; padding:10px 16px; border-radius:14px; backdrop-filter:blur(4px); box-shadow:0 8px 24px -6px rgba(0,0,0,.45); opacity:0; transform:translateY(10px) scale(.95); transition: all .4s cubic-bezier(.4,0,.2,1); z-index:1999; font-size:.8rem; }
.badge-mini-toast.show { opacity:1; transform:translateY(0) scale(1); }
.badge-mini-toast .toast-inner { display:flex; align-items:center; }
.badge-mini-toast i { font-size:1rem; }
.badge-mini-toast button { background:transparent; border:0; width:20px; height:20px; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; }
</style>