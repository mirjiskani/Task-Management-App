<?php

// Test the GET /api/users/{id} endpoint
$id = 1;  // Test with user ID 1
$url = "http://localhost:8000/api/users/$id";

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Content-Type: application/json\r\n',
        'timeout' => 10
    ]
]);

echo "Testing GET /api/users/$id endpoint..." . PHP_EOL;
echo "URL: " . $url . PHP_EOL;
echo PHP_EOL;

try {
    $response = file_get_contents($url, false, $context);
    
    if ($response === false) {
        echo "ERROR: Failed to get response from server" . PHP_EOL;
    } else {
        echo "RESPONSE:" . PHP_EOL;
        echo $response . PHP_EOL;
        echo PHP_EOL;
        
        // Try to parse JSON
        $data = json_decode($response, true);
        if ($data !== null) {
            echo "PARSED JSON:" . PHP_EOL;
            if (isset($data['error'])) {
                echo "Error: " . $data['error'] . PHP_EOL;
            } elseif (isset($data['user'])) {
                echo "User found:" . PHP_EOL;
                echo "- ID: " . $data['user']['id'] . PHP_EOL;
                echo "- Name: " . $data['user']['name'] . PHP_EOL;
                echo "- Email: " . $data['user']['email'] . PHP_EOL;
                echo "- Role: " . $data['user']['role'] . PHP_EOL;
                echo "- Status: " . $data['user']['status'] . PHP_EOL;
            }
        } else {
            echo "ERROR: Response is not valid JSON" . PHP_EOL;
        }
    }
    
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . PHP_EOL;
}
