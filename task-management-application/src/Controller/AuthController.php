<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/auth')]
class AuthController extends AbstractController
{
    // LOGIN - POST /api/auth/login
    #[Route('/login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data || !isset($data['email']) || !isset($data['password'])) {
                return new JsonResponse(['error' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
            }

            // For demo purposes - return a mock token
            // In production, this would validate against database
            $token = bin2hex(random_bytes(32));
            
            return new JsonResponse([
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => 1,
                    'name' => 'Demo User',
                    'email' => $data['email']
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Login failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // REGISTER - POST /api/auth/register
    #[Route('/register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data || !isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
                return new JsonResponse(['error' => 'Name, email, and password are required'], Response::HTTP_BAD_REQUEST);
            }

            // For demo purposes - return a mock user
            $token = bin2hex(random_bytes(32));
            
            return new JsonResponse([
                'message' => 'Registration successful',
                'token' => $token,
                'user' => [
                    'id' => 2,
                    'name' => $data['name'],
                    'email' => $data['email']
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Registration failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // LOGOUT - POST /api/auth/logout
    #[Route('/logout', methods: ['POST'])]
    public function logout(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data || !isset($data['token'])) {
                return new JsonResponse(['error' => 'Token required'], Response::HTTP_BAD_REQUEST);
            }

            // For demo purposes - always succeed
            return new JsonResponse(['message' => 'Logout successful']);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Logout failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // VALIDATE TOKEN - POST /api/auth/validate
    #[Route('/validate', methods: ['POST'])]
    public function validateToken(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data || !isset($data['token'])) {
                return new JsonResponse(['error' => 'Token required'], Response::HTTP_BAD_REQUEST);
            }

            // For demo purposes - simple validation
            if (strlen($data['token']) < 10) {
                return new JsonResponse(['valid' => false, 'error' => 'Invalid token format']);
            }

            return new JsonResponse([
                'valid' => true,
                'user' => [
                    'id' => 1,
                    'name' => 'Demo User',
                    'email' => 'demo@example.com'
                ]
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Token validation failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
