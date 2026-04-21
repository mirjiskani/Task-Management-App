<?php

// Simple authentication test script

$baseUrl = 'http://localhost:8000';

echo "=== Authentication API Test Script ===\n\n";

// Test 1: Login
echo "1. Testing Login...\n";
$loginData = [
    'email' => 'test@example.com',
    'password' => 'password123'
];

$ch = curl_init($baseUrl . '/api/auth/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n\n";

if ($httpCode == 200) {
    $loginResponse = json_decode($response, true);
    if (isset($loginResponse->token)) {
        $token = $loginResponse->token;
        
        // Test 2: Validate Token
        echo "2. Testing Token Validation...\n";
        $validateData = ['token' => $token];
        
        $ch2 = curl_init($baseUrl . '/api/auth/validate');
        curl_setopt($ch2, CURLOPT_POST, true);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($validateData));
        curl_setopt($ch2, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        
        $response2 = curl_exec($ch2);
        $httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
        curl_close($ch2);
        
        echo "Status: $httpCode2\n";
        echo "Response: $response2\n\n\n";
        
        // Test 3: Get Profile (protected route)
        if ($httpCode2 == 200) {
            echo "3. Testing Protected Profile...\n";
            $ch3 = curl_init($baseUrl . '/api/users/profile');
            curl_setopt($ch3, CURLOPT_HTTPGET, true);
            curl_setopt($ch3, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
            curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
            
            $response3 = curl_exec($ch3);
            $httpCode3 = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
            curl_close($ch3);
            
            echo "Status: $httpCode3\n";
            echo "Response: $response3\n\n\n";
            
            // Test 4: Logout
            echo "4. Testing Logout...\n";
            $logoutData = ['token' => $token];
            
            $ch4 = curl_init($baseUrl . '/api/auth/logout');
            curl_setopt($ch4, CURLOPT_POST, true);
            curl_setopt($ch4, CURLOPT_POSTFIELDS, json_encode($logoutData));
            curl_setopt($ch4, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true);
            
            $response4 = curl_exec($ch4);
            $httpCode4 = curl_getinfo($ch4, CURLINFO_HTTP_CODE);
            curl_close($ch4);
            
            echo "Status: $httpCode4\n";
            echo "Response: $response4\n\n\n";
        }
    }
}

echo "=== Test Complete ===\n";
