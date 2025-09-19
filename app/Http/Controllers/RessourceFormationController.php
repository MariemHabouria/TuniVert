<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class RessourceFormationController extends Controller
{
    public function store(Request $request, Formation $formation)
    {
        // Optionnel : seul l’organisateur peut ajouter des ressources
        if ((int) $formation->organisateur_id !== (int) Auth::id()) {
            abort(403, 'Action non autorisée.');
        }

        // Validation de base
        $request->validate([
            'titre' => ['required','string','max:255'],
            'type'  => ['required', Rule::in(['pdf','ppt','video','lien'])],
        ]);

        // Validation conditionnelle :
        // - pour pdf/ppt -> on exige un fichier
        // - pour video/lien -> on exige une URL
        if (in_array($request->type, ['pdf','ppt'])) {
            $request->validate([
                'file' => ['required','file','max:10240', // 10 Mo
                          'mimetypes:application/pdf,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation'],
            ]);
        } else {
            $request->validate([
                'url' => ['required','string','max:2000'],
            ]);
        }

        // Déterminer la valeur finale de l'URL à enregistrer
        $finalUrl = null;

        if (in_array($request->type, ['pdf','ppt']) && $request->hasFile('file')) {
            // Enregistrer le fichier sur le disk "public"
            // => public/storage/formations/ressources/xxxx.pdf
            $path = $request->file('file')->store('formations/ressources', 'public');

            // Génère une URL accessible publiquement (via le symlink storage:link)
            $finalUrl = Storage::disk('public')->url($path);
        } else {
            // Lien externe (YouTube, Drive, site, …)
            $finalUrl = $request->input('url');
        }

        // Création de la ressource
        $formation->ressources()->create([
            'titre' => $request->titre,
            'type'  => $request->type,
            'url'   => $finalUrl,
        ]);

        return back()->with('status', 'Ressource ajoutée avec succès.');
    }
}
