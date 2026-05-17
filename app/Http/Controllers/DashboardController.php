<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(): View
    {
        $stats = $this->taskService->getDashboardStats();
        return view('dashboard', compact('stats'));
    }
}
