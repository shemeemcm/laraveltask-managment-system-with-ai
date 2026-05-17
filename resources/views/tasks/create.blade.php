<x-app-layout>
    <!-- Add Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="custom-dashboard-wrapper">
        <div class="main-container">
            
            <!-- Content Area -->
            <div class="content-area">
                <!-- Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
                    <h1 style="font-size: 2.75rem; font-weight: 900; letter-spacing: -0.02em;">Create New Task</h1>
                    <a href="{{ route('tasks.index') }}" class="btn-new-task" style="background: #64748b;">
                        <span style="margin-right: 0.5rem; font-size: 1.25rem;">←</span> Back to List
                    </a>
                </div>

                <!-- Filters Placeholder -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 3rem;">
                    <div style="position: relative;">
                        <input type="text" placeholder="Search Filter Task" class="filter-input" style="padding-left: 2.5rem;">
                    </div>
                    <select class="filter-input">
                        <option>Status</option>
                    </select>
                    <select class="filter-input">
                        <option>All Assignees</option>
                    </select>
                    <select class="filter-input">
                        <option>Priority</option>
                    </select>
                </div>

                <p style="color: #94a3b8; font-size: 0.875rem; font-weight: 600; margin-bottom: 1.5rem;">Add Task To Workspace</p>

                <!-- Create Task Card -->
                <div class="detail-card">
                    <div style="display: flex; justify-content: flex-end; margin-bottom: -1rem;">
                        <span style="color: #cbd5e1; font-size: 1.75rem; cursor: pointer;">•••</span>
                    </div>

                    <h2 style="font-size: 2.25rem; font-weight: 900; color: #0f172a; margin-bottom: 3rem;">New Task Details</h2>

                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf

                        <!-- Title Input -->
                        <div class="form-group">
                            <label class="form-label">Task Title</label>
                            <div class="form-input-box">
                                <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Launch New Campaign" required autofocus>
                                <span style="font-size: 1.25rem;">👤</span>
                            </div>
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description Textarea -->
                        <div class="form-group">
                            <label class="form-label">Task Description</label>
                            <div class="form-input-box" style="align-items: flex-start;">
                                <textarea name="description" rows="5" placeholder="Enter task details...">{{ old('description') }}</textarea>
                            </div>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Priority Selection -->
                        <div class="form-group">
                            <label class="form-label">Priority</label>
                            <div class="priority-selector">
                                @foreach(App\Enums\Priority::cases() as $priority)
                                    <label class="priority-btn {{ old('priority', 'medium') == $priority->value ? 'priority-btn-active' : 'priority-btn-inactive' }}">
                                        <input type="radio" name="priority" value="{{ $priority->value }}" class="hidden" {{ old('priority', 'medium') == $priority->value ? 'checked' : '' }} onchange="this.parentElement.parentElement.querySelectorAll('label').forEach(l => l.classList.replace('priority-btn-active', 'priority-btn-inactive')); this.parentElement.classList.replace('priority-btn-inactive', 'priority-btn-active');">
                                        {{ $priority->label() }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Status Hidden or Default -->
                        <input type="hidden" name="status" value="pending">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <!-- Due Date -->
                            <div class="form-group">
                                <label class="form-label">Due Date</label>
                                <div class="form-input-box">
                                    <input type="date" name="due_date" value="{{ old('due_date') }}">
                                    <span style="color: #94a3b8;">📅</span>
                                </div>
                                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                            </div>

                            <!-- Assign To -->
                            <div class="form-group">
                                <label class="form-label">Assign To</label>
                                <div class="form-input-box">
                                    <select name="assigned_to" style="background: transparent; border: none; width: 100%; font-weight: inherit; color: inherit; outline: none; appearance: none;">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('assigned_to', Auth::id()) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <span style="color: #94a3b8;">📋</span>
                                </div>
                                <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
                            </div>
                        </div>

                        <!-- AI Logic Callout -->
                        <div style="background: #f0f9ff; border: 1px solid #e0f2fe; padding: 1.5rem; border-radius: 1.25rem; margin-top: 1rem; display: flex; align-items: center; gap: 1rem;">
                            <div style="font-size: 1.5rem;">⚡</div>
                            <p style="color: #0369a1; font-size: 0.85rem; font-weight: 600; margin: 0;">Our AI will automatically analyze your description to generate a smart summary and suggest improvements.</p>
                        </div>

                        <button type="submit" class="btn-save-changes" style="margin-top: 3rem;">Create Task</button>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            @include('tasks.partials.sidebar')
        </div>
    </div>
</x-app-layout>
