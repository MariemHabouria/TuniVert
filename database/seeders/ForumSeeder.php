<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Forum;
use App\Models\User;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        Forum::create([
            'titre'          => 'Réduction des déchets plastiques',
            'contenu'        => 'Discutons des moyens de réduire l’usage du plastique dans notre ville.',
            'utilisateur_id' => 1, // ⚠️ Un utilisateur existant
        ]);

        Forum::create([
            'titre'          => 'Améliorer le transport public',
            'contenu'        => 'Idées pour rendre le transport public plus rapide et écologique.',
            'utilisateur_id' => 1,
        ]);

        Forum::create([
            'titre'          => 'Plus d’espaces verts 🌳',
            'contenu'        => 'Propositions pour créer des parcs et jardins communautaires.',
            'utilisateur_id' => 1,
        ]);
    }
}
