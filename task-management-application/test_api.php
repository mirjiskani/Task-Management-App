<?php

// Test script for User API

$baseUrl = 'http://localhost:8000/api/users';

echo "=== User API Test Script ===\n\n";

// Test 1: Register User
echo "1. Registering User...\n";
$registerData = json_encode([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'password123'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $registerData
    ]
]);

$response = file_get_contents("$baseUrl/register", false, $context);
$result = json_decode($response, true);

echo "Status: 201\n";
echo "Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n\n";

$token = $result['token'] ?? null;

if ($token) {
    // Test 2: Login User
    echo "2. Logging In...\n";
    $loginData = json_encode([
        'email' => 'john@example.com',
        'password' => 'password123'
    ]);
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => $loginData
        ]
    ]);
    
    $response = file_get_contents("$baseUrl/login", false, $context);
    $result = json_decode($response, true);
    
    echo "Status: 200\n";
    echo "Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n\n";

    // Test 3: Get Profile
    echo "3. Getting User Profile...\n";
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "Authorization: Bearer $token"
        ]
    ]);
    
    $response = file_get_contents("$baseUrl/profile", false, $context);
    $result = json_decode($response, true);
    
    echo "Status: 200\n";
    echo "Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n\n";

    // Test 4: Logout
    echo "4. Logging Out...\n";
    $logoutData = json_encode(['token' => $token]);
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => $logoutData
        ]
    ]);
    
    $response = file_get_contents("$baseUrl/logout", false, $context);
    $result = json_decode($response, true);
    
    echo "Status: 200\n";
    echo "Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n\n";
} else {
    echo "Failed to register user. Cannot continue with other tests.\n";
}

echo "=== Test Complete ===\n";
