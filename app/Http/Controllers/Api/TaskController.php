<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * GET /api/tasks
     */
    public function index(): AnonymousResourceCollection
    {
        return TaskResource::collection($this->taskService->getAllTasks());
    }

    /**
     * POST /api/tasks
     */
    public function store(StoreTaskRequest $request): TaskResource
    {
        $task = $this->taskService->createTask($request->validated());
        return new TaskResource($task);
    }

    /**
     * PATCH /api/tasks/{id}/status
     */
    public function updateStatus(Request $request, Task $task): TaskResource
    {
        Gate::authorize('update', $task);

        $request->validate([
            'status' => ['required', 'string', 'in:pending,in_progress,completed'],
        ]);

        $this->taskService->updateTask($task->id, ['status' => $request->status]);
        
        return new TaskResource($task->fresh());
    }

    /**
     * GET /api/tasks/{id}/ai-summary
     */
    public function getAiSummary(Task $task): JsonResponse
    {
        Gate::authorize('view', $task);

        return response()->json([
            'title' => $task->title,
            'ai_summary' => $task->ai_summary,
            'ai_priority' => $task->ai_priority,
        ]);
    }
}
