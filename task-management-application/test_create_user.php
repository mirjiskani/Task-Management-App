<?php

// First create a user to test with
$url = 'http://localhost:8000/api/users/register';

$userData = [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => 'password123'
];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json\r\n',
        'content' => json_encode($userData),
        'timeout' => 10
    ]
]);

echo "Creating test user..." . PHP_EOL;
echo "URL: " . $url . PHP_EOL;
echo "Data: " . json_encode($userData) . PHP_EOL;
echo PHP_EOL;

try {
    $response = file_get_contents($url, false, $context);
    
    if ($response === false) {
        echo "ERROR: Failed to create user" . PHP_EOL;
    } else {
        echo "CREATE RESPONSE:" . PHP_EOL;
        echo $response . PHP_EOL;
        echo PHP_EOL;
        
        $data = json_decode($response, true);
        if ($data !== null && isset($data['user']['id'])) {
            echo "User created with ID: " . $data['user']['id'] . PHP_EOL;
            
            // Now test update
            $updateId = $data['user']['id'];
            $updateUrl = "http://localhost:8000/api/users/$updateId";
            
            $updateData = [
                'name' => 'Test User Updated',
                'email' => 'test.updated@example.com',
                'role' => 'user',
                'status' => 1
            ];
            
            $updateContext = stream_context_create([
                'http' => [
                    'method' => 'PUT',
                    'header' => 'Content-Type: application/json\r\n',
                    'content' => json_encode($updateData),
                    'timeout' => 10
                ]
            ]);
            
            echo PHP_EOL . "Testing PUT /api/users/$updateId..." . PHP_EOL;
            echo "Update Data: " . json_encode($updateData) . PHP_EOL;
            echo PHP_EOL;
            
            $updateResponse = file_get_contents($updateUrl, false, $updateContext);
            
            if ($updateResponse === false) {
                echo "ERROR: Failed to update user" . PHP_EOL;
            } else {
                echo "UPDATE RESPONSE:" . PHP_EOL;
                echo $updateResponse . PHP_EOL;
            }
        }
    }
    
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . PHP_EOL;
}
