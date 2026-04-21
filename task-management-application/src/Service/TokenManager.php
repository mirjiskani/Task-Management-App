<?php

namespace App\Service;

use App\Entity\Users;
use App\Repository\UsersRepository;

class TokenManager
{
    private UsersRepository $userRepository;
    private static array $tokens = [];

    public function __construct(UsersRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create(string $name, Users $user, array $payload = []): string
    {
        $token = bin2hex(random_bytes(32));
        
        // Store token with user data in static array
        self::$tokens[$token] = [
            'user' => $user,
            'created' => time(),
            'payload' => $payload
        ];
        
        return $token;
    }

    public function validate(string $token): ?Users
    {
        try {
            if (isset(self::$tokens[$token])) {
                return self::$tokens[$token]['user'];
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function revoke(string $token): void
    {
        if (isset(self::$tokens[$token])) {
            unset(self::$tokens[$token]);
        }
    }
}
