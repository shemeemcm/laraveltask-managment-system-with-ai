<style>
    :root {
        --bg-dark: #0f172a;
        --card-bg: #ffffff;
        --sidebar-dark: #1e293b;
        --primary-blue: #3b82f6;
        --text-gray: #64748b;
    }

    .custom-dashboard-wrapper {
        background-color: var(--bg-dark);
        min-height: 100vh;
        color: white;
        padding: 2rem;
        font-family: 'Figtree', sans-serif;
    }

    .main-container {
        max-width: 1600px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    @media (min-width: 1024px) {
        .main-container {
            flex-direction: row;
        }
    }

    .content-area {
        flex: 1;
    }

    .sidebar-area {
        width: 100%;
    }

    @media (min-width: 1024px) {
        .sidebar-area {
            width: 400px;
        }
    }

    .sidebar-card {
        background: white;
        border-radius: 2.5rem;
        padding: 2.5rem;
        color: #1e293b;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }

    .dark-sidebar-widget {
        background: var(--sidebar-dark);
        border-radius: 2.5rem;
        padding: 2.5rem;
        margin-top: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .detail-card {
        background: white;
        border-radius: 2.5rem;
        padding: 3.5rem;
        color: #1e293b;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }

    .chart-container {
        position: relative;
        height: 80px;
        width: 80px;
        margin: 0 auto;
    }

    .filter-input {
        background: white;
        color: #1e293b;
        border-radius: 1rem;
        padding: 0.75rem 1rem;
        border: none;
        width: 100%;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .btn-new-task {
        background: var(--primary-blue);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 1rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .badge {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 0.5rem 1.25rem;
        border-radius: 9999px;
        letter-spacing: 0.05em;
    }

    .badge-priority { background: #fee2e2; color: #ef4444; }
    .badge-status { background: #f1f5f9; color: #94a3b8; }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        border-radius: 1.25rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        margin-bottom: 0.5rem;
    }

    .nav-link-active {
        background: var(--primary-blue);
        color: white;
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
    }

    .nav-link-inactive {
        color: #64748b;
    }

    .nav-link-inactive:hover {
        background: #f8fafc;
    }

    /* Form specific styles */
    .form-group {
        margin-bottom: 2rem;
    }

    .form-label {
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.75rem;
        display: block;
    }

    .form-input-box {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        padding: 1.25rem;
        border-radius: 1.25rem;
        width: 100%;
        color: #475569;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .form-input-box input, .form-input-box textarea {
        background: transparent;
        border: none;
        width: 100%;
        color: inherit;
        font-weight: inherit;
        outline: none;
    }

    .priority-selector {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .priority-btn {
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        font-weight: 800;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
        font-size: 0.9rem;
    }

    .priority-btn-inactive {
        background: #f1f5f9;
        color: #94a3b8;
    }

    .priority-btn-active {
        background: var(--primary-blue);
        color: white;
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2);
    }

    .btn-save-changes {
        background: var(--primary-blue);
        color: white;
        padding: 1rem 3rem;
        border-radius: 1.5rem;
        font-weight: 800;
        border: none;
        cursor: pointer;
        font-size: 1rem;
        margin-top: 3rem;
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        display: block;
        margin: 0 auto;
    }
    /* Task Dashboard Extracted Styles */
    .page-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
    }

    .btn-new-task-icon {
        margin-right: 0.5rem;
        font-size: 1.5rem;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .filter-input-wrapper {
        position: relative;
    }

    .filter-input-icon-padding {
        padding-left: 2.5rem;
    }

    .filter-label {
        color: #94a3b8;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .task-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .task-card {
        background-color: white;
        border-radius: 17px;
    }

    .task-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .task-status-wrapper {
        display: flex;
        align-items: center;
        color: var(--primary-blue);
        font-weight: 700;
        font-size: 0.875rem;
    }

    .task-status-icon {
        margin-right: 0.5rem;
    }

    .task-options-icon {
        color: #cbd5e1;
        font-size: 1.5rem;
        cursor: pointer;
    }

    .task-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        margin-left: 1rem;
        color: #0f172a;
    }

    .task-badges-wrapper {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        margin-left: 1rem;
    }

    .task-description-wrapper {
        background: #f8fafc;
        padding: 1.25rem;
        border-radius: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .task-description-text {
        color: #64748b;
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .task-details-wrapper {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-bottom: 2rem;
        margin-left: 1rem;
    }

    .task-detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .task-detail-row:last-child {
        margin-bottom: 0;
    }

    .task-detail-value {
        color: #334155;
    }

    .task-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        border-top: 1px solid #f1f5f9;
        padding-top: 1.5rem;
        margin-bottom: 1rem;
        margin-right: 1rem;
    }

    .btn-edit-task {
        text-decoration: none;
        color: #475569;
        background: #f1f5f9;
        padding: 0.6rem 1.5rem;
        border-radius: 0.75rem;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .btn-view-task {
        text-decoration: none;
        color: white;
        background: var(--primary-blue);
        padding: 0.6rem 1.5rem;
        border-radius: 0.75rem;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .empty-state-wrapper {
        grid-column: span 2;
        text-align: center;
        padding: 5rem;
        border: 2px dashed #334155;
        border-radius: 2rem;
    }

    .empty-state-text {
        color: #64748b;
        font-weight: 700;
    }
</style>
