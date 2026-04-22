<?php

// Test CORS headers
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Origin: http://localhost:3000\r\n"
    ]
]);

echo "Testing CORS headers..." . PHP_EOL;
echo "Request: GET http://localhost:8000/api/users" . PHP_EOL;
echo "Origin: http://localhost:3000" . PHP_EOL;
echo PHP_EOL;

try {
    $response = file_get_contents('http://localhost:8000/api/users', false, $context);
    
    if ($response === false) {
        echo "ERROR: Failed to get response" . PHP_EOL;
    } else {
        echo "Response received successfully!" . PHP_EOL;
        echo "Response length: " . strlen($response) . " bytes" . PHP_EOL;
        echo PHP_EOL;
        
        // Parse JSON response
        $data = json_decode($response, true);
        if ($data !== null) {
            echo "JSON parsed successfully!" . PHP_EOL;
            if (isset($data['users'])) {
                echo "Users found: " . count($data['users']) . PHP_EOL;
            }
        } else {
            echo "Response is not valid JSON" . PHP_EOL;
        }
    }
    
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . PHP_EOL;
}
