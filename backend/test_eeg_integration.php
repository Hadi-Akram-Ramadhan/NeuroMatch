<?php

// Test script for EEG upload and ML integration
$baseUrl = 'http://localhost:8000/api';

// Use a unique email for each test run
echo "Testing NeuroMatch EEG+ML Integration\n";
echo "====================================\n\n";

// Step 1: Register a new user
$email = 'test' . time() . '@example.com';
$registerData = [
    'name' => 'Integration User',
    'email' => $email,
    'password' => 'password123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/register');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($registerData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "1. Registering user...\n";
echo "Status Code: $httpCode\n";
echo "Response: $response\n\n";

if ($httpCode !== 200) {
    echo "❌ Registration failed!\n";
    exit(1);
}

$data = json_decode($response, true);
$token = $data['access_token'] ?? null;
if (!$token) {
    echo "❌ No token received!\n";
    exit(1);
}
echo "✅ Registration successful!\n\n";

// Step 2: Upload EEG file
echo "2. Uploading EEG file...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/eeg/upload');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Authorization: Bearer ' . $token
]);
$postData = [
    'eeg_file' => new CURLFile('sample_eeg.csv', 'text/csv', 'sample_eeg.csv')
];
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status Code: $httpCode\n";
echo "Response: $response\n\n";
if ($httpCode !== 201) {
    echo "❌ EEG upload failed!\n";
    exit(1);
}
echo "✅ EEG upload and ML analysis successful!\n\n";

// Step 3: Get profile (mood/personality)
echo "3. Getting user profile...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/eeg/profile');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Authorization: Bearer ' . $token
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Status Code: $httpCode\n";
echo "Response: $response\n\n";
if ($httpCode === 200) {
    echo "✅ Profile retrieved successfully!\n";
    echo "🎉 All integration tests passed!\n";
} else {
    echo "❌ Failed to get profile!\n";
    exit(1);
}

echo "\nTest completed.\n";
