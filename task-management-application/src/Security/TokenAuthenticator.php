<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\InMemoryTokenStorage;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Entity\Users;

class TokenAuthenticator implements AuthenticatorInterface
{
    private InMemoryTokenStorage $tokenStorage;

    public function __construct(InMemoryTokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(TokenInterface $token): bool
    {
        return true; // This authenticator supports token authentication
    }

    public function authenticate(TokenInterface $token): UserInterface
    {
        try {
            $payload = json_decode($token->getCredentials(), true);
            
            if (!$payload || !isset($payload['user_id'])) {
                throw new \Symfony\Component\Security\Core\Exception\AuthenticationException('Invalid token');
            }

            // Find user by ID (more secure than email)
            $userRepository = $this->getUserRepository();
            $user = $userRepository->find($payload['user_id']);
            
            if (!$user) {
                throw new \Symfony\Component\Security\Core\Exception\AuthenticationException('User not found');
            }

            if ($user->getStatus() !== 1) {
                throw new \Symfony\Component\Security\Core\Exception\AuthenticationException('Account is disabled');
            }

            return new User($user);
        } catch (\Exception $e) {
            throw new \Symfony\Component\Security\Core\Exception\AuthenticationException('Authentication failed');
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): void
    {
        // Store token in session for subsequent requests
        $this->tokenStorage->setToken($token);
    }

    public function onAuthenticationFailure(Request $request, \Exception $exception): void
    {
        // Clear invalid token
        $this->tokenStorage->setToken(null);
    }

    private function getUserRepository(): \App\Repository\UsersRepository
    {
        // This would be injected in a real app
        return new \App\Repository\UsersRepository(
            null,
            Users::class
        );
    }
}
