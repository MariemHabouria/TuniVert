<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Badge;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Hash;

class EssentialDataSeeder extends Seeder
{
    /**
     * Run the database seeder for essential system data.
     */
    public function run(): void
    {
        $this->command->info('🔧 Creating essential system data...');

        // Create essential users
        $this->createEssentialUsers();
        
        // Create payment methods
        $this->createPaymentMethods();
        
        // Create essential badges
        $this->createEssentialBadges();

        $this->command->info('✅ Essential data created successfully!');
    }

    private function createEssentialUsers()
    {
        $this->command->info('👥 Creating essential users...');

        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@tunivert.com'],
            [
                'name' => 'TuniVert Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'is_association' => false,
            ]
        );

        // Association user
        User::firstOrCreate(
            ['email' => 'association@tunivert.com'],
            [
                'name' => 'Association TuniVert',
                'password' => Hash::make('association123'),
                'role' => 'association',
                'email_verified_at' => now(),
                'is_association' => true,
                'matricule_association' => 'ASSOC001',
            ]
        );

        // Regular user
        User::firstOrCreate(
            ['email' => 'user@tunivert.com'],
            [
                'name' => 'Utilisateur Test',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'is_association' => false,
            ]
        );
    }

    private function createPaymentMethods()
    {
        $this->command->info('💳 Creating payment methods...');

        $paymentMethods = [
            [
                'name' => 'Carte Bancaire',
                'key' => 'stripe',
                'active' => true,
                'icon_path' => 'img/payment/stripe.png',
                'type' => 'online',
                'button_text' => 'Payer par Carte',
                'description' => 'Paiement sécurisé par carte bancaire',
                'sort_order' => 1,
            ],
            [
                'name' => 'PayPal',
                'key' => 'paypal',
                'active' => true,
                'icon_path' => 'img/payment/paypal.png',
                'type' => 'online',
                'button_text' => 'Payer avec PayPal',
                'description' => 'Paiement via votre compte PayPal',
                'sort_order' => 2,
            ],
            [
                'name' => 'e-DINAR (Paymee)',
                'key' => 'paymee',
                'active' => true,
                'icon_path' => 'img/payment/paymee.png',
                'type' => 'online',
                'button_text' => 'Payer avec e-DINAR',
                'description' => 'Paiement mobile avec e-DINAR',
                'sort_order' => 3,
            ],
            [
                'name' => 'Virement Bancaire',
                'key' => 'bank_transfer',
                'active' => true,
                'icon_path' => 'img/payment/bank.png',
                'type' => 'offline',
                'button_text' => 'Virement Bancaire',
                'description' => 'Virement bancaire traditionnel',
                'sort_order' => 4,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::firstOrCreate(
                ['key' => $method['key']],
                $method
            );
        }
    }

    private function createEssentialBadges()
    {
        $this->command->info('🏆 Creating essential badges...');

        $badges = [
            [
                'name' => 'Premier Don',
                'slug' => 'first_donation',
                'description' => 'Félicitations pour votre premier don !',
                'icon' => '🎉',
            ],
            [
                'name' => 'Donateur Bronze',
                'slug' => 'bronze_donor',
                'description' => 'Donateur fidèle avec plus de 50 TND de dons',
                'icon' => '🥉',
            ],
            [
                'name' => 'Donateur Argent',
                'slug' => 'silver_donor',
                'description' => 'Donateur généreux avec plus de 200 TND de dons',
                'icon' => '🥈',
            ],
            [
                'name' => 'Donateur Or',
                'slug' => 'gold_donor',
                'description' => 'Donateur exceptionnel avec plus de 500 TND de dons',
                'icon' => '🥇',
            ],
            [
                'name' => 'Protecteur des Océans',
                'slug' => 'protector_oceans',
                'description' => 'Défenseur de nos océans avec plus de 100 TND de dons',
                'icon' => '🌊',
            ],
        ];

        foreach ($badges as $badgeData) {
            Badge::firstOrCreate(
                ['slug' => $badgeData['slug']],
                $badgeData
            );
        }
    }
}