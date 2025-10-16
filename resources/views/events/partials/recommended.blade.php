<!-- Recommended Events Start -->
<div class="container-fluid event py-5 bg-light mt-5">
    <div class="container py-5">
        <div class="text-center mx-auto mb-5" style="max-width: 800px;">
            <h5 class="text-uppercase text-primary">Recommended For You</h5>
            <h1 class="mb-0">Events you might be interested in</h1>
        </div>

        @auth
        @if($recommendedEvents->count() > 0)
            <div class="event-carousel owl-carousel">
                @foreach($recommendedEvents as $event)
                    <div class="event-item">
                        <img src="{{ Storage::url($event->image) }}" class="img-fluid w-100" alt="{{ $event->title }}">
                        <div class="event-content p-4">
                            <span class="badge bg-success mb-2">Recommended</span>
                            <h4>{{ $event->title }}</h4>
                            <p>{{ Str::limit($event->details, 100) }}</p>
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center fs-5 text-muted">No recommended events at the moment.</p>
        @endif
        @else
            <p class="text-center fs-5 text-muted">Connectez-vous pour voir vos événements recommandés.</p>
        @endauth
    </div>
</div>
<!-- Recommended Events End -->
