<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Alerts Management - Taif Project') }}</title>

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

        /* 💡 الخصائص المنطقية */
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

        .alert-message-box {
            margin-bottom: 15px;
        }

        .alerts-management-card {
            width: 100%;
            margin-top: 20px;
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .alerts-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .alerts-table-title {
            margin: 0;
            font-weight: 700;
            color: #111827;
        }

        .alerts-filter-search-wrapper {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .alerts-type-filter,
        .alerts-read-filter,
        .alerts-date-filter {
            width: 150px;
            background: #f8fafc;
        }

        .alerts-search-wrapper {
            position: relative;
            width: 250px;
        }

        .alerts-search-icon {
            position: absolute;
            inset-inline-start: 12px;
            top: 10px;
            color: #6b7280;
        }

        .alerts-search-input {
            padding-inline-start: 2.5rem;
            background: #f8fafc;
        }

        .alerts-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        .alerts-table th,
        .alerts-table td {
            padding: 15px 20px;
            text-align: start;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .alerts-table th {
            background-color: #f8fafd;
            color: var(--muted-text);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .alert-title-cell {
            font-weight: 800;
            color: #111827;
        }

        .alert-message-preview {
            max-width: 280px;
            color: #6b7280;
            font-size: 13px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .alert-type-badge,
        .alert-read-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .type-panic { background: #fee2e2; color: #dc2626; }
        .type-heart_rate { background: #fef3c7; color: #d97706; }
        .type-safe_zone { background: #fee2e2; color: #dc2626; }
        .type-activity { background: #fef3c7; color: #d97706; }
        .type-default { background: #e0f2fe; color: #0ea5e9; }

        .read-yes { background: #c6f6d5; color: #22543d; }
        .read-no { background: #e0f2fe; color: #0369a1; }

        .alerts-action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .alerts-action-button {
            height: 32px;
            min-width: 32px;
            padding: 0 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 800;
            transition: 0.2s;
            text-decoration: none;
        }

        .alerts-action-view { background: #e0f2fe; color: #0ea5e9; }
        .alerts-action-read { background: #dcfce7; color: #16a34a; }
        .alerts-action-unread { background: #fef3c7; color: #d97706; }
        .alerts-action-delete { background: #fef3c7; color: #d97706; }

        .alerts-action-button:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #6b7280;
        }

        .empty-state-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 16px;
            border-radius: 50%;
            background: #e0f2fe;
            color: #0ea5e9;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
        }

        .empty-state-title {
            font-size: 20px;
            color: #111827;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .empty-state-text {
            font-size: 14px;
        }

        .admin-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 2000;
            width: 100%;
            height: 100%;
            display: none;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(5px);
        }

        .admin-modal-box {
            position: relative;
            width: 460px;
            max-width: 95%;
            max-height: 92vh;
            overflow-y: auto;
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .admin-modal-header {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            padding: 20px;
            color: white;
            font-size: 18px;
            font-weight: 800;
            text-align: center;
        }

        .view-alert-modal .admin-modal-header { background: var(--taif-green); }
        .delete-alert-modal .admin-modal-header { background: var(--danger-red); }

        .admin-modal-close {
            position: absolute;
            top: 15px;
            inset-inline-end: 15px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 22px;
            cursor: pointer;
        }

        .admin-modal-body { padding: 30px 25px; }

        .view-alert-details-box {
            padding: 15px;
            background: #f8fafc;
            border-radius: 15px;
        }

        .view-alert-info-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .view-alert-info-row:last-child { border: none; }
        .view-alert-info-label { color: var(--muted-text); font-weight: 700; }
        
        .view-alert-info-value {
            color: var(--dark-text);
            font-weight: 600;
            text-align: end;
            max-width: 260px;
            overflow-wrap: break-word;
        }

        .view-alert-message {
            margin-top: 16px;
            padding: 15px;
            border-radius: 12px;
            background: white;
            color: #4b5563;
            line-height: 1.6;
            font-size: 14px;
        }

        .delete-warning-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fee2e2;
            color: #dc2626;
            border-radius: 50%;
            font-size: 30px;
        }

        .delete-title { margin-bottom: 10px; color: #111827; font-size: 22px; font-weight: 800; }
        .delete-message { margin-bottom: 24px; color: #6b7280; font-size: 14px; line-height: 1.6; }
        .delete-actions { display: flex; justify-content: center; gap: 12px; }

        .delete-cancel-button,
        .delete-confirm-button {
            min-width: 110px;
            height: 42px;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
        }

        .delete-cancel-button { background: #e5e7eb; color: #374151; }
        .delete-confirm-button { background: var(--danger-red); color: white; }

        @media (max-width: 992px) {
            .admin-main-content {
                width: calc(100% - 75px);
                margin-inline-start: 75px;
            }
            .alerts-table-header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .alerts-filter-search-wrapper { flex-wrap: wrap; }
        }
    </style>
</head>
<body>
    @include('admin.partials.sidebar')

    <div class="admin-main-content text-start">
        <div class="admin-page-header">
            <div>
                <h4 class="admin-page-title">
                    {{ __('Alerts Management') }}
                </h4>
                <small class="admin-page-subtitle">
                    {{ __('Monitor and manage system alerts') }}
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

        @if(session('success'))
            <div class="alert alert-success alert-message-box">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-message-box">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-message-box">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="alerts-management-card">
            <div class="alerts-table-header">
                <h6 class="alerts-table-title">
                    {{ __('Alerts Directory') }}
                </h6>

                <form action="{{ route('admin.alerts.index') }}" method="GET" id="searchForm" class="alerts-filter-search-wrapper" style="display: flex; gap: 10px; align-items: center; justify-content: flex-end;">
                    
                    <select name="type" id="alertTypeFilter" class="form-control form-control-sm alerts-type-filter">
                        <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>{{ __('All types') }}</option>
                        <option value="panic" {{ request('type') == 'panic' ? 'selected' : '' }}>{{ __('Panic') }}</option>
                        <option value="heart_rate" {{ request('type') == 'heart_rate' ? 'selected' : '' }}>{{ __('Heart Rate') }}</option>
                        <option value="safe_zone" {{ request('type') == 'safe_zone' ? 'selected' : '' }}>{{ __('Safe Zone') }}</option>
                        <option value="activity" {{ request('type') == 'activity' ? 'selected' : '' }}>{{ __('Activity') }}</option>
                    </select>

                    <select name="read_status" id="alertReadFilter" class="form-control form-control-sm alerts-read-filter">
                        <option value="all" {{ request('read_status') == 'all' ? 'selected' : '' }}>{{ __('All read status') }}</option>
                        <option value="read" {{ request('read_status') == 'read' ? 'selected' : '' }}>{{ __('Read') }}</option>
                        <option value="unread" {{ request('read_status') == 'unread' ? 'selected' : '' }}>{{ __('Unread') }}</option>
                    </select>

                    <select name="date_filter" id="alertDateFilter" class="form-control form-control-sm alerts-date-filter">
                        <option value="all" {{ request('date_filter') == 'all' ? 'selected' : '' }}>{{ __('All dates') }}</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>{{ __('Today') }}</option>
                        <option value="yesterday" {{ request('date_filter') == 'yesterday' ? 'selected' : '' }}>{{ __('Yesterday') }}</option>
                        <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>{{ __('This Week') }}</option>
                    </select>

                    <div class="alerts-search-wrapper">
                        <i class="fas fa-search alerts-search-icon"></i>
                        <input
                            type="text"
                            name="search"
                            id="alertSearchInput"
                            value="{{ request('search') }}"
                            class="form-control form-control-sm alerts-search-input"
                            placeholder="{{ __('Search title or message...') }}"
                        >
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="alerts-table" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Message') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Parent') }}</th>
                            <th>{{ __('Child') }}</th>
                            <th>{{ __('Read') }}</th>
                            <th>{{ __('Sent At') }}</th>
                            <th style="text-align:center;">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody id="alertsTableBody">
                        @forelse($alerts as $alert)
                            @php
                                $parentName = trim(($alert->parent->user->first_name ?? '') . ' ' . ($alert->parent->user->last_name ?? ''));
                                if ($parentName === '') $parentName = __('N/A');
                                
                                $childName = $alert->child->name ?? __('N/A');
                                $alertType = $alert->alert_type ?? 'default';

                                $typeClass = match($alertType) {
                                    'panic' => 'type-panic',
                                    'heart_rate' => 'type-heart_rate',
                                    'safe_zone' => 'type-safe_zone',
                                    'activity' => 'type-activity',
                                    default => 'type-default',
                                };

                                $alertDate = \Carbon\Carbon::parse($alert->sent_at ?? $alert->created_at);
                            @endphp

                            <tr>
                                <td style="text-align: center; font-weight: bold; color: #718096;">
                                    {{ $alerts->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    <div class="alert-title-cell">{{ $alert->title }}</div>
                                </td>
                                <td>
                                    <div class="alert-message-preview">{{ Str::limit($alert->message, 40) }}</div>
                                </td>
                                <td>
                                    <span class="alert-type-badge {{ $typeClass }}">
                                        {{ __(ucwords(str_replace('_', ' ', $alertType))) }}
                                    </span>
                                </td>
                                <td>{{ $parentName }}</td>
                                <td>{{ $childName }}</td>
                                <td>
                                    <span class="alert-read-badge {{ $alert->is_read ? 'read-yes' : 'read-no' }}">
                                        {{ $alert->is_read ? __('Read') : __('Unread') }}
                                    </span>
                                </td>
                                <td>{{ $alertDate->format('M d, Y h:i A') }}</td>
                                <td>
                                    <div class="alerts-action-buttons" style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                        <button type="button" class="alerts-action-button alerts-action-view js-view-alert"
                                            data-title="{{ $alert->title }}"
                                            data-message="{{ $alert->message }}"
                                            data-type="{{ __(ucwords(str_replace('_', ' ', $alertType))) }}"
                                            data-parent="{{ $parentName }}"
                                            data-child="{{ $childName }}"
                                            data-read="{{ $alert->is_read ? __('Read') : __('Unread') }}"
                                            data-sent-at="{{ $alertDate->format('M d, Y h:i A') }}"
                                            data-panic-event="{{ $alert->panic_event_id ?? __('N/A') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        @if(!$alert->is_read)
                                            <form action="{{ route('admin.alerts.mark-read', $alert->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="submit" class="alerts-action-button alerts-action-read" title="{{ __('Mark as read') }}">{{ __('Read') }}</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.alerts.mark-unread', $alert->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="submit" class="alerts-action-button alerts-action-unread" title="{{ __('Mark as unread') }}">{{ __('Unread') }}</button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.alerts.destroy', $alert->id) }}" method="POST" style="margin: 0;">
                                            @csrf @method('DELETE')
                                            <button type="button" class="alerts-action-button alerts-action-delete js-delete-alert" data-name="{{ $alert->title }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="fas fa-bell"></i></div>
                                        <div class="empty-state-title">{{ __('No alerts found') }}</div>
                                        <div class="empty-state-text">{{ __('Alerts will appear here once the system starts generating them.') }}</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($alerts->count())
                <div class="admin-pagination-wrapper" style="padding: 15px; border-top: 1px solid #eee; display: flex; justify-content: center; background-color: #fff; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                    {{ $alerts->links() }}
                </div>
            @endif
        </div>
    </div>

    <div id="alertViewModal" class="admin-modal-overlay view-alert-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header"><i class="fas fa-bell"></i> {{ __('Alert Details') }}</div>
            <span class="admin-modal-close" onclick="closeAdminModal('alertViewModal')">&times;</span>
            <div class="admin-modal-body">
                <div class="view-alert-details-box">
                    <div class="view-alert-info-row"><span class="view-alert-info-label">{{ __('Title') }}</span><span class="view-alert-info-value" id="viewAlertTitle"></span></div>
                    <div class="view-alert-info-row"><span class="view-alert-info-label">{{ __('Type') }}</span><span class="view-alert-info-value" id="viewAlertType"></span></div>
                    <div class="view-alert-info-row"><span class="view-alert-info-label">{{ __('Parent') }}</span><span class="view-alert-info-value" id="viewAlertParent"></span></div>
                    <div class="view-alert-info-row"><span class="view-alert-info-label">{{ __('Child') }}</span><span class="view-alert-info-value" id="viewAlertChild"></span></div>
                    <div class="view-alert-info-row"><span class="view-alert-info-label">{{ __('Read Status') }}</span><span class="view-alert-info-value" id="viewAlertRead"></span></div>
                    <div class="view-alert-info-row"><span class="view-alert-info-label">{{ __('Sent At') }}</span><span class="view-alert-info-value" id="viewAlertSentAt" dir="ltr" style="text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></span></div>
                    <div class="view-alert-info-row"><span class="view-alert-info-label">{{ __('Panic Event ID') }}</span><span class="view-alert-info-value" id="viewAlertPanicEvent"></span></div>
                </div>
                <div class="view-alert-message" id="viewAlertMessage" style="margin-top: 15px; padding: 10px; background: #f8fafc; border-radius: 8px; border-inline-start: 4px solid var(--taif-blue);"></div>
            </div>
        </div>
    </div>

    <div id="alertDeleteModal" class="admin-modal-overlay delete-alert-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header"><i class="fas fa-trash"></i> {{ __('Delete Alert') }}</div>
            <span class="admin-modal-close" onclick="closeAdminModal('alertDeleteModal')">&times;</span>
            <div class="admin-modal-body text-center">
                <div class="delete-warning-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <h4 class="delete-title">{{ __('Are you sure?') }}</h4>
                <p class="delete-message">{{ __('You are about to delete alert:') }}<br><strong id="deleteAlertName" dir="auto"></strong>.<br>{{ __('This action cannot be undone.') }}</p>
                <div class="delete-actions">
                    <button type="button" class="delete-cancel-button" onclick="closeAdminModal('alertDeleteModal')">{{ __('Cancel') }}</button>
                    <button type="button" class="delete-confirm-button" id="confirmAlertDeleteButton">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let alertDeleteForm = null;

        let searchTimeout = null;
        const searchForm = document.getElementById('searchForm');
        
        document.getElementById('alertSearchInput')?.addEventListener('keyup', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => { if (searchForm) searchForm.submit(); }, 500);
        });

        document.getElementById('alertTypeFilter')?.addEventListener('change', () => { if(searchForm) searchForm.submit(); });
        document.getElementById('alertReadFilter')?.addEventListener('change', () => { if(searchForm) searchForm.submit(); });
        document.getElementById('alertDateFilter')?.addEventListener('change', () => { if(searchForm) searchForm.submit(); });

        document.querySelectorAll('.js-view-alert').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('viewAlertTitle').innerText = this.dataset.title || '{{ __('N/A') }}';
                document.getElementById('viewAlertType').innerText = this.dataset.type || '{{ __('N/A') }}';
                document.getElementById('viewAlertParent').innerText = this.dataset.parent || '{{ __('N/A') }}';
                document.getElementById('viewAlertChild').innerText = this.dataset.child || '{{ __('N/A') }}';
                document.getElementById('viewAlertRead').innerText = this.dataset.read || '{{ __('N/A') }}';
                document.getElementById('viewAlertSentAt').innerText = this.dataset.sentAt || '{{ __('N/A') }}';
                document.getElementById('viewAlertPanicEvent').innerText = this.dataset.panicEvent || '{{ __('N/A') }}';
                document.getElementById('viewAlertMessage').innerText = this.dataset.message || '{{ __('N/A') }}';
                document.getElementById('alertViewModal').style.display = 'flex';
            });
        });

        document.querySelectorAll('.js-delete-alert').forEach(function (button) {
            button.addEventListener('click', function () {
                alertDeleteForm = this.closest('form');
                document.getElementById('deleteAlertName').innerText = this.dataset.name || '{{ __('this alert') }}';
                document.getElementById('alertDeleteModal').style.display = 'flex';
            });
        });

        const confirmBtn = document.getElementById('confirmAlertDeleteButton');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
                if (alertDeleteForm) alertDeleteForm.submit();
            });
        }

        function closeAdminModal(modalId) { document.getElementById(modalId).style.display = 'none'; }
        window.onclick = function (event) { if (event.target.classList.contains('admin-modal-overlay')) event.target.style.display = 'none'; };
    </script>
</body>
</html>