<?php

// Simple authentication test without complex security

$baseUrl = 'http://localhost:8000';

echo "=== Simple Auth Test ===\n\n";

// Test 1: Check if server is running
echo "1. Testing Server Connection...\n";
$ch = curl_init($baseUrl . '/api/users');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

if ($httpCode == 200) {
    echo "✅ Server is running!\n";
    
    // Test 2: Test existing user endpoint
    echo "2. Testing Existing User Endpoint...\n";
    $ch2 = curl_init($baseUrl . '/api/users');
    curl_setopt($ch2, CURLOPT_POST, true);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'role' => 'user'
    ]));
    curl_setopt($ch2, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

    $response2 = curl_exec($ch2);
    $httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
    curl_close($ch2);

    echo "Status: $httpCode2\n";
    echo "Response: $response2\n\n";
    
    if ($httpCode2 == 201) {
        echo "✅ User creation working!\n";
    } else {
        echo "❌ User creation failed\n";
    }
} else {
    echo "❌ Server is not running\n";
    echo "Please start the server with: php -S localhost:8000 -t public\n";
}

echo "=== Test Complete ===\n";
