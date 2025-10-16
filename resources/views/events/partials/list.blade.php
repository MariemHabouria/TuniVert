@if($events->count() > 0)
    <div class="event-carousel owl-carousel">
        @foreach ($events as $event)
            <div class="event-item">
                <div class="event-img-wrapper">
                    <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}">
                </div>
                <div class="event-content p-4">
                    <h4>{{ $event->title }}</h4>
                    <p>{{ Str::limit($event->details, 100) }}</p>
                    <span>{{ $event->location }}</span>
                    <span>{{ \Carbon\Carbon::parse($event->date)->format('d M, Y') }}</span>
                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary">Details</a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-center fs-5 text-muted">Aucun événement disponible pour le moment.</p>
@endif
