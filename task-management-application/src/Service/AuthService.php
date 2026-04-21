<?php

namespace App\Service;

use App\Entity\Users;
use App\Repository\UsersRepository;

class AuthService
{
    private UsersRepository $userRepository;
    private TokenManager $tokenManager;

    public function __construct(
        UsersRepository $userRepository,
        TokenManager $tokenManager
    ) {
        $this->userRepository = $userRepository;
        $this->tokenManager = $tokenManager;
    }

    public function authenticate(string $email, string $password): ?Users
    {
        $user = $this->userRepository->findByEmail($email);
        
        if (!$user) {
            throw new \RuntimeException('Invalid credentials');
        }

        if ($user->getStatus() !== 1) {
            throw new \RuntimeException('Account is disabled');
        }

        // Simple password verification (use password_hash/password_verify in production)
        if (!$this->verifyPassword($password, $user->getPassword())) {
            throw new \RuntimeException('Invalid credentials');
        }

        return $user;
    }

    private function verifyPassword(string $plainPassword, string $hashedPassword): bool
    {
        // For now, simple string comparison for demo purposes
        // In production, use proper hashing: password_verify($plainPassword, $hashedPassword)
        return password_verify($plainPassword, $hashedPassword) || ($plainPassword === $hashedPassword);
    }

    public function createUser(array $data): Users
    {
        $user = new Users();
        $user->setName($data['name'] ?? '');
        $user->setEmail($data['email'] ?? '');
        $user->setPassword($data['password'] ?? '');
        $user->setRole($data['role'] ?? 'user');
        $user->setStatus($data['status'] ?? 1);
        
        // For demo, just create and return the user without persisting to DB
        return $user;
    }

    public function generateToken(Users $user, array $payload = []): string
    {
        return $this->tokenManager->create('auth_token', $user, $payload);
    }

    public function validateToken(string $token): ?Users
    {
        $userId = $this->tokenManager->validateToken($token);
        
        if (!$userId) {
            return null;
        }

        return $this->userRepository->find($userId);
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function revokeToken(string $token): void
    {
        $this->tokenManager->revoke($token);
    }

    public function isTokenExpired(string $token): bool
    {
        // Simple expiration check for demo
        return false;
    }

    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->find($id);
        if ($user) {
            $this->userRepository->delete($user);
        }
    }
}
