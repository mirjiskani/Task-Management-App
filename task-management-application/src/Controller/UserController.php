<?php

namespace App\Controller;

use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

#[Route('/api/users')]
class UserController extends AbstractController
{
    private AuthService $authService;
    private ValidatorInterface $validator;

    public function __construct(AuthService $authService, ValidatorInterface $validator)
    {
        $this->authService = $authService;
        $this->validator = $validator;
    }

    // LOGIN - POST /api/users/login
    #[Route('/login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (!$data) {
                return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
            }

            // Input validation
            $errors = $this->validateLoginData($data);
            if (!empty($errors)) {
                return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }

            // Authenticate user
            $user = $this->authService->authenticate($data['email'], $data['password']);
            
            // Generate JWT token
            $token = $this->authService->generateToken($user, ['login' => true]);

            return new JsonResponse([
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole()
                ]
            ], Response::HTTP_OK);

        } catch (AuthenticationException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Login failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // REGISTER - POST /api/users/register
    #[Route('/register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (!$data) {
                return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
            }

            // Input validation
            $errors = $this->validateRegisterData($data);
            if (!empty($errors)) {
                return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }

            // Create user
            $user = $this->authService->createUser($data);
            
            // Generate token
            $token = $this->authService->generateToken($user, ['register' => true]);

            return new JsonResponse([
                'message' => 'Registration successful',
                'token' => $token,
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole()
                ]
            ], Response::HTTP_CREATED);

        } catch (AuthenticationException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Registration failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // LOGOUT - POST /api/users/logout
    #[Route('/logout', methods: ['POST'])]
    public function logout(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data || !isset($data['token'])) {
                return new JsonResponse(['error' => 'Token required'], Response::HTTP_BAD_REQUEST);
            }

            // Revoke token
            $this->authService->revokeToken($data['token']);

            return new JsonResponse(['message' => 'Logout successful']);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Logout failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // GET PROFILE - GET /api/users/profile
    #[Route('/profile', methods: ['GET'])]
    public function getProfile(): JsonResponse
    {
        try {
            $user = $this->getCurrentUser();
            
            return new JsonResponse([
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole()
                ]
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to get profile'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // UPDATE PROFILE - PUT /api/users/profile
    #[Route('/profile', methods: ['PUT'])]
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $user = $this->getCurrentUser();
            $data = json_decode($request->getContent(), true);
            
            if (!$data) {
                return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
            }

            // Update user profile
            if (isset($data['name'])) {
                $user->setName($data['name']);
            }
            
            if (isset($data['email'])) {
                // Email validation would be handled in service
                $user->setEmail($data['email']);
            }
            
            // Save user
            $this->authService->generateToken($user, ['profile_updated' => true]);

            return new JsonResponse([
                'message' => 'Profile updated successfully',
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole()
                ]
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to update profile'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // VALIDATE TOKEN - POST /api/users/validate
    #[Route('/validate', methods: ['POST'])]
    public function validateToken(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data || !isset($data['token'])) {
                return new JsonResponse(['error' => 'Token required'], Response::HTTP_BAD_REQUEST);
            }

            $user = $this->authService->validateToken($data['token']);
            
            if ($user && !$this->authService->isTokenExpired($data['token'])) {
                return new JsonResponse(['valid' => false, 'error' => 'Token expired']);
            }

            return new JsonResponse([
                'valid' => true,
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole()
                ]
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Token validation failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // REFRESH TOKEN - POST /api/users/refresh
    #[Route('/refresh', methods: ['POST'])]
    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            if (!$data || !isset($data['token'])) {
                return new JsonResponse(['error' => 'Token required'], Response::HTTP_BAD_REQUEST);
            }

            $newToken = $this->authService->refreshToken($data['token']);
            
            if ($newToken) {
                return new JsonResponse([
                    'message' => 'Token refreshed',
                    'token' => $newToken
                ]);
            } else {
                return new JsonResponse(['error' => 'Token refresh failed'], Response::HTTP_UNAUTHORIZED);
            }

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Token refresh failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function validateLoginData(array $data): array
    {
        $errors = [];
        
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        }
        
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        }
        
        return $errors;
    }

    private function validateRegisterData(array $data): array
    {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'Name is required';
        }
        
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        }
        
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        }
        
        return $errors;
    }

    private function getCurrentUser(): ?\App\Entity\Users
    {
        $token = $this->getTokenFromRequest();
        if (!$token) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException('Authentication required');
        }

        return $this->authService->validateToken($token);
    }

    private function getTokenFromRequest(Request $request): ?string
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
