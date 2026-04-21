<?php

namespace App\Controller;

use App\Service\UserService;
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
    private UserService $userService;
    private ValidatorInterface $validator;

    public function __construct(UserService $userService, ValidatorInterface $validator)
    {
        $this->userService = $userService;
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
            $user = $this->userService->authenticate($data['email'], $data['password']);
            
            // Generate JWT token
            $token = $this->userService->generateToken($user, ['login' => true]);

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
            $user = $this->userService->createUser($data);
            
            // Generate token
            // $token = $this->userService->generateToken($user, ['register' => true]);

            return new JsonResponse([
                'message' => 'Registration successful',
                // 'token' => $token,
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

    // GET ALL USERS - GET /api/users
    #[Route('', methods: ['GET'])]
    public function getAllUsers(): JsonResponse
    {
        try {
            $users = $this->userService->getAllUsers();
            
            $userList = [];
            foreach ($users as $user) {
                $userList[] = [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole(),
                    'status' => $user->getStatus(),
                    'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
                    'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s')
                ];
            }

            return new JsonResponse([
                'users' => $userList,
                'total' => count($userList)
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to get users'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // GET SINGLE USER - GET /api/users/{id}
    #[Route('/{id}', methods: ['GET'])]
    public function getSingleUser(int $id): JsonResponse
    {
        try {
            $user = $this->userService->getUserById($id);
            
            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            return new JsonResponse([
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole(),
                    'status' => $user->getStatus(),
                    'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
                    'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to get user'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // UPDATE USER - PUT /api/users/{id}
    #[Route('/{id}', methods: ['PUT'])]
    public function updateUser(int $id, Request $request): JsonResponse
    {
        try {
            $user = $this->userService->getUserById($id);
            
            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            $data = json_decode($request->getContent(), true);
            
            if (!$data) {
                return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
            }

            // Update user fields
            if (isset($data['name'])) {
                $user->setName($data['name']);
            }
            
            if (isset($data['email'])) {
                $user->setEmail($data['email']);
            }
            
            if (isset($data['role'])) {
                $user->setRole($data['role']);
            }
            
            if (isset($data['status'])) {
                $user->setStatus($data['status']);
            }

            // Save updated user
            $this->userService->saveUser($user);

            return new JsonResponse([
                'message' => 'User updated successfully',
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRole(),
                    'status' => $user->getStatus(),
                    'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to update user'], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $this->userService->revokeToken($data['token']);

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
            $this->userService->generateToken($user, ['profile_updated' => true]);

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

            $user = $this->userService->validateToken($data['token']);
            
            if ($user && !$this->userService->isTokenExpired($data['token'])) {
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

            $newToken = $this->userService->refreshToken($data['token']);
            
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

        return $this->userService->validateToken($token);
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
