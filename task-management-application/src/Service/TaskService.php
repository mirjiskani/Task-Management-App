<?php

namespace App\Service;

use App\Entity\Tasks;
use App\Entity\Users;
use App\Repository\TasksRepository;
use App\Repository\UsersRepository;
use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskService
{
    private TasksRepository $taskRepository;
    private UsersRepository $usersRepository;
    private ValidatorInterface $validator;

    public function __construct(
        TasksRepository $taskRepository,
        UsersRepository $usersRepository,
        ValidatorInterface $validator
    ) {
        $this->taskRepository = $taskRepository;
        $this->usersRepository = $usersRepository;
        $this->validator = $validator;
    }

    public function createTask(array $data): Tasks
    {
        // Validate user_id is provided
        if (empty($data['user_id'])) {
            throw new \InvalidArgumentException('user_id is required');
        }

        // Get the user
        $user = $this->usersRepository->find($data['user_id']);
        if (!$user) {
            throw new \InvalidArgumentException('User not found with id: ' . $data['user_id']);
        }

        // Business validation
        if (empty($data['task'])) {
            throw new \InvalidArgumentException('Task description is required');
        }

        // Validate task description length
        if (strlen($data['task']) < 10) {
            throw new \InvalidArgumentException('Task description must be at least 10 characters');
        }

        $task = new Tasks();
        $task->setTask($data['task']);
        $task->setUser($user);
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

        // Update user if user_id is provided
        if (isset($data['user_id'])) {
            $user = $this->usersRepository->find($data['user_id']);
            if (!$user) {
                throw new \InvalidArgumentException('User not found with id: ' . $data['user_id']);
            }
            $task->setUser($user);
        }

        // Update task description if provided
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
            'task' => $task->getTask(),
            'user_id' => $task->getUser()?->getId(),
            'user' => $task->getUser() ? [
                'id' => $task->getUser()->getId(),
                'name' => $task->getUser()->getName(),
                'email' => $task->getUser()->getEmail(),
                'role' => $task->getUser()->getRole()
            ] : null,
            'created_at' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $task->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}

