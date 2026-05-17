<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(\Illuminate\Http\Request $request): View
    {
        $filters = $request->only(['search', 'status', 'assigned_to', 'priority']);
        $tasks = $this->taskService->getAllTasks($filters);
        $stats = $this->taskService->getDashboardStats();
        $users = User::all();
        return view('tasks.index', compact('tasks', 'stats', 'users'));
    }

    public function create(): View
    {
        $users = User::all();
        $stats = $this->taskService->getDashboardStats();
        return view('tasks.create', compact('users', 'stats'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->taskService->createTask($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task): View
    {
        Gate::authorize('view', $task);
        $stats = $this->taskService->getDashboardStats();
        $users = User::all();
        return view('tasks.show', compact('task', 'stats', 'users'));
    }

    public function edit(Task $task): View
    {
        Gate::authorize('update', $task);
        $stats = $this->taskService->getDashboardStats();
        $users = User::all();
        return view('tasks.edit', compact('task', 'stats', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);
        $this->taskService->updateTask($task->id, $request->validated());
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);
        $this->taskService->deleteTask($task->id);
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
