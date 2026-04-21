<?php

// Test the API endpoint directly
$url = 'http://localhost:8000/api/users';

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Content-Type: application/json\r\n',
        'timeout' => 10
    ]
]);

echo "Testing GET /api/users endpoint..." . PHP_EOL;
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
            echo "Users count: " . (isset($data['total']) ? $data['total'] : 'N/A') . PHP_EOL;
            if (isset($data['users']) && is_array($data['users'])) {
                echo "User details:" . PHP_EOL;
                foreach ($data['users'] as $user) {
                    echo "- ID: " . $user['id'] . ", Email: " . $user['email'] . PHP_EOL;
                }
            }
        } else {
            echo "ERROR: Response is not valid JSON" . PHP_EOL;
        }
    }
    
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . PHP_EOL;
}
