<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class DonationAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::query();
        if ($request->filled('moyen_paiement')) {
            $query->where('moyen_paiement', $request->string('moyen_paiement'));
        }
        $dons = $query->orderByDesc('date_don')->paginate(20)->withQueryString();

        $stats = [
                'total' => (clone $query)->sum('montant'),
                'count' => (clone $query)->count(),
        ];

        return view('admin.donations.index', compact('dons','stats'));
    }
}
