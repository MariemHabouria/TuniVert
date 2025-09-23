<?php

namespace Database\Factories;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Donation>
 */
class DonationFactory extends Factory
{
    protected $model = Donation::class;

    public function definition(): array
    {
        $userId = User::inRandomOrder()->value('id') ?? User::factory();
        $moyens = ['carte','paypal','virement_bancaire','paymee','test'];
        $moyen = $this->faker->randomElement($moyens);
        return [
            'utilisateur_id' => $userId,
            'evenement_id' => $this->faker->optional(0.7)->randomElement([1,2,3,4]),
            'montant' => $this->faker->randomFloat(2, 5, 500),
            'moyen_paiement' => $moyen,
            'transaction_id' => $this->faker->optional()->uuid(),
            'date_don' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'date_creation' => now(),
            'date_mise_a_jour' => now(),
        ];
    }
}
