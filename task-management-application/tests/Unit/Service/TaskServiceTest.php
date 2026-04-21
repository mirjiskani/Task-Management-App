<?php

namespace App\Tests\Unit\Service;

use App\Entity\Tasks;
use App\Repository\TasksRepository;
use App\Exception\ValidationException;
use App\Service\TaskService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class TaskServiceTest extends TestCase
{
    private $taskRepository;
    private $validator;
    private $taskService;

    protected function setUp(): void
    {
        $this->taskRepository = $this->createMock(TasksRepository::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->taskService = new TaskService($this->taskRepository, $this->validator);
    }

    public function testCreateTaskWithValidData(): void
    {
        // Arrange
        $taskData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'user',
            'task' => 'Complete the project documentation and ensure all API endpoints are working correctly.'
        ];

        $task = new Tasks();
        
        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $this->taskRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function($task) use ($taskData) {
                return $task instanceof Tasks 
                    && $task->getName() === $taskData['name']
                    && $task->getEmail() === $taskData['email'];
            }));

        // Act
        $result = $this->taskService->createTask($taskData);

        // Assert
        $this->assertInstanceOf(Tasks::class, $result);
        $this->assertEquals('John Doe', $result->getName());
        $this->assertEquals('john@example.com', $result->getEmail());
    }

    public function testCreateTaskWithShortDescription(): void
    {
        // Arrange
        $taskData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'user',
            'task' => 'Short'
        ];

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Task description must be at least 10 characters');

        $this->taskService->createTask($taskData);
    }

    public function testCreateTaskWithInvalidEmail(): void
    {
        // Arrange
        $taskData = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'role' => 'user',
            'task' => 'Complete the project documentation and ensure all API endpoints are working correctly.'
        ];

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format');

        $this->taskService->createTask($taskData);
    }

    public function testCreateTaskWithMissingName(): void
    {
        // Arrange
        $taskData = [
            'email' => 'john@example.com',
            'role' => 'user',
            'task' => 'Complete the project documentation and ensure all API endpoints are working correctly.'
        ];

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Name is required');

        $this->taskService->createTask($taskData);
    }

    public function testUpdateTaskNotFound(): void
    {
        // Arrange
        $this->taskRepository->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        // Act & Assert
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Task not found');

        $this->taskService->updateTask(999, ['name' => 'Updated Name']);
    }

    public function testDeleteTaskNotFound(): void
    {
        // Arrange
        $this->taskRepository->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        // Act & Assert
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Task not found');

        $this->taskService->deleteTask(999);
    }

    public function testFormatTask(): void
    {
        // Arrange
        $task = new Tasks();
        $task->setName('John Doe');
        $task->setEmail('john@example.com');
        $task->setRole('user');
        $task->setTask('Complete the project documentation');
        $task->setCreatedAt(new \DateTime('2024-01-01 10:00:00'));
        $task->setUpdatedAt(new \DateTime('2024-01-01 11:00:00'));

        // Act
        $result = $this->taskService->formatTask($task);

        // Assert
        $this->assertEquals([
            'id' => null, // ID will be null until persisted
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'user',
            'task' => 'Complete the project documentation',
            'created_at' => '2024-01-01 10:00:00',
            'updated_at' => '2024-01-01 11:00:00'
        ], $result);
    }

    public function testUpdateTaskWithInvalidRole(): void
    {
        // Arrange
        $task = new Tasks();
        
        $this->taskRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($task);

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid role. Must be admin, manager, or user');

        $this->taskService->updateTask(1, ['role' => 'invalid_role']);
    }
}
