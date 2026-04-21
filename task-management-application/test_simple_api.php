<?php

// Simple API test without complex headers

$baseUrl = 'http://localhost:8000';

echo "=== Simple API Test ===\n\n";

// Test 1: Register User
echo "1. Testing Register Endpoint...\n";
$registerData = json_encode([
    'name' => 'Simple Test User',
    'email' => 'simple@example.com',
    'password' => 'password123'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $registerData
    ]
]);

$response = file_get_contents($baseUrl . '/api/auth/register', false, $context);

if ($response !== false) {
    echo "✅ Server is running!\n";
    $result = json_decode($response, true);
    echo "Status: Registration successful\n";
    echo "Message: " . ($result['message'] ?? 'N/A') . "\n";
    echo "Token: " . substr($result['token'] ?? '', 0, 20) . "...\n\n";
} else {
    echo "❌ Server is not responding\n";
}

// Test 2: Login
echo "2. Testing Login Endpoint...\n";
$loginData = json_encode([
    'email' => 'simple@example.com',
    'password' => 'password123'
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $loginData
    ]
]);

$response = file_get_contents($baseUrl . '/api/auth/login', false, $context);

if ($response !== false) {
    echo "✅ Login successful!\n";
    $result = json_decode($response, true);
    echo "Message: " . ($result['message'] ?? 'N/A') . "\n";
    echo "Token: " . substr($result['token'] ?? '', 0, 20) . "...\n\n";
} else {
    echo "❌ Login failed\n";
}

echo "=== Test Complete ===\n";
