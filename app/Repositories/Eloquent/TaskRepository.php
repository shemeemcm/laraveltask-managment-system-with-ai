<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TaskRepository implements TaskRepositoryInterface
{
    private function applyFilters($query, array $filters)
    {
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }
        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }
        return $query;
    }

    public function all(array $filters = []): Collection
    {
        $query = Task::with('user')->latest();
        return $this->applyFilters($query, $filters)->get();
    }

    public function find(int $id): ?Task
    {
        return Task::with('user')->find($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $task = Task::find($id);
        if (!$task) return false;
        return $task->update($data);
    }

    public function delete(int $id): bool
    {
        $task = Task::find($id);
        if (!$task) return false;
        return $task->delete();
    }

    public function getByUserId(int $userId, array $filters = []): Collection
    {
        $query = Task::where('assigned_to', $userId)->with('user')->latest();
        return $this->applyFilters($query, $filters)->get();
    }

    public function getStats(): array
    {
        // Compute database-agnostic monthly stats
        $tasksThisYear = Task::whereYear('created_at', date('Y'))->get();
        $monthlyTasks = array_fill(1, 12, 0);
        $monthlyCompleted = array_fill(1, 12, 0);
        
        foreach($tasksThisYear as $t) {
            $month = (int)$t->created_at->format('m');
            $monthlyTasks[$month]++;
            if ($t->status->value === 'completed') {
                $monthlyCompleted[$month]++;
            }
        }

        return [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'high_priority' => Task::where('priority', 'high')->count(),
            'by_priority' => Task::select('priority', DB::raw('count(*) as count'))
                ->groupBy('priority')
                ->pluck('count', 'priority')
                ->toArray(),
            'monthly_tasks' => array_values($monthlyTasks),
            'monthly_completed' => array_values($monthlyCompleted),
        ];
    }
}
