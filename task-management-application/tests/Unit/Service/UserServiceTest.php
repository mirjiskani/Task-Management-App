<?php

namespace App\Tests\Unit\Service;

use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Service\UserService;
use App\Exception\ValidationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class UserServiceTest extends TestCase
{
    private $userRepository;
    private $validator;
    private $userService;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UsersRepository::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->userService = new UserService($this->userRepository, $this->validator);
    }

    public function testCreateUserWithValidData(): void
    {
        // Arrange
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'user',
            'status' => 1
        ];

        $user = new Users();
        
        $this->userRepository->expects($this->once())
            ->method('findByEmail')
            ->with('john@example.com')
            ->willReturn(null);

        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function($user) use ($userData) {
                return $user instanceof Users 
                    && $user->getName() === $userData['name']
                    && $user->getEmail() === $userData['email'];
            }));

        // Act
        $result = $this->userService->createUser($userData);

        // Assert
        $this->assertInstanceOf(Users::class, $result);
        $this->assertEquals('John Doe', $result->getName());
        $this->assertEquals('john@example.com', $result->getEmail());
    }

    public function testCreateUserWithDuplicateEmail(): void
    {
        // Arrange
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'user'
        ];

        $existingUser = new Users();
        $existingUser->setEmail('john@example.com');

        $this->userRepository->expects($this->once())
            ->method('findByEmail')
            ->with('john@example.com')
            ->willReturn($existingUser);

        // Act & Assert
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Email already exists');

        $this->userService->createUser($userData);
    }

    public function testCreateUserWithMissingName(): void
    {
        // Arrange
        $userData = [
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'user'
        ];

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Name is required');

        $this->userService->createUser($userData);
    }

    public function testCreateUserWithInvalidEmail(): void
    {
        // Arrange
        $userData = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'role' => 'user'
        ];

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format');

        $this->userService->createUser($userData);
    }

    public function testUpdateUserNotFound(): void
    {
        // Arrange
        $this->userRepository->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        // Act & Assert
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('User not found');

        $this->userService->updateUser(999, ['name' => 'Updated Name']);
    }

    public function testDeleteUserNotFound(): void
    {
        // Arrange
        $this->userRepository->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        // Act & Assert
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('User not found');

        $this->userService->deleteUser(999);
    }

    public function testFormatUser(): void
    {
        // Arrange
        $user = new Users();
        $user->setName('John Doe');
        $user->setEmail('john@example.com');
        $user->setRole('user');
        $user->setStatus(1);
        $user->setCreatedAt(new \DateTime('2024-01-01 10:00:00'));
        $user->setUpdatedAt(new \DateTime('2024-01-01 11:00:00'));

        // Act
        $result = $this->userService->formatUser($user);

        // Assert
        $this->assertEquals([
            'id' => null, // ID will be null until persisted
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'user',
            'status' => 1,
            'created_at' => '2024-01-01 10:00:00',
            'updated_at' => '2024-01-01 11:00:00'
        ], $result);
    }
}
