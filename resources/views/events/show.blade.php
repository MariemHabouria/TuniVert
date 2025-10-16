<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>TuniVert - {{ $event->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet"> 

    <!-- Icon Fonts -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap & Custom CSS -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    @include('layouts.navbar')
<!-- Contenu principal -->
<div class="container py-5" style="margin-top: 120px;">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="row g-0">
         
        <!-- Image -->
            <div class="col-md-5">
                @if($event->image)
                    <img src="{{ Storage::url($event->image) }}" class="img-fluid h-100 w-100 rounded-start" style="object-fit: cover;" alt="{{ $event->title }}">
                @else
                    <img src="{{ asset('img/default-event.jpg') }}" class="img-fluid h-100 w-100 rounded-start" style="object-fit: cover;" alt="Image par défaut">
                @endif
            </div>

            <!-- Infos -->
            <div class="col-md-7">
                <div class="card-body p-4">
                    
                    <h2 class="card-title text-primary mb-4">{{ $event->title }}</h2>
                    <p class="mb-2"><i class="bi bi-geo-alt-fill text-danger me-2"></i><strong>Lieu :</strong> {{ $event->location }}</p>
                    <p class="mb-2"><i class="bi bi-calendar-event text-success me-2"></i><strong>Date :</strong> {{ \Carbon\Carbon::parse($event->date)->format('d M, Y') }}</p>
                    <p class="mb-2"><i class="bi bi-tags-fill text-warning me-2"></i><strong>Catégorie :</strong> {{ $event->category ?? 'N/A' }}</p>
                    <p class="mb-3"><i class="bi bi-info-circle-fill text-info me-2"></i><strong>Détails :</strong> {{ $event->details ?? 'N/A' }}</p>
                    <p class="mb-4"><i class="bi bi-person-circle text-primary me-2"></i><strong>Organisateur :</strong> {{ $event->organizer->name ?? 'N/A' }}</p>
  <!-- Nombre de participants -->
        <p class="mb-4"><i class="bi bi-people-fill text-secondary me-2"></i>
            <strong>Participants :</strong> {{ $event->participants->count() }}
        </p>

  
                    <div class="d-flex">
    <a href="{{ route('events.index') }}" class="btn btn-outline-secondary me-2">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
    
    <!-- Donate CTA: sends user to the public donation page with this event preselected -->
    <a href="{{ route('donation') }}?event={{ $event->id }}#donate" class="btn btn-success me-2">
        <i class="bi bi-heart-fill"></i> Faire un don
    </a>

  @auth
    @if(Auth::id() === $event->organizer_id)
        <div class="d-flex gap-2">
            <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> Modifier
            </a>
            <form action="{{ route('events.destroy', $event->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    @endif
@endauth

</div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chatbot spécifique à la page événement -->
<div id="chatbot" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; width: 300px; font-family: sans-serif;">
    <button id="chat-toggle" class="btn btn-primary w-100 mb-2">💬 Chat avec le bot</button>

    <div id="chat-window" style="display:none; border:1px solid #ccc; border-radius:10px; background:#fff; max-height:400px; overflow-y:auto; padding:10px;">
        <!-- Messages seront injectés ici -->
    </div>

    <div id="chat-input-container" style="display:none; margin-top:5px;">
        <input type="text" id="chat-input" class="form-control mb-2" placeholder="Pose ta question sur l'événement..." />
        <button id="chat-send" class="btn btn-primary w-100">Envoyer</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('chat-toggle');
    const chatWindow = document.getElementById('chat-window');
    const inputContainer = document.getElementById('chat-input-container');
    const sendBtn = document.getElementById('chat-send');
    const chatInput = document.getElementById('chat-input');

    toggleBtn.addEventListener('click', () => {
        const isVisible = chatWindow.style.display === 'block';
        chatWindow.style.display = isVisible ? 'none' : 'block';
        inputContainer.style.display = isVisible ? 'none' : 'block';
    });

    async function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;

        chatWindow.innerHTML += `<div><strong>Vous:</strong> ${message}</div>`;
        chatInput.value = '';
        chatWindow.scrollTop = chatWindow.scrollHeight;

        const res = await fetch("{{ route('chatbot.ask') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
        });

        const data = await res.json();
        chatWindow.innerHTML += `<div><strong>Bot:</strong> ${data.reply}</div>`;
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }

    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });
});
</script>

<h3>Commentaires</h3>

<!-- Formulaire d'ajout de commentaire (seul utilisateur connecté) -->
@auth
<form action="{{ route('comments.store', $event->id) }}" method="POST" class="mb-4">
    @csrf
    <textarea name="content" class="form-control mb-2" placeholder="Ajoutez un commentaire..." required></textarea>
    <button type="submit" class="btn btn-primary">Commenter</button>
</form>
@endauth

<!-- Liste des commentaires -->
<div class="comments-section mt-4">
    @forelse($event->comments as $comment)
    <div class="d-flex mb-3 align-items-start comment-card p-3 rounded shadow-sm bg-light">
        <!-- Avatar -->
        <div class="flex-shrink-0 me-3">
            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px; font-weight:bold; font-size:18px;">
                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
            </div>
        </div>

        <!-- Contenu -->
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <strong class="text-primary">{{ $comment->user->name }}</strong>
                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            </div>

            <p class="mb-2">{{ $comment->content }}</p>

            @if(Auth::id() === $comment->user_id)
            <div class="d-flex gap-2">
                <!-- Modifier -->
                <form action="{{ route('comments.update', $comment->id) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    @method('PUT')
                    <input type="text" name="content" value="{{ $comment->content }}" class="form-control form-control-sm w-auto" placeholder="Modifier...">
                    <button type="submit" class="btn btn-sm btn-outline-warning">Modifier</button>
                </form>

                <!-- Supprimer -->
                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @empty
    <p class="text-muted text-center">Aucun commentaire pour le moment.</p>
    @endforelse
</div>

<!-- CSS additionnel à mettre dans votre style.css ou dans un <style> -->
<style>
.comment-card input.form-control-sm {
    min-width: 200px;
}
.comment-card .avatar {
    font-family: 'Jost', sans-serif;
}
.comment-card p {
    margin-bottom: 0.5rem;
}
</style>



<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

 <!--Footer Start -->
        <div class="container-fluid footer bg-dark text-body py-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item">
                            <h4 class="mb-4 text-white">Newsletter</h4>
                            <p class="mb-4">Dolor amet sit justo amet elitr clita ipsum elitr est.Lorem ipsum dolor sit amet, consectetur adipiscing elit consectetur adipiscing elit.</p>
                            <div class="position-relative mx-auto">
                                <input class="form-control border-0 bg-secondary w-100 py-3 ps-4 pe-5" type="text" placeholder="Enter your email">
                                <button type="button" class="btn-hover-bg btn btn-primary position-absolute top-0 end-0 py-2 mt-2 me-2">SignUp</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="mb-4 text-white">Our Services</h4>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Ocean Turtle</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> White Tiger</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Social Ecology</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Loneliness</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Beauty of Life</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Present for You</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="mb-4 text-white">Volunteer</h4>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Karen Dawson</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Jack Simmons</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Michael Linden</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Simon Green</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Natalie Channing</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Caroline Gerwig</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item">
                            <h4 class="mb-4 text-white">Our Gallery</h4>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-1.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-1.jpg" data-lightbox="footerGallery-1" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                               <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-2.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-2.jpg" data-lightbox="footerGallery-2" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-3.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-3.jpg" data-lightbox="footerGallery-3" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-4.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-4.jpg" data-lightbox="footerGallery-4" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-5.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-5.jpg" data-lightbox="footerGallery-5" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                               <div class="col-4">
									<div class="footer-gallery">
										<img src="img/gallery-footer-6.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-6.jpg" data-lightbox="footerGallery-6" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->
</body>
</html>
