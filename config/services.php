<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'currency' => env('STRIPE_CURRENCY', 'usd'),
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
        'mode' => env('PAYPAL_MODE', 'sandbox'), // or 'live'
        'currency' => env('PAYPAL_CURRENCY', env('STRIPE_CURRENCY', 'USD')),
    ],

    'paymee' => [
        // API key from Paymee dashboard (Sandbox or Live)
        'api_key' => env('PAYMEE_API_KEY'),
        // Mode: sandbox | live (controls which base URL is used)
        'mode' => env('PAYMEE_MODE', 'sandbox'),
        // Optional default currency for display/reference only
        'currency' => env('PAYMEE_CURRENCY', env('STRIPE_CURRENCY', 'TND')),
    ],

    'testpay' => [
        'enabled' => env('TEST_PAYMENTS_ENABLED', false),
    ],

    // Bank transfer coordinates (editable via .env)
    'bank' => [
        'beneficiary' => env('BANK_BENEFICIARY', 'TuniVert'),
        'iban' => env('BANK_IBAN', 'TN59 1000 0000 0000 0000 0000'),
        'swift' => env('BANK_SWIFT', 'TUNIVERTXXX'),
        'note' => env('BANK_TRANSFER_NOTE', 'Utilisez la référence reçue par email comme motif du virement.'),
    ],


    'openai' => [
    'key'   => env('OPENAI_API_KEY'),
    'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
],

    // External Donation AI microservice (Python FastAPI)
    'donation_ai' => [
        'url' => env('DONATION_AI_URL', null), // e.g., http://127.0.0.1:8085
    ],

    // HuggingFace API for sentiment analysis
    'huggingface' => [
        'api_key' => env('HF_API_KEY'),
        'model' => env('HF_MODEL', 'nlptown/bert-base-multilingual-uncased-sentiment'),
    ],

];
