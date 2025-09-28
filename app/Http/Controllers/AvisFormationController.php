<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\AvisFormation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisFormationController extends Controller
{
    public function store(Request $request, Formation $formation)
    {
        $data = $request->validate([
            'note'        => ['required','integer','min:1','max:5'],
            'commentaire' => ['nullable','string','max:2000'],
        ]);

        AvisFormation::updateOrCreate(
            [
                'formation_id' => $formation->id,
                'user_id'      => Auth::id(),
            ],
            [
                'note'         => $data['note'],
                'commentaire'  => $data['commentaire'] ?? null,
            ]
        );

        return back()->with('status', 'Merci pour votre avis !');
    }
}
