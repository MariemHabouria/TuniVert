<?php 
namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;

class FormationInscriptionController extends Controller
{
    public function store(Request $request, Formation $formation) {
        // fermé/terminé ?
        if ($formation->statut !== 'ouverte') {
            return back()->withErrors(['inscription' => 'Cette formation n’est pas ouverte.']);
        }
        // capacité
        if ($formation->estComplete()) {
            return back()->withErrors(['inscription' => 'Cette formation est complète.']);
        }
        // déjà inscrit ?
        if ($request->user()->formationsInscrites()->where('formation_id',$formation->id)->exists()) {
            return back()->with('status', 'Vous êtes déjà inscrit(e).');
        }
        $request->user()->formationsInscrites()->attach($formation->id);
        return back()->with('status', 'Inscription confirmée !');
    }

    public function destroy(Request $request, Formation $formation) {
        $request->user()->formationsInscrites()->detach($formation->id);
        return back()->with('status', 'Désinscription effectuée.');
    }
}