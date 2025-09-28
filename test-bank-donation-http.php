#!/usr/bin/env php
<?php
// Test bank transfer donation via HTTP

$url = 'http://127.0.0.1:8000/donations';

// First, let's get the CSRF token by loading the donation page
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://127.0.0.1:8000/donation',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEJAR => 'cookies.txt',
    CURLOPT_COOKIEFILE => 'cookies.txt',
    CURLOPT_FOLLOWLOCATION => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Loaded donation page: HTTP $httpCode\n";

// Extract CSRF token from the response
preg_match('/name="_token" value="([^"]+)"/', $response, $matches);
$csrfToken = $matches[1] ?? null;

if (!$csrfToken) {
    echo "❌ Could not extract CSRF token\n";
    exit(1);
}

echo "✅ CSRF token extracted: " . substr($csrfToken, 0, 20) . "...\n";

// Now make the donation
$postData = [
    '_token' => $csrfToken,
    'montant' => '25.50',
    'moyen_paiement' => 'virement_bancaire',
    'evenement_id' => '', // Optional
    'is_anonymous' => '0'
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($postData),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_COOKIEJAR => 'cookies.txt',
    CURLOPT_COOKIEFILE => 'cookies.txt',
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/x-www-form-urlencoded',
        'X-Requested-With: XMLHttpRequest',
    ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Bank transfer donation response: HTTP $httpCode\n";
echo "Response: $response\n";

// Clean up
@unlink('cookies.txt');

if ($httpCode === 200 || $httpCode === 302) {
    echo "✅ Donation submitted successfully!\n";
    echo "Now check the logs for email entries...\n";
} else {
    echo "❌ Donation failed with HTTP code $httpCode\n";
}