<?php

require_once 'vendor/autoload.php';

use Symfony\Bundle\FrameworkBundle\Kernel;
use Doctrine\ORM\EntityManager;

try {
    $kernel = new Kernel('dev', false);
    $kernel->boot();
    $container = $kernel->getContainer();
    $entityManager = $container->get('doctrine.orm.entity_manager');
    
    $users = $entityManager->getRepository('App\Entity\Users')->findAll();
    echo 'Total users found: ' . count($users) . PHP_EOL;
    
    foreach ($users as $user) {
        echo 'User ID: ' . $user->getId() . ', Email: ' . $user->getEmail() . ', Status: ' . $user->getStatus() . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
