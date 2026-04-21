<?php

namespace App\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\InMemoryTokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Service\TokenManager;
use App\Repository\UsersRepository;

class AuthMiddleware
{
    private InMemoryTokenStorage $tokenStorage;
    private TokenManager $tokenManager;
    private UsersRepository $userRepository;

    public function __construct(
        InMemoryTokenStorage $tokenStorage,
        TokenManager $tokenManager,
        UsersRepository $userRepository
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->tokenManager = $tokenManager;
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request, $next): JsonResponse
    {
        // Skip authentication for certain routes
        $excludedRoutes = ['/api/auth/login', '/api/auth/register', '/api/auth/validate', '/api/auth/refresh'];
        
        if (in_array($request->getPathInfo(), $excludedRoutes)) {
            return $next($request);
        }

        // Extract token from header
        $token = $this->extractTokenFromRequest($request);
        
        if (!$token) {
            return new JsonResponse(['error' => 'Authentication token required'], Response::HTTP_UNAUTHORIZED);
        }

        // Validate token
        $user = $this->tokenManager->validate($token);
        if (!$user) {
            return new JsonResponse(['error' => 'Invalid or expired token'], Response::HTTP_UNAUTHORIZED);
        }

        // Add user to request for downstream use
        $request->attributes->set('_user', $user);

        return $next($request);
    }

    private function extractTokenFromRequest(Request $request): ?string
    {
        $authHeader = $request->headers->get('Authorization');
        
        if (!$authHeader) {
            return null;
        }

        // Extract token from "Bearer <token>"
        if (preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
