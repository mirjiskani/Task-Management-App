<?php

// Test script for Tasks API

$baseUrl = 'http://localhost:8000/api/tasks';

echo "=== Tasks API Test Script ===\n\n";

// Test 1: Create Task
echo "1. Creating Task...\n";
$createData = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'role' => 'user',
    'task' => 'Complete the project documentation and ensure all API endpoints are working correctly with proper validation and error handling.'
];

$ch = curl_init($baseUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($createData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status: $httpCode\n";
echo "Response: $response\n\n";

$taskId = json_decode($response, true)['task']['id'] ?? null;

if ($taskId) {
    // Test 2: Get Task
    echo "2. Getting Task ID: $taskId...\n";
    $ch = curl_init("$baseUrl/$taskId");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Status: $httpCode\n";
    echo "Response: $response\n\n";

    // Test 3: Update Task
    echo "3. Updating Task ID: $taskId...\n";
    $updateData = [
        'name' => 'John Updated',
        'task' => 'Updated task description: Complete the project documentation with additional security features and performance optimizations.'
    ];
    
    $ch = curl_init("$baseUrl/$taskId");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updateData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Status: $httpCode\n";
    echo "Response: $response\n\n";

    // Test 4: Get All Tasks
    echo "4. Getting All Tasks...\n";
    $ch = curl_init($baseUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Status: $httpCode\n";
    echo "Response: $response\n\n";

    // Test 5: Delete Task
    echo "5. Deleting Task ID: $taskId...\n";
    $ch = curl_init("$baseUrl/$taskId");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Status: $httpCode\n";
    echo "Response: $response\n\n";
} else {
    echo "Failed to create task. Cannot continue with other tests.\n";
}

echo "=== Test Complete ===\n";
