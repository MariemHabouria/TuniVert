<?php

namespace Database\Factories;

use App\Models\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormationFactory extends Factory
{
    protected $model = Formation::class;

    public function definition(): array
    {
        $types = ['formation','atelier','conférence','webinaire'];
        $statuts = ['ouverte','suspendue','terminee'];

        return [
            'titre'          => ucfirst($this->faker->words(3, true)),
            'description'    => $this->faker->paragraph(3),
            'categorie'      => $this->faker->randomElement(['Environnement','Recyclage','Énergie','Biodiversité','Climat']),
            'type'           => $this->faker->randomElement($types),
            'capacite'       => $this->faker->numberBetween(10, 80),
            'organisateur_id'=> null, // défini dans le seeder
            'image'          => 'https://picsum.photos/seed/'.md5($this->faker->uuid()).'/800/450',
            'lien_visio'     => $this->faker->boolean(60) ? 'https://meet.google.com/'.strtolower($this->faker->bothify('???-#####-???')) : null,
            'statut'         => $this->faker->randomElement($statuts),
            // si tu as timestamps auto, pas besoin de date_creation/date_mise_a_jour
        ];
    }
}
