<?php

// Simple test to check if users exist in database
try {
    // Start Symfony kernel
    $kernel = new \App\Kernel('dev', false);
    $kernel->boot();
    
    // Get entity manager
    $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
    
    // Get all users
    $users = $entityManager->getRepository(\App\Entity\Users::class)->findAll();
    
    echo "=== USER COUNT TEST ===" . PHP_EOL;
    echo "Total users found: " . count($users) . PHP_EOL;
    echo PHP_EOL;
    
    if (count($users) > 0) {
        echo "=== USER DETAILS ===" . PHP_EOL;
        foreach ($users as $user) {
            echo "ID: " . $user->getId() . PHP_EOL;
            echo "Name: " . $user->getName() . PHP_EOL;
            echo "Email: " . $user->getEmail() . PHP_EOL;
            echo "Role: " . $user->getRole() . PHP_EOL;
            echo "Status: " . $user->getStatus() . PHP_EOL;
            echo "Created: " . $user->getCreatedAt()->format('Y-m-d H:i:s') . PHP_EOL;
            echo "---" . PHP_EOL;
        }
    } else {
        echo "No users found in database!" . PHP_EOL;
        echo "You may need to create some users first." . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace:" . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
