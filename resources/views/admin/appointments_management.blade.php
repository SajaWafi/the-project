<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Appointment Management - Taif Project') }}</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
>

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

        /* 💡 استخدام الخصائص المنطقية للاتجاهات */
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

        .appointment-status-filter,
        .appointment-date-filter {
            width: 160px;
            background: #f8fafc;
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

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-scheduled {
            background: #dbeafe;
            color: #2563eb;
        }

        .status-completed {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #dc2626;
        }

        .appointment-action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .appointment-action-button {
            width: 32px;
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: 0.2s;
        }

        .appointment-action-view {
            background: #e0f2fe;
            color: #0ea5e9;
        }

        .appointment-action-edit {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .appointment-action-delete {
            background: #fef3c7;
            color: #d97706;
        }

        .appointment-action-button:hover {
            transform: translateY(-1px);
            opacity: 0.9;
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
            width: 440px;
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

        .view-appointment-modal .admin-modal-header {
            background: var(--taif-green);
        }

        .edit-appointment-modal .admin-modal-header {
            background: var(--taif-orange);
        }

        .delete-appointment-modal .admin-modal-header {
            background: var(--danger-red);
        }

        .admin-modal-close {
            position: absolute;
            top: 15px;
            inset-inline-end: 15px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 22px;
            cursor: pointer;
        }

        .admin-modal-body {
            padding: 30px 25px;
        }

        .view-appointment-details-box {
            padding: 15px;
            background: #f8fafc;
            border-radius: 15px;
        }

        .view-appointment-info-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .view-appointment-info-row:last-child {
            border: none;
        }

        .view-appointment-info-label {
            color: var(--muted-text);
            font-weight: 700;
        }

        .view-appointment-info-value {
            color: var(--dark-text);
            font-weight: 600;
            text-align: end;
        }

        .appointment-edit-form-group {
            margin-bottom: 16px;
            text-align: start;
        }

        .appointment-edit-form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1f5b87;
            font-size: 14px;
            font-weight: 700;
        }

        .appointment-edit-input,
        .appointment-edit-select,
        .appointment-edit-textarea {
            width: 100%;
            padding: 0 15px;
            background: #f8fafc;
            border: 2px solid var(--light-border);
            border-radius: 12px;
            font-size: 15px;
        }

        .appointment-edit-input,
        .appointment-edit-select {
            height: 45px;
        }

        .appointment-edit-textarea {
            min-height: 90px;
            padding-top: 12px;
            resize: none;
        }

        .appointment-edit-input:focus,
        .appointment-edit-select:focus,
        .appointment-edit-textarea:focus {
            outline: none;
            background: white;
            border-color: var(--taif-orange);
        }

        .time-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
        }

        .appointment-edit-save-button {
            width: 100%;
            padding: 12px;
            background: var(--taif-orange);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
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

        .delete-title {
            margin-bottom: 10px;
            color: #111827;
            font-size: 22px;
            font-weight: 800;
        }

        .delete-message {
            margin-bottom: 24px;
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
        }

        .delete-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

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

        .delete-cancel-button {
            background: #e5e7eb;
            color: #374151;
        }

        .delete-confirm-button {
            background: var(--danger-red);
            color: white;
        }

        @media (max-width: 992px) {
            .admin-sidebar {
                width: 75px;
            }

            .admin-sidebar-title,
            .admin-sidebar-link span {
                display: none;
            }

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
                    {{ __('Appointment Management') }}
                </h4>
                <small class="admin-page-subtitle">
                    {{ __('Manage all appointments in the system') }}
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
                    {{ __('Appointments Directory') }}
                </h6>

                <form action="{{ route('admin.appointments.index') }}" method="GET" id="searchForm" class="appointment-filter-search-wrapper" style="display: flex; gap: 10px; align-items: center; justify-content: flex-end;">
                    
                    <select
                        name="status"
                        id="appointmentStatusFilter"
                        class="form-control form-control-sm appointment-status-filter"
                    >
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('All statuses') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>{{ __('Scheduled') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                    </select>

                    <select
                        name="date_filter"
                        id="appointmentDateFilter"
                        class="form-control form-control-sm appointment-date-filter"
                    >
                        <option value="all" {{ request('date_filter') == 'all' ? 'selected' : '' }}>{{ __('All dates') }}</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>{{ __('Today') }}</option>
                        <option value="upcoming" {{ request('date_filter') == 'upcoming' ? 'selected' : '' }}>{{ __('Upcoming') }}</option>
                        <option value="past" {{ request('date_filter') == 'past' ? 'selected' : '' }}>{{ __('Past') }}</option>
                    </select>

                    <div class="appointment-search-wrapper">
                        <i class="fas fa-search appointment-search-icon"></i>
                        <input
                            type="text"
                            name="search"
                            id="appointmentSearchInput"
                            value="{{ request('search') }}"
                            class="form-control form-control-sm appointment-search-input"
                            placeholder="{{ __('Search doctor or parent...') }}"
                        >
                    </div>
                </form>
            </div>

            <table class="appointment-table">
                <thead>
                    <tr>
                        <th>{{ __('Doctor') }}</th>
                        <th>{{ __('Parent') }}</th>
                        <th>{{ __('Child') }}</th>
                        <th>{{ __('Workplace') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Time') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th style="text-align:center;">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody id="appointmentTableBody">
                    @forelse($appointments as $appointment)
                        @php
                            $doctorName = trim(
                                ($appointment->doctor->user->first_name ?? '') . ' ' .
                                ($appointment->doctor->user->last_name ?? '')
                            );

                            $parentName = trim(
                                ($appointment->parent->user->first_name ?? '') . ' ' .
                                ($appointment->parent->user->last_name ?? '')
                            );

                            $status = $appointment->status ?? 'pending';

                            $statusClass = match($status) {
                                'scheduled' => 'status-scheduled',
                                'completed' => 'status-completed',
                                'cancelled' => 'status-cancelled',
                                default => 'status-pending',
                            };

                            $appointmentDate = \Carbon\Carbon::parse($appointment->date);
                            
                            $timeText =
                                str_pad($appointment->from_hour, 2, '0', STR_PAD_LEFT) . ':' .
                                str_pad($appointment->from_minute, 2, '0', STR_PAD_LEFT) . ' ' .
                                $appointment->from_period .
                                ' - ' .
                                str_pad($appointment->to_hour, 2, '0', STR_PAD_LEFT) . ':' .
                                str_pad($appointment->to_minute, 2, '0', STR_PAD_LEFT) . ' ' .
                                $appointment->to_period;
                        @endphp

                        <tr data-status="{{ $status }}">
                            <td>{{ $doctorName ?: __('N/A') }}</td>
                            <td>{{ $parentName ?: __('N/A') }}</td>
                            <td>{{ $appointment->child->name ?? __('N/A') }}</td>
                            <td>{{ $appointment->workplace->place_name ?? __('N/A') }}</td>
                            <td>{{ $appointmentDate->format('M d, Y') }}</td>
                            <td dir="ltr" style="text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}">{{ $timeText }}</td>
                            <td>
                                <span class="appointment-status-badge {{ $statusClass }}">
                                    {{ __($status) }}
                                </span>
                            </td>
                            <td>
                                <div class="appointment-action-buttons">
                                    <button
                                        type="button"
                                        class="appointment-action-button appointment-action-view js-view-appointment"
                                        data-doctor="{{ $doctorName ?: __('N/A') }}"
                                        data-parent="{{ $parentName ?: __('N/A') }}"
                                        data-child="{{ $appointment->child->name ?? __('N/A') }}"
                                        data-workplace="{{ $appointment->workplace->place_name ?? __('N/A') }}"
                                        data-date="{{ $appointmentDate->format('M d, Y') }}"
                                        data-time="{{ $timeText }}"
                                        data-status="{{ __($status) }}"
                                        data-note="{{ $appointment->note ?? __('N/A') }}"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <button
                                        type="button"
                                        class="appointment-action-button appointment-action-edit js-edit-appointment"
                                        data-id="{{ $appointment->id }}"
                                        data-date="{{ $appointment->date }}"
                                        data-from-hour="{{ $appointment->from_hour }}"
                                        data-from-minute="{{ $appointment->from_minute }}"
                                        data-from-period="{{ $appointment->from_period }}"
                                        data-to-hour="{{ $appointment->to_hour }}"
                                        data-to-minute="{{ $appointment->to_minute }}"
                                        data-to-period="{{ $appointment->to_period }}"
                                        data-status="{{ $status }}"
                                        data-note="{{ $appointment->note }}"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form
                                        action="{{ route('admin.appointments.destroy', $appointment->id) }}"
                                        method="POST"
                                        class="m-0"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="button"
                                            class="appointment-action-button appointment-action-delete js-delete-appointment"
                                            data-name="{{ $doctorName ?: __('Doctor') }} / {{ $parentName ?: __('Parent') }}"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                {{ __('No appointments found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-3">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>

    <div id="appointmentViewModal" class="admin-modal-overlay view-appointment-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header"><i class="fas fa-calendar-check"></i> {{ __('Appointment Details') }}</div>
            <span class="admin-modal-close" onclick="closeAdminModal('appointmentViewModal')">&times;</span>
            <div class="admin-modal-body">
                <div class="view-appointment-details-box">
                    <div class="view-appointment-info-row"><span class="view-appointment-info-label">{{ __('Doctor') }}</span><span class="view-appointment-info-value" id="viewAppointmentDoctor"></span></div>
                    <div class="view-appointment-info-row"><span class="view-appointment-info-label">{{ __('Parent') }}</span><span class="view-appointment-info-value" id="viewAppointmentParent"></span></div>
                    <div class="view-appointment-info-row"><span class="view-appointment-info-label">{{ __('Child') }}</span><span class="view-appointment-info-value" id="viewAppointmentChild"></span></div>
                    <div class="view-appointment-info-row"><span class="view-appointment-info-label">{{ __('Workplace') }}</span><span class="view-appointment-info-value" id="viewAppointmentWorkplace"></span></div>
                    <div class="view-appointment-info-row"><span class="view-appointment-info-label">{{ __('Date') }}</span><span class="view-appointment-info-value" id="viewAppointmentDate"></span></div>
                    <div class="view-appointment-info-row"><span class="view-appointment-info-label">{{ __('Time') }}</span><span class="view-appointment-info-value" id="viewAppointmentTime" dir="ltr" style="text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></span></div>
                    <div class="view-appointment-info-row"><span class="view-appointment-info-label">{{ __('Status') }}</span><span class="view-appointment-info-value" id="viewAppointmentStatus"></span></div>
                    <div class="view-appointment-info-row"><span class="view-appointment-info-label">{{ __('Note') }}</span><span class="view-appointment-info-value" id="viewAppointmentNote"></span></div>
                </div>
            </div>
        </div>
    </div>

    <div id="appointmentEditModal" class="admin-modal-overlay edit-appointment-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header"><i class="fas fa-edit"></i> {{ __('Edit Appointment') }}</div>
            <span class="admin-modal-close" onclick="closeAdminModal('appointmentEditModal')">&times;</span>
            <div class="admin-modal-body">
                <form id="appointmentUpdateForm" method="POST">
                    @csrf @method('PUT')
                    <div class="appointment-edit-form-group">
                        <label>{{ __('Date') }}</label>
                        <input type="date" id="editAppointmentDate" name="date" class="appointment-edit-input">
                    </div>
                    <div class="appointment-edit-form-group">
                        <label>{{ __('Time Since') }}</label>
                        <div class="time-grid" dir="ltr">
                            <select id="editFromHour" name="from_hour" class="appointment-edit-select">@for($i = 1; $i <= 12; $i++)<option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>@endfor</select>
                            <select id="editFromMinute" name="from_minute" class="appointment-edit-select">@foreach([0, 15, 30, 45] as $minute)<option value="{{ $minute }}">{{ str_pad($minute, 2, '0', STR_PAD_LEFT) }}</option>@endforeach</select>
                            <select id="editFromPeriod" name="from_period" class="appointment-edit-select"><option value="AM">AM</option><option value="PM">PM</option></select>
                        </div>
                    </div>
                    <div class="appointment-edit-form-group">
                        <label>{{ __('Time To') }}</label>
                        <div class="time-grid" dir="ltr">
                            <select id="editToHour" name="to_hour" class="appointment-edit-select">@for($i = 1; $i <= 12; $i++)<option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>@endfor</select>
                            <select id="editToMinute" name="to_minute" class="appointment-edit-select">@foreach([0, 15, 30, 45] as $minute)<option value="{{ $minute }}">{{ str_pad($minute, 2, '0', STR_PAD_LEFT) }}</option>@endforeach</select>
                            <select id="editToPeriod" name="to_period" class="appointment-edit-select"><option value="AM">AM</option><option value="PM">PM</option></select>
                        </div>
                    </div>
                    <div class="appointment-edit-form-group">
                        <label>{{ __('Status') }}</label>
                        <select id="editAppointmentStatus" name="status" class="appointment-edit-select">
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="scheduled">{{ __('Scheduled') }}</option>
                            <option value="completed">{{ __('Completed') }}</option>
                            <option value="cancelled">{{ __('Cancelled') }}</option>
                        </select>
                    </div>
                    <div class="appointment-edit-form-group">
                        <label>{{ __('Note') }}</label>
                        <textarea id="editAppointmentNote" name="note" class="appointment-edit-textarea"></textarea>
                    </div>
                    <button type="submit" class="appointment-edit-save-button">{{ __('Save Changes') }}</button>
                </form>
            </div>
        </div>
    </div>

    <div id="appointmentDeleteModal" class="admin-modal-overlay delete-appointment-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header"><i class="fas fa-trash"></i> {{ __('Delete Appointment') }}</div>
            <span class="admin-modal-close" onclick="closeAdminModal('appointmentDeleteModal')">&times;</span>
            <div class="admin-modal-body text-center">
                <div class="delete-warning-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <h4 class="delete-title">{{ __('Are you sure?') }}</h4>
                <p class="delete-message">{{ __('You are about to delete appointment:') }}<br><strong id="deleteAppointmentName" dir="auto"></strong>.<br>{{ __('This action cannot be undone.') }}</p>
                <div class="delete-actions">
                    <button type="button" class="delete-cancel-button" onclick="closeAdminModal('appointmentDeleteModal')">{{ __('Cancel') }}</button>
                    <button type="button" class="delete-confirm-button" id="confirmAppointmentDeleteButton">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let appointmentDeleteForm = null;

        let searchTimeout = null;
        const searchForm = document.getElementById('searchForm');
        const appointmentSearchInput = document.getElementById('appointmentSearchInput');
        const appointmentStatusFilter = document.getElementById('appointmentStatusFilter');
        const appointmentDateFilter = document.getElementById('appointmentDateFilter');

        if (appointmentSearchInput) {
            appointmentSearchInput.addEventListener('keyup', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (searchForm) searchForm.submit();
                }, 500);
            });
        }

        if (appointmentStatusFilter) {
            appointmentStatusFilter.addEventListener('change', () => { if(searchForm) searchForm.submit(); });
        }
        if (appointmentDateFilter) {
            appointmentDateFilter.addEventListener('change', () => { if(searchForm) searchForm.submit(); });
        }

        document.querySelectorAll('.js-view-appointment').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('viewAppointmentDoctor').innerText = this.dataset.doctor || '{{ __('N/A') }}';
                document.getElementById('viewAppointmentParent').innerText = this.dataset.parent || '{{ __('N/A') }}';
                document.getElementById('viewAppointmentChild').innerText = this.dataset.child || '{{ __('N/A') }}';
                document.getElementById('viewAppointmentWorkplace').innerText = this.dataset.workplace || '{{ __('N/A') }}';
                document.getElementById('viewAppointmentDate').innerText = this.dataset.date || '{{ __('N/A') }}';
                document.getElementById('viewAppointmentTime').innerText = this.dataset.time || '{{ __('N/A') }}';
                document.getElementById('viewAppointmentStatus').innerText = this.dataset.status || '{{ __('N/A') }}';
                document.getElementById('viewAppointmentNote').innerText = this.dataset.note || '{{ __('N/A') }}';

                document.getElementById('appointmentViewModal').style.display = 'flex';
            });
        });

        document.querySelectorAll('.js-edit-appointment').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('editAppointmentDate').value = this.dataset.date || '';
                document.getElementById('editFromHour').value = this.dataset.fromHour || '';
                document.getElementById('editFromMinute').value = this.dataset.fromMinute || '';
                document.getElementById('editFromPeriod').value = this.dataset.fromPeriod || '';
                document.getElementById('editToHour').value = this.dataset.toHour || '';
                document.getElementById('editToMinute').value = this.dataset.toMinute || '';
                document.getElementById('editToPeriod').value = this.dataset.toPeriod || '';
                document.getElementById('editAppointmentStatus').value = this.dataset.status || 'pending';
                document.getElementById('editAppointmentNote').value = this.dataset.note || '';

                if(document.getElementById('appointmentUpdateForm')) {
                    document.getElementById('appointmentUpdateForm').action = '/admin/appointments/' + this.dataset.id;
                }
                document.getElementById('appointmentEditModal').style.display = 'flex';
            });
        });

        document.querySelectorAll('.js-delete-appointment').forEach(function (button) {
            button.addEventListener('click', function () {
                appointmentDeleteForm = this.closest('form');
                document.getElementById('deleteAppointmentName').innerText = this.dataset.name || '{{ __('this appointment') }}';
                document.getElementById('appointmentDeleteModal').style.display = 'flex';
            });
        });

        const confirmBtn = document.getElementById('confirmAppointmentDeleteButton');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
                if (appointmentDeleteForm) appointmentDeleteForm.submit();
            });
        }

        function closeAdminModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        window.onclick = function (event) {
            if (event.target.classList.contains('admin-modal-overlay')) {
                event.target.style.display = 'none';
            }
        };
    </script>
</body>
</html>