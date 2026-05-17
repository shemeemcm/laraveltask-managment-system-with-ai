<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use App\Enums\UserRole;
use App\Enums\Priority;
use App\Enums\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
        ]);

        // Regular User
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::USER,
        ]);

        // Create some tasks
        Task::create([
            'title' => 'Complete Project Proposal',
            'description' => 'Draft the initial proposal for the new task management system.',
            'priority' => Priority::HIGH,
            'status' => Status::IN_PROGRESS,
            'due_date' => now()->addDays(7),
            'assigned_to' => $admin->id,
            'ai_summary' => 'AI Summary: High priority task involving documentation and strategic planning.',
            'ai_priority' => 'high',
        ]);

        Task::create([
            'title' => 'Update Tailwind Config',
            'description' => 'Optimize tailwind configuration for production build.',
            'priority' => Priority::MEDIUM,
            'status' => Status::PENDING,
            'due_date' => now()->addDays(3),
            'assigned_to' => $user->id,
            'ai_summary' => 'AI Summary: Technical maintenance task to improve performance.',
            'ai_priority' => 'medium',
        ]);
    }
}
