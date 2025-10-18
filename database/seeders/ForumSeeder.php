<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Forum;
use App\Models\User;
use App\Models\ReponseForum;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier s'il y a au moins un utilisateur
        $user = User::first() ?? User::factory()->create();

        // Créer des forums avec les nouvelles colonnes
        $forum1 = Forum::create([
            'titre'             => 'Réduction des déchets plastiques',
            'contenu'           => 'Discutons des moyens de réduire l\'usage du plastique dans notre ville. Partagez vos idées et bonnes pratiques !',
            'utilisateur_id'    => $user->id,
            'tags'              => 'écologie, déchets, plastique, environnement',
            'nb_vues'           => 45,
            'nb_reponses'       => 3,
            'popularite_score'  => 60,
        ]);

        $forum2 = Forum::create([
            'titre'             => 'Améliorer le transport public',
            'contenu'           => 'Idées pour rendre le transport public plus rapide, écologique et accessible à tous. Quels sont vos retours d\'expérience ?',
            'utilisateur_id'    => $user->id,
            'tags'              => 'transport, écologie, mobilité, bus',
            'nb_vues'           => 78,
            'nb_reponses'       => 5,
            'popularite_score'  => 103,
        ]);

        $forum3 = Forum::create([
            'titre'             => 'Plus d\'espaces verts 🌳',
            'contenu'           => 'Propositions pour créer des parcs et jardins communautaires. Où aimeriez-vous voir plus de végétation en ville ?',
            'utilisateur_id'    => $user->id,
            'tags'              => 'espaces verts, parcs, nature, ville',
            'nb_vues'           => 32,
            'nb_reponses'       => 2,
            'popularite_score'  => 42,
        ]);

        $forum4 = Forum::create([
            'titre'             => 'Sécurité des piétons',
            'contenu'           => 'Points dangereux pour les piétons identifiés dans la ville. Propositions d\'amélioration de la sécurité.',
            'utilisateur_id'    => $user->id,
            'tags'              => 'sécurité, piétons, circulation, urbanisme',
            'nb_vues'           => 67,
            'nb_reponses'       => 4,
            'popularite_score'  => 87,
        ]);

        $forum5 = Forum::create([
            'titre'             => 'Événements culturels locaux',
            'contenu'           => 'Organisons ensemble des événements culturels dans notre quartier. Quels types d\'activités souhaiteriez-vous ?',
            'utilisateur_id'    => $user->id,
            'tags'              => 'culture, événements, communauté, loisirs',
            'nb_vues'           => 23,
            'nb_reponses'       => 1,
            'popularite_score'  => 28,
        ]);

        // Ajouter des réponses aux forums si le modèle existe
        if (class_exists(ReponseForum::class)) {
            $this->ajouterReponses($forum1, $forum2, $forum3, $user);
        }

        $this->command->info('💬 ' . Forum::count() . ' forums créés avec succès !');
    }

    /**
     * Ajouter des réponses aux forums
     */
    private function ajouterReponses($forum1, $forum2, $forum3, $user)
    {
        // Réponses pour le forum 1
        ReponseForum::create([
            'forum_id' => $forum1->id,
            'utilisateur_id' => $user->id,
            'contenu' => 'Je propose l\'installation de fontaines à eau dans les lieux publics pour réduire les bouteilles en plastique.',
        ]);

        ReponseForum::create([
            'forum_id' => $forum1->id,
            'utilisateur_id' => $user->id,
            'contenu' => 'Les commerçants pourraient proposer des sacs réutilisables et des contenants consignés.',
        ]);

        // Réponses pour le forum 2
        ReponseForum::create([
            'forum_id' => $forum2->id,
            'utilisateur_id' => $user->id,
            'contenu' => 'Des bus électriques seraient une excellente solution pour réduire la pollution et le bruit.',
        ]);

        ReponseForum::create([
            'forum_id' => $forum2->id,
            'utilisateur_id' => $user->id,
            'contenu' => 'Plus de fréquences le weekend serait très apprécié, surtout pour les travailleurs en décalé.',
            'est_resolution' => true, // Marquer comme solution
        ]);

        // Réponses pour le forum 3
        ReponseForum::create([
            'forum_id' => $forum3->id,
            'utilisateur_id' => $user->id,
            'contenu' => 'L\'ancien terrain industriel près de la gare serait parfait pour un nouveau parc.',
        ]);

        $this->command->info('💬 Réponses ajoutées aux forums !');
    }
}