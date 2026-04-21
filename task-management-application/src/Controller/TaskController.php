<?php

namespace App\Controller;

use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tasks')]
class TaskControllerRefactored extends AbstractController
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    // CREATE TASK - POST /api/tasks
    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (!$data) {
                return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
            }

            $task = $this->taskService->createTask($data);

            return new JsonResponse([
                'message' => 'Task created successfully',
                'task' => $this->taskService->formatTask($task)
            ], Response::HTTP_CREATED);

        } catch (\App\Exception\ValidationException $e) {
            return new JsonResponse(['errors' => $e->getErrors()], Response::HTTP_BAD_REQUEST);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_CONFLICT);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // UPDATE TASK - PUT /api/tasks/{id}
    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (!$data) {
                return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
            }

            $task = $this->taskService->updateTask($id, $data);

            return new JsonResponse([
                'message' => 'Task updated successfully',
                'task' => $this->taskService->formatTask($task)
            ]);

        } catch (\App\Exception\ValidationException $e) {
            return new JsonResponse(['errors' => $e->getErrors()], Response::HTTP_BAD_REQUEST);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // DELETE TASK - DELETE /api/tasks/{id}
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->taskService->deleteTask($id);

            return new JsonResponse([
                'message' => 'Task deleted successfully',
                'task_id' => $id
            ]);

        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // GET TASK - GET /api/tasks/{id}
    #[Route('/{id}', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->getTaskById($id);
            if (!$task) {
                throw new \RuntimeException('Task not found');
            }

            return new JsonResponse([
                'task' => $this->taskService->formatTask($task)
            ]);

        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // GET ALL TASKS - GET /api/tasks
    #[Route('', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        try {
            $tasks = $this->taskService->getAllTasks();
            
            $tasksData = array_map([$this->taskService, 'formatTask'], $tasks);

            return new JsonResponse([
                'tasks' => $tasksData,
                'total' => count($tasksData)
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
