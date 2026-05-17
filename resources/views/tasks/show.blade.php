<x-app-layout>
    <!-- Add Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="custom-dashboard-wrapper">
        <div class="main-container">
            
            <!-- Content Area -->
            <div class="content-area">
                <!-- Header -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
                    <h1 style="font-size: 2.75rem; font-weight: 900; letter-spacing: -0.02em;">Task Detail + AI Summary</h1>
                    <a href="{{ route('tasks.create') }}" class="btn-new-task">
                        <span style="margin-right: 0.5rem; font-size: 1.5rem;">+</span> New Task
                    </a>
                </div>

                <!-- Filters Placeholder (Matching index) -->
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

                <p style="color: #94a3b8; font-size: 0.875rem; font-weight: 600; margin-bottom: 1.5rem;">Filter User Task</p>

                <!-- Task Detail Card -->
                <div class="detail-card">
                    <div style="display: flex; justify-content: flex-end; margin-bottom: -1rem;">
                        <span style="color: #cbd5e1; font-size: 1.75rem; cursor: pointer;">•••</span>
                    </div>

                    <h2 style="font-size: 2.25rem; font-weight: 900; color: #0f172a; margin-bottom: 1.5rem;">{{ $task->title }}</h2>

                    <div style="display: flex; align-items: center; margin-bottom: 3rem;">
                        <span class="badge badge-status">Status</span>
                        <span style="margin-left: 0.75rem; font-weight: 700; color: #64748b; font-size: 0.9rem;">{{ $task->status->label() }}</span>
                        
                        <span class="badge badge-priority" style="margin-left: 2rem;">Priority</span>
                        <span style="margin-left: 0.75rem; font-weight: 700; color: #64748b; font-size: 0.9rem;">{{ $task->priority->label() }}</span>
                    </div>

                    <!-- Description Section -->
                    <div style="background: #f8fafc; border-radius: 1.5rem; padding: 2.5rem; border: 1px solid #f1f5f9;">
                        <h4 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; margin-bottom: 1.5rem;">Description</h4>
                        
                        <div class="info-row">
                            <span class="info-label">Assigned to:</span>
                            <span class="info-value">{{ $task->user->name }}</span>
                        </div>

                        <div class="due-date-box">
                            <span style="color: #94a3b8; font-weight: 600;">Due Date: {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'N/A' }}</span>
                            <span style="color: #94a3b8;">📅</span>
                        </div>

                        <p style="margin-top: 2rem; color: #64748b; line-height: 1.8; font-size: 0.95rem;">
                            {{ $task->description ?: 'No detailed description provided.' }}
                        </p>

                        <!-- AI Generated Summary -->
                        <div class="ai-section">
                            <h5 style="font-size: 1rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem;">AI-Generated Summary</h5>
                            <p style="color: #64748b; font-size: 0.9rem; line-height: 1.7; font-style: italic;">
                                "{{ $task->ai_summary ?: 'The AI summary is being generated or was not requested for this task.' }}"
                            </p>
                        </div>

                        <!-- Bottom AI Priority Row -->
                        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #edf2f7; font-size: 0.9rem;">
                            <span style="font-weight: 800; color: #1e293b;">AI Summary:</span>
                            <span style="color: #64748b; margin-left: 0.5rem;">{{ Str::limit($task->ai_summary, 80) }}</span>
                            <span style="font-weight: 800; color: #1e293b; margin-left: 1.5rem;">Priority:</span>
                            <span style="color: #64748b; margin-left: 0.5rem;">{{ $task->ai_priority ?: $task->priority->label() }}</span>
                        </div>
                    </div>

                    <a href="{{ route('tasks.edit', $task) }}" style="text-decoration: none;">
                        <button class="btn-save">Save Changes</button>
                    </a>
                </div>
            </div>

            <!-- Sidebar -->
            @include('tasks.partials.sidebar', [
                'sidebarExtra' => '
                    <button style="width: 100%; margin-top: 1.5rem; background: white; border: 2px solid var(--primary-blue); color: var(--primary-blue); padding: 1rem; border-radius: 1.25rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                        Refresh AI Summary <span style="font-size: 1.25rem;">🔄</span>
                    </button>'
            ])
        </div>
    </div>
</x-app-layout>
