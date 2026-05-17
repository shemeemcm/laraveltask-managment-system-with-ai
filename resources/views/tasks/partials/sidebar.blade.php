<!-- Sidebar Area -->
<div class="sidebar-area">
    <div class="sidebar-card">
        <!-- User Profile -->
        <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2.5rem;">
            <div style="width: 5rem; height: 5rem; background: var(--primary-blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 900; box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.5);">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div>
                <h4 style="font-size: 1.5rem; font-weight: 900; color: #0f172a; margin: 0;">{{ Auth::user()->name }}</h4>
                <p style="font-size: 0.75rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; margin: 0.25rem 0 0 0;">{{ Auth::user()->role->value }}</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav style="margin-bottom: 3rem;">
            <a href="{{ route('tasks.index') }}" class="nav-link {{ Route::is('tasks.index') ? 'nav-link-active' : 'nav-link-inactive' }}">
                <span style="margin-right: 1rem; font-size: 1.25rem;"></span> Tasks
            </a>
            <a href="#" class="nav-link nav-link-inactive">
                <span style="margin-right: 1rem; font-size: 1.25rem;"></span> Users <span style="margin-left: 0.5rem; font-size: 10px; opacity: 0.5;">(Admin only)</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="width: 100%; border: none; background: none; cursor: pointer;" class="nav-link nav-link-inactive">
                    <span style="margin-right: 1rem; font-size: 1.25rem; color: #ef4444;"></span> <span style="color: #ef4444;">Logout</span>
                </button>
            </form>
        </nav>

        <!-- Stats Section -->
        <div style="border-top: 1px solid #f1f5f9; padding-top: 2rem;">
            <h5 style="font-size: 0.75rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.2em; text-align: center; margin-bottom: 2rem;">Task Statistics</h5>
            
            <div style="display: flex; justify-content: space-between; text-align: center; margin-bottom: 2.5rem;">
                <div>
                    <div class="chart-container">
                        <canvas id="doneChart"></canvas>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: 800; font-size: 1.25rem; color: #0f172a;">{{ $stats['completed'] ?? 0 }}</div>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; display: block; margin-top: 0.5rem;">Done</span>
                </div>
                <div>
                    <div class="chart-container">
                        <canvas id="pendingChart"></canvas>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: 800; font-size: 1.25rem; color: #0f172a;">{{ $stats['pending'] ?? 0 }}</div>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; display: block; margin-top: 0.5rem;">Pending</span>
                </div>
                <div>
                    <div class="chart-container">
                        <canvas id="highChart"></canvas>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: 800; font-size: 1.25rem; color: #0f172a;">{{ $stats['in_progress'] ?? 0 }}</div>
                    </div>
                    <span style="font-size: 0.7rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; display: block; margin-top: 0.5rem;">In Progress</span>
                </div>
            </div>

            <div style="height: 150px; width: 100%;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Optional Sidebar Extension (e.g. Refresh AI Summary) -->
    @isset($sidebarExtra)
        {!! $sidebarExtra !!}
    @endisset

    <!-- Bottom Widget -->
    <div class="dark-sidebar-widget">
        <h5 style="font-size: 0.75rem; font-weight: 900; color: #475569; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 1.5rem;">Monthly Task Completion</h5>
        <div style="height: 180px; width: 100%;">
            <canvas id="momentumChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js Logic (Shared) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: { legend: { display: false }, tooltip: { enabled: false } }
        };

        const dataTotal = {{ $stats['total'] }};
        const dataCompleted = {{ $stats['completed'] }};
        const dataPending = {{ $stats['pending'] }};
        const dataInProgress = {{ $stats['in_progress'] }};

        new Chart(document.getElementById('doneChart'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [dataCompleted, Math.max(0.1, dataTotal - dataCompleted)],
                    backgroundColor: ['#10b981', '#f1f5f9'], // Green for completed
                    borderWidth: 0
                }]
            },
            options: chartOptions
        });

        new Chart(document.getElementById('pendingChart'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [dataPending, Math.max(0.1, dataTotal - dataPending)],
                    backgroundColor: ['#64748b', '#f1f5f9'], // Gray for pending
                    borderWidth: 0
                }]
            },
            options: chartOptions
        });

        new Chart(document.getElementById('highChart'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [dataInProgress, Math.max(0.1, dataTotal - dataInProgress)],
                    backgroundColor: ['#6366f1', '#f1f5f9'], // Indigo for in progress
                    borderWidth: 0
                }]
            },
            options: chartOptions
        });

        const barOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { display: false },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 10, weight: '900' } }
                }
            },
            plugins: { legend: { display: false } }
        };

        const allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: allMonths,
                datasets: [{
                    data: @json($stats['monthly_tasks']),
                    backgroundColor: '#3b82f6',
                    borderRadius: 5
                }]
            },
            options: barOptions
        });

        new Chart(document.getElementById('momentumChart'), {
            type: 'bar',
            data: {
                labels: allMonths,
                datasets: [{
                    data: @json($stats['monthly_completed']),
                    backgroundColor: '#10b981',
                    borderRadius: 8
                }]
            },
            options: {
                ...barOptions,
                scales: {
                    y: { display: false },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#475569', font: { size: 10, weight: '900' } }
                    }
                }
            }
        });
    });
</script>
