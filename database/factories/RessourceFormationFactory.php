<?php

namespace Database\Factories;

use App\Models\RessourceFormation;
use Illuminate\Database\Eloquent\Factories\Factory;

class RessourceFormationFactory extends Factory
{
    protected $model = RessourceFormation::class;

    public function definition(): array
    {
        $types = ['pdf','ppt','video','lien'];
        $type = $this->faker->randomElement($types);

        // génère une URL plausible selon le type
        $url = match ($type) {
            'pdf'  => 'https://files.test/'.strtolower($this->faker->slug()).'.pdf',
            'ppt'  => 'https://files.test/'.strtolower($this->faker->slug()).'.pptx',
            'video'=> 'https://www.youtube.com/watch?v='.$this->faker->bothify('###########'),
            default=> 'https://'.$this->faker->domainName().'/'.$this->faker->slug(),
        };

        return [
            'formation_id' => null, // défini dans le seeder
            'titre'        => ucfirst($this->faker->words(2, true)),
            'type'         => $type,
            'url'          => $url,
        ];
    }
}
