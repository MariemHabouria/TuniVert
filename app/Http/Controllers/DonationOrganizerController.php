<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class DonationOrganizerController extends Controller
{
    public function byEvent(int $eventId)
    {
        $dons = Donation::where('evenement_id', $eventId)
            ->orderByDesc('date_don')
            ->paginate(20);

        $stats = [
            'total' => Donation::where('evenement_id', $eventId)->sum('montant'),
            'count' => Donation::where('evenement_id', $eventId)->count(),
        ];

        return view('organizer.donations.event', compact('dons','stats','eventId'));
    }
}
