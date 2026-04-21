<?php

namespace App\Service;

use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private UsersRepository $userRepository;
    private ValidatorInterface $validator;

    public function __construct(
        UsersRepository $userRepository,
        ValidatorInterface $validator
    ) {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function createUser(array $data): Users
    {
        // Business validation
        if (empty($data['name'])) {
            throw new \InvalidArgumentException('Name is required');
        }
        
        if (empty($data['email'])) {
            throw new \InvalidArgumentException('Email is required');
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }
        
        if (empty($data['password'])) {
            throw new \InvalidArgumentException('Password is required');
        }

        // Check if email already exists
        $existingUser = $this->userRepository->findByEmail($data['email']);
        if ($existingUser) {
            throw new \RuntimeException('Email already exists');
        }

        $user = new Users();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        $user->setRole($data['role'] ?? 'user');
        $user->setStatus($data['status'] ?? 1);
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());

        // Symfony validation
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $this->userRepository->save($user);
        return $user;
    }

    public function updateUser(int $id, array $data): Users
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new \RuntimeException('User not found');
        }

        // Business logic for updates
        if (isset($data['name'])) {
            if (empty($data['name'])) {
                throw new \InvalidArgumentException('Name cannot be empty');
            }
            $user->setName($data['name']);
        }

        if (isset($data['email'])) {
            if (empty($data['email'])) {
                throw new \InvalidArgumentException('Email cannot be empty');
            }
            
            if ($data['email'] !== $user->getEmail()) {
                $existingUser = $this->userRepository->findByEmail($data['email']);
                if ($existingUser && $existingUser->getId() !== $id) {
                    throw new \RuntimeException('Email already exists');
                }
                $user->setEmail($data['email']);
            }
        }

        if (isset($data['password'])) {
            if (empty($data['password'])) {
                throw new \InvalidArgumentException('Password cannot be empty');
            }
            if (strlen($data['password']) < 6) {
                throw new \InvalidArgumentException('Password must be at least 6 characters');
            }
            $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        }

        if (isset($data['role'])) {
            if (!in_array($data['role'], ['admin', 'manager', 'user'])) {
                throw new \InvalidArgumentException('Invalid role. Must be admin, manager, or user');
            }
            $user->setRole($data['role']);
        }

        if (isset($data['status'])) {
            if (!is_int($data['status']) || $data['status'] < 0 || $data['status'] > 1) {
                throw new \InvalidArgumentException('Status must be 0 (inactive) or 1 (active)');
            }
            $user->setStatus($data['status']);
        }

        $user->setUpdatedAt(new \DateTime());

        // Symfony validation
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $this->userRepository->save($user);
        return $user;
    }

    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new \RuntimeException('User not found');
        }

        $this->userRepository->delete($user);
    }

    public function getUserById(int $id): ?Users
    {
        return $this->userRepository->find($id);
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function formatUser(Users $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'status' => $user->getStatus(),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $user->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}

