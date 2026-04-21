<?php

namespace App\Service;

use App\Entity\Tasks;
use App\Repository\TasksRepository;
use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskService
{
    private TasksRepository $taskRepository;
    private ValidatorInterface $validator;

    public function __construct(
        TasksRepository $taskRepository,
        ValidatorInterface $validator
    ) {
        $this->taskRepository = $taskRepository;
        $this->validator = $validator;
    }

    public function createTask(array $data): Tasks
    {
        // Business validation
        if (empty($data['name'])) {
            throw new \InvalidArgumentException('Name is required');
        }
        
        if (empty($data['email'])) {
            throw new \InvalidArgumentException('Email is required');
        }
        
        if (empty($data['task'])) {
            throw new \InvalidArgumentException('Task description is required');
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        // Validate task description length
        if (strlen($data['task']) < 10) {
            throw new \InvalidArgumentException('Task description must be at least 10 characters');
        }

        $task = new Tasks();
        $task->setName($data['name']);
        $task->setEmail($data['email']);
        $task->setRole($data['role'] ?? 'user');
        $task->setTask($data['task']);
        $task->setCreatedAt(new \DateTime());
        $task->setUpdatedAt(new \DateTime());

        // Symfony validation
        $errors = $this->validator->validate($task);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $this->taskRepository->save($task);
        return $task;
    }

    public function updateTask(int $id, array $data): Tasks
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            throw new \RuntimeException('Task not found');
        }

        // Business logic for updates
        if (isset($data['name'])) {
            if (empty($data['name'])) {
                throw new \InvalidArgumentException('Name cannot be empty');
            }
            if (strlen($data['name']) < 2) {
                throw new \InvalidArgumentException('Name must be at least 2 characters');
            }
            $task->setName($data['name']);
        }

        if (isset($data['email'])) {
            if (empty($data['email'])) {
                throw new \InvalidArgumentException('Email cannot be empty');
            }
            
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('Invalid email format');
            }
            $task->setEmail($data['email']);
        }

        if (isset($data['role'])) {
            if (!in_array($data['role'], ['admin', 'manager', 'user'])) {
                throw new \InvalidArgumentException('Invalid role. Must be admin, manager, or user');
            }
            $task->setRole($data['role']);
        }

        if (isset($data['task'])) {
            if (empty($data['task'])) {
                throw new \InvalidArgumentException('Task description cannot be empty');
            }
            if (strlen($data['task']) < 10) {
                throw new \InvalidArgumentException('Task description must be at least 10 characters');
            }
            $task->setTask($data['task']);
        }

        $task->setUpdatedAt(new \DateTime());

        // Symfony validation
        $errors = $this->validator->validate($task);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $this->taskRepository->save($task);
        return $task;
    }

    public function deleteTask(int $id): void
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            throw new \RuntimeException('Task not found');
        }

        $this->taskRepository->delete($task);
    }

    public function getTaskById(int $id): ?Tasks
    {
        return $this->taskRepository->find($id);
    }

    public function getAllTasks(): array
    {
        return $this->taskRepository->findAll();
    }

    public function getTasksByEmail(string $email): array
    {
        return $this->taskRepository->findBy(['email' => $email]);
    }

    public function formatTask(Tasks $task): array
    {
        return [
            'id' => $task->getId(),
            'name' => $task->getName(),
            'email' => $task->getEmail(),
            'role' => $task->getRole(),
            'task' => $task->getTask(),
            'created_at' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $task->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}

