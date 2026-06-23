<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Activity Logs - Taif Project') }}</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        :root {
            --sidebar-bg: #2c5282;
            --page-bg: #f4f7fc;
            --sidebar-text: #e2e8f0;
            --sidebar-active-bg: rgba(255, 255, 255, 0.15);
            --taif-orange: #f6ad55;
            --taif-green: #48bb78;
            --taif-blue: #2c5282;
            --light-border: #edf2f7;
            --muted-text: #718096;
            --dark-text: #2d3748;
            --danger-red: #ef4444;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            display: flex;
            background-color: var(--page-bg);
            font-family: Arial, sans-serif;
        }

        .admin-main-content {
            width: calc(100% - 260px);
            margin-inline-start: 260px;
            padding: 2rem;
        }

        .admin-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.2rem 1.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
        }

        .admin-page-title {
            margin-bottom: 0;
            font-weight: 700;
        }

        .admin-page-subtitle {
            color: #6b7280;
        }

        .admin-status-wrapper {
            display: flex;
            align-items: center;
        }

        .admin-status-text {
            margin-inline-end: 1rem;
            text-align: end;
        }

        .admin-status-title {
            font-size: 13px;
            font-weight: 700;
        }

        .admin-online-status {
            color: #198754;
            font-size: 11px;
        }

        .admin-logout-button {
            width: 38px;
            height: 38px;
            border: none;
            background: white;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .appointment-management-card {
            width: 100%;
            margin-top: 20px;
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .appointment-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .appointment-table-title {
            margin: 0;
            font-weight: 700;
            color: #111827;
        }

        .appointment-filter-search-wrapper {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .appointment-search-wrapper {
            position: relative;
            width: 250px;
        }

        .appointment-search-icon {
            position: absolute;
            inset-inline-start: 12px;
            top: 10px;
            color: #6b7280;
        }

        .appointment-search-input {
            padding-inline-start: 2.5rem;
            background: #f8fafc;
            border: 1px solid var(--light-border);
            border-radius: 6px;
        }

        .appointment-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        .appointment-table th,
        .appointment-table td {
            padding: 15px 20px;
            text-align: start;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .appointment-table th {
            background-color: #f8fafd;
            color: var(--muted-text);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .appointment-status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .status-pending { background: #fef3c7; color: #d97706; } /* للتنبيهات أو الدخول */
        .status-scheduled { background: #dbeafe; color: #2563eb; } /* للتعديل أو التحديث */
        .status-completed { background: #c6f6d5; color: #22543d; } /* للإضافة أو الإنشاء */
        .status-cancelled { background: #fee2e2; color: #dc2626; } /* للحذف */

        .user-info-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar-placeholder {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--taif-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        @media (max-width: 992px) {
            .admin-main-content {
                width: calc(100% - 75px);
                margin-inline-start: 75px;
            }
        }
    </style>
</head>
<body>
    @include('admin.partials.sidebar')

    <div class="admin-main-content text-start">
        <div class="admin-page-header">
            <div>
                <h4 class="admin-page-title">
                    {{ __('System Activity Logs') }}
                </h4>
                <small class="admin-page-subtitle">
                    {{ __('Monitor all system actions and events') }}
                </small>
            </div>

            <div class="admin-status-wrapper">
                <div class="admin-status-text">
                    <div class="admin-status-title">{{ __('Admin Panel') }}</div>
                    <small class="admin-online-status">
                        <i class="fas fa-circle me-1"></i> {{ __('Online') }}
                    </small>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="admin-logout-button">
                        <i class="fas fa-sign-out-alt text-danger"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="appointment-management-card">
            <div class="appointment-table-header">
                <h6 class="appointment-table-title">
                    {{ __('Recent Activities') }}
                </h6>

                <form action="{{ route('admin.activity-logs.index') }}" method="GET" id="searchForm" class="appointment-filter-search-wrapper" style="justify-content: flex-end;">
                    <div class="appointment-search-wrapper">
                        <i class="fas fa-search appointment-search-icon"></i>
                        <input
                            type="text"
                            name="search"
                            id="logSearchInput"
                            value="{{ request('search') }}"
                            class="form-control form-control-sm appointment-search-input"
                            placeholder="{{ __('Search user or action...') }}"
                        >
                    </div>
                </form>
            </div>

            <table class="appointment-table">
                <thead>
                    <tr>
                        <th>{{ __('Date & Time') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Action') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('IP Address') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($logs as $log)
                        @php
                            // تحديد لون البادج بناءً على نوع الحركة
                            $actionType = strtolower($log->action);
                            $badgeClass = 'status-scheduled'; // افتراضي (أزرق)
                            
                            if (str_contains($actionType, 'add') || str_contains($actionType, 'create') || str_contains($actionType, 'إضافة')) {
                                $badgeClass = 'status-completed'; // أخضر
                            } elseif (str_contains($actionType, 'delete') || str_contains($actionType, 'remove') || str_contains($actionType, 'حذف')) {
                                $badgeClass = 'status-cancelled'; // أحمر
                            } elseif (str_contains($actionType, 'login') || str_contains($actionType, 'auth') || str_contains($actionType, 'دخول')) {
                                $badgeClass = 'status-pending'; // أصفر
                            }

                            $userName = $log->user ? $log->user->first_name . ' ' . $log->user->last_name : __('System / Guest');
                            $firstLetter = mb_substr(trim($userName), 0, 1);
                        @endphp

                        <tr>
                            <td>
                                <div style="font-weight: bold; color: var(--dark-text);">{{ $log->created_at->format('M d, Y') }}</div>
                                <div style="font-size: 12px; color: var(--muted-text);">{{ $log->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="user-info-wrapper">
                                    <div class="user-avatar-placeholder">
                                        {{ $log->user ? $firstLetter : 'S' }}
                                    </div>
                                    <span style="font-weight: bold; color: var(--taif-blue);">{{ $userName }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="appointment-status-badge {{ $badgeClass }}">
                                    {{ __($log->action) }}
                                </span>
                            </td>
                            <td style="color: #475569; font-size: 14px; max-width: 300px; line-height: 1.5;">
                                {{ $log->description }}
                            </td>
                            <td style="color: var(--muted-text); font-size: 13px; font-family: monospace;">
                                <i class="fas fa-network-wired me-1"></i> {{ $log->ip_address ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-history" style="font-size: 40px; margin-bottom: 15px; color: #cbd5e1;"></i>
                                <p>{{ __('No activity logs recorded yet.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>

    <script>
        // سكريبت بحث مباشر بدون الحاجة لزر Submit
        let searchTimeout = null;
        const searchForm = document.getElementById('searchForm');
        const logSearchInput = document.getElementById('logSearchInput');

        if (logSearchInput) {
            logSearchInput.addEventListener('keyup', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (searchForm) searchForm.submit();
                }, 600);
            });
        }
    </script>
</body>
</html>