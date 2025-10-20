<?php

use App\Models\Event;
use Illuminate\Support\Facades\DB;

$events = Event::whereNull('image')->orWhere('image', '')->get();

foreach($events as $event) {
    $title = strtolower($event->title);
    
    if(strpos($title, 'beach') !== false || strpos($title, 'plage') !== false) {
        $event->image = 'img/events/beach-cleanup.jpg';
    } elseif(strpos($title, 'tree') !== false || strpos($title, 'plant') !== false) {
        $event->image = 'img/events/tree-planting.jpg';
    } elseif(strpos($title, 'clean') !== false || strpos($title, 'nettoyage') !== false) {
        $event->image = 'img/events/recycling.jpg';
    } elseif(strpos($title, 'food') !== false || strpos($title, 'distribution') !== false) {
        $event->image = 'img/events/awareness-campaign.jpg';
    } else {
        $event->image = 'img/events/conference.jpg';
    }
    
    $event->save();
    echo "Updated event: {$event->title} with image: {$event->image}\n";
}

echo "Updated {$events->count()} events with images\n";