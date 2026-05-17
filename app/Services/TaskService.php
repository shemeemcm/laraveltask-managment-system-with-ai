<?php

namespace App\Services;

use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    protected $taskRepository;
    protected $aiService;

    public function __construct(TaskRepositoryInterface $taskRepository, AIService $aiService)
    {
        $this->taskRepository = $taskRepository;
        $this->aiService = $aiService;
    }

    public function getAllTasks(array $filters = []): Collection
    {
        if (Auth::user()->isAdmin()) {
            return $this->taskRepository->all($filters);
        }
        return $this->taskRepository->getByUserId(Auth::id(), $filters);
    }

    public function createTask(array $data): Task
    {
        $task = $this->taskRepository->create($data);

        // Generate AI Summary and Priority directly
        $analysis = $this->aiService->generateSummary($task);
        
        $this->taskRepository->update($task->id, $analysis);

        // Refresh task to include new AI fields
        return $this->taskRepository->find($task->id);
    }

    public function updateTask(int $id, array $data): bool
    {
        $updated = $this->taskRepository->update($id, $data);

        if ($updated && (isset($data['title']) || isset($data['description']))) {
            $task = $this->taskRepository->find($id);
            
            // Re-generate AI summary if core fields changed
            $analysis = $this->aiService->generateSummary($task);
            $this->taskRepository->update($id, $analysis);
        }

        return $updated;
    }

    public function deleteTask(int $id): bool
    {
        return $this->taskRepository->delete($id);
    }

    public function getTask(int $id): ?Task
    {
        return $this->taskRepository->find($id);
    }

    public function getDashboardStats(): array
    {
        return $this->taskRepository->getStats();
    }
}
