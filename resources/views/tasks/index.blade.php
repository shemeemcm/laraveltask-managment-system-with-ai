<x-app-layout>
    <!-- Add Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="custom-dashboard-wrapper">
        <div class="main-container">
            
            <!-- Content Area -->
            <div class="content-area">
                <!-- Header -->
                <div class="page-header-flex">
                    <h1 class="page-title">Task List</h1>
                    <a href="{{ route('tasks.create') }}" class="btn-new-task">
                        <span class="btn-new-task-icon">+</span> New Task
                    </a>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('tasks.index') }}" class="filters-grid">
                    <div class="filter-input-wrapper">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Filter Task" class="filter-input filter-input-icon-padding" onkeypress="if(event.key === 'Enter') this.form.submit()">
                    </div>
                    <select name="status" class="filter-input" onchange="this.form.submit()">
                        <option value="">Status</option>
                        @foreach(App\Enums\Status::cases() as $status)
                            <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    <select name="assigned_to" class="filter-input" onchange="this.form.submit()">
                        <option value="">All Assignees</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <select name="priority" class="filter-input" onchange="this.form.submit()">
                        <option value="">Priority</option>
                        @foreach(App\Enums\Priority::cases() as $priority)
                            <option value="{{ $priority->value }}" {{ request('priority') === $priority->value ? 'selected' : '' }}>{{ $priority->label() }}</option>
                        @endforeach
                    </select>
                </form>

                <p class="filter-label">Filter User Tasks</p>

                <!-- Cards Grid -->
                <div class="task-cards-grid">
                    @forelse($tasks as $task)
                        <div class="task-card">
                            <div class="task-card-header">
                                <div class="task-status-wrapper">
                                    <span class="task-status-icon">🔵</span> {{ $task->status->label() }}
                                </div>
                                <span class="task-options-icon">•••</span>
                            </div>

                            <h3 class="task-title">{{ $task->title }}</h3>

                            <div class="task-badges-wrapper">
                                <span class="badge badge-status">Status</span>
                                <span class="badge badge-priority">Priority: {{ $task->priority->label() }}</span>
                            </div>

                            <div class="task-description-wrapper">
                                <p class="task-description-text">{{ $task->description ?: 'No description provided.' }}</p>
                            </div>

                            <div class="task-details-wrapper">
                                <div class="task-detail-row">
                                    <span>Assigned to: <b class="task-detail-value">{{ $task->user->name }}</b></span>
                                </div>
                                <div class="task-detail-row">
                                    <span>Due Date: <b class="task-detail-value">{{ $task->due_date ? $task->due_date->format('Y-m-d') : 'N/A' }}</b></span>
                                </div>
                            </div>

                            <div class="task-footer">
                                <a href="{{ route('tasks.edit', $task) }}" class="btn-edit-task">Edit</a>
                                <a href="{{ route('tasks.show', $task) }}" class="btn-view-task">View</a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-wrapper">
                            <p class="empty-state-text">No tasks found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Sidebar -->
            @include('tasks.partials.sidebar')
        </div>
    </div>
</x-app-layout>
