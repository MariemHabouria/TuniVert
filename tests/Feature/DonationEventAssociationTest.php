<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationEventAssociationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_donation_submitted_with_event_id_is_linked_to_that_event()
    {
        // Create a user and event first
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $this->be($user);

        $payload = [
            'montant' => 25.50,
            'moyen_paiement' => 'virement_bancaire',
            'evenement_id' => $event->id,
            'is_anonymous' => false,
        ];

        $resp = $this->post(route('donations.store'), $payload);
        $resp->assertStatus(302);

        $this->assertDatabaseHas('donations', [
            'utilisateur_id' => $user->id,
            'evenement_id' => 1,
            'montant' => 25.50,
            'moyen_paiement' => 'virement_bancaire',
        ]);
    }
}
