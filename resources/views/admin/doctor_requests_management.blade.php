<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Requests Management - Taif Project</title>

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
            font-family: 'Public Sans', Arial, sans-serif;
        }


        .admin-main-content {
            width: calc(100% - 260px);
            margin-left: 260px;
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
            margin-right: 1rem;
            text-align: right;
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

        .request-management-card {
            width: 100%;
            margin-top: 20px;
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .request-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .request-table-title {
            margin: 0;
            font-weight: 700;
            color: #111827;
        }

        .request-search-wrapper {
            position: relative;
            width: 250px;
        }

        .request-search-icon {
            position: absolute;
            left: 12px;
            top: 10px;
            color: #6b7280;
        }

        .request-search-input {
            padding-left: 2.5rem;
            background: #f8fafc;
        }

        .request-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        .request-table th,
        .request-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }

        .request-table th {
            background-color: #f8fafd;
            color: var(--muted-text);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .request-status-badge {
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

        .status-accepted {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .request-action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .request-action-button {
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
        }

        .request-action-view {
            background: #e0f2fe;
            color: #0ea5e9;
        }

        .request-action-accept {
            background: #dcfce7;
            color: #16a34a;
        }

        .request-action-reject {
            background: #fee2e2;
            color: #dc2626;
        }

        .request-action-delete {
            background: #fef3c7;
            color: #d97706;
        }

        .request-action-button:hover {
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
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
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

        .view-request-modal .admin-modal-header {
            background: var(--taif-green);
        }

        .delete-request-modal .admin-modal-header {
            background: var(--danger-red);
        }

        .admin-modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 22px;
            cursor: pointer;
        }

        .admin-modal-body {
            padding: 30px 25px;
        }

        .view-request-details-box {
            padding: 15px;
            background: #f8fafc;
            border-radius: 15px;
        }

        .view-request-info-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .view-request-info-row:last-child {
            border: none;
        }

        .view-request-info-label {
            color: var(--muted-text);
            font-weight: 700;
        }

        .view-request-info-value {
            color: var(--dark-text);
            font-weight: 600;
            text-align: right;
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
                margin-left: 75px;
            }
        }
        .request-status-filter {
            width: 160px;
            background: #f8fafc;
        }
    </style>
</head>
<body>
  @include('admin.partials.sidebar')

    <div class="admin-main-content text-start">
        <div class="admin-page-header">
            <div>
                <h4 class="admin-page-title">
                    Linking Requests Management
                </h4>

                <small class="admin-page-subtitle">
                    Manage doctor requests sent to parents
                </small>
            </div>

            <div class="admin-status-wrapper">
                <div class="admin-status-text">
                
                </div>

               
            </div>
        </div>

        <div class="request-management-card">
            <div class="request-table-header">
                <h6 class="request-table-title">
                    Requests Directory
                </h6>

                <div style="display: flex; gap: 10px; align-items: center;">
                    <select
                        id="requestStatusFilter"
                        class="form-control form-control-sm request-status-filter"
                        style="width: 160px; background: #f8fafc;"
                    >
                        <option value="all">All statuses</option>
                        <option value="pending">Pending</option>
                        <option value="accepted">Accepted</option>
                        <option value="rejected">Rejected</option>
                    </select>

                    <div class="request-search-wrapper">
                        <i class="fas fa-search request-search-icon"></i>

                        <input
                            type="text"
                            id="requestSearchInput"
                            class="form-control form-control-sm request-search-input"
                            placeholder="Search..."
                        >
                    </div>
                </div>
            </div>

            <table class="request-table">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Parent</th>
                        <th>Child</th>
                        <th>Status</th>
                        <th>Sent Date</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>

                <tbody id="requestTableBody">
                    @forelse($requests as $requestItem)
                        @php
                            $doctorName = trim(
                                ($requestItem->doctor->user->first_name ?? '') . ' ' .
                                ($requestItem->doctor->user->last_name ?? '')
                            );

                            $parentName = trim(
                                ($requestItem->parent->user->first_name ?? '') . ' ' .
                                ($requestItem->parent->user->last_name ?? '')
                            );

                            $child = $requestItem->parent?->children?->first();
                        //[Match Expression]: ميزة حديثة في PHP 8 لربط الحالة بصنف  CSS مباشرة (أنظف من switch-case)
                            $statusClass = match($requestItem->status) {
                                'accepted' => 'status-accepted',
                                'rejected' => 'status-rejected',
                                default => 'status-pending',
                            };
                        @endphp

                        <tr data-status="{{ $requestItem->status }}">
                            <td>{{ $doctorName ?: 'N/A' }}</td>

                            <td>{{ $parentName ?: 'N/A' }}</td>

                            <td>{{ $child->name ?? 'N/A' }}</td>

                            <td>
                                <span class="request-status-badge {{ $statusClass }}">
                                    {{ $requestItem->status }}
                                </span>
                            </td>

                            <td>
                                {{ $requestItem->created_at->format('M d, Y') }}
                            </td>

                            <td>
                                <div class="request-action-buttons">
                                    <button
                                        type="button"
                                        class="request-action-button request-action-view js-view-request"
                                        data-doctor="{{ $doctorName ?: 'N/A' }}"
                                        data-parent="{{ $parentName ?: 'N/A' }}"
                                        data-child="{{ $child->name ?? 'N/A' }}"
                                        data-status="{{ $requestItem->status }}"
                                        data-date="{{ $requestItem->created_at->format('M d, Y h:i A') }}"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @if($requestItem->status === 'pending')
                                        <form
                                            action="{{ route('admin.doctor-requests.accept', $requestItem->id) }}"
                                            method="POST"
                                            class="m-0"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="request-action-button request-action-accept"
                                            >
                                                Accept
                                            </button>
                                        </form>

                                        <form
                                            action="{{ route('admin.doctor-requests.reject', $requestItem->id) }}"
                                            method="POST"
                                            class="m-0"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="request-action-button request-action-reject"
                                            >
                                                Reject
                                            </button>
                                        </form>
                                    @endif

                                    <form
                                        action="{{ route('admin.doctor-requests.destroy', $requestItem->id) }}"
                                        method="POST"
                                        class="m-0"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            class="request-action-button request-action-delete js-delete-request"
                                            data-name="{{ $doctorName ?: 'this doctor' }} → {{ $parentName ?: 'this parent' }}"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                No requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-3">
                {{ $requests->links() }}
            </div>
        </div>
    </div>

    <div id="requestViewModal" class="admin-modal-overlay view-request-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-link"></i>
                Request Details
            </div>

            <span
                class="admin-modal-close"
                onclick="closeAdminModal('requestViewModal')"
            >
                &times;
            </span>

            <div class="admin-modal-body">
                <div class="view-request-details-box">
                    <div class="view-request-info-row">
                        <span class="view-request-info-label">Doctor</span>
                        <span class="view-request-info-value" id="viewRequestDoctor"></span>
                    </div>

                    <div class="view-request-info-row">
                        <span class="view-request-info-label">Parent</span>
                        <span class="view-request-info-value" id="viewRequestParent"></span>
                    </div>

                    <div class="view-request-info-row">
                        <span class="view-request-info-label">Child</span>
                        <span class="view-request-info-value" id="viewRequestChild"></span>
                    </div>

                    <div class="view-request-info-row">
                        <span class="view-request-info-label">Status</span>
                        <span class="view-request-info-value" id="viewRequestStatus"></span>
                    </div>

                    <div class="view-request-info-row">
                        <span class="view-request-info-label">Sent At</span>
                        <span class="view-request-info-value" id="viewRequestDate"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="requestDeleteModal" class="admin-modal-overlay delete-request-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-trash"></i>
                Delete Request
            </div>

            <span
                class="admin-modal-close"
                onclick="closeAdminModal('requestDeleteModal')"
            >
                &times;
            </span>

            <div class="admin-modal-body text-center">
                <div class="delete-warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <h4 class="delete-title">
                    Are you sure?
                </h4>

                <p class="delete-message">
                    You are about to delete request:
                    <strong id="deleteRequestName">this request</strong>.
                    This action cannot be undone.
                </p>

                <div class="delete-actions">
                    <button
                        type="button"
                        class="delete-cancel-button"
                        onclick="closeAdminModal('requestDeleteModal')"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        class="delete-confirm-button"
                        id="confirmRequestDeleteButton"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let requestDeleteForm = null;

        const requestSearchInput = document.getElementById('requestSearchInput');
        const requestStatusFilter = document.getElementById('requestStatusFilter');
        // ---------------------------------------------------------
        // دالة الفلترة المزدوجة (Multi-criteria Client-Side Filtering)
        // ---------------------------------------------------------
        function filterRequestsTable() {
            const searchTerm = requestSearchInput
                ? requestSearchInput.value.toLowerCase().trim()
                : '';

            const selectedStatus = requestStatusFilter
                ? requestStatusFilter.value
                : 'all';
            // جلب كل صفوف الجدول
            const requestRows = document.querySelectorAll('#requestTableBody tr');

            requestRows.forEach(function (row) {
                if (!row.dataset.status) {
                    return;
                }

                const rowText = row.innerText.toLowerCase();// النص المعروض في الصف
                const rowStatus = row.dataset.status; // حالة الطلب المخفية في data-status

                const matchesSearch = rowText.includes(searchTerm);
                const matchesStatus = selectedStatus === 'all' || rowStatus === selectedStatus;
                // إظهار الصف فقط إذا تحقق الشرطان معاً (&&)
                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }
        // ربط الأحداث: البحث الفوري مع الكتابة، وتغيير القائمة المنسدلة
        if (requestSearchInput) {
            requestSearchInput.addEventListener('keyup', filterRequestsTable);
        }

        if (requestStatusFilter) {
            requestStatusFilter.addEventListener('change', filterRequestsTable);
        }
        // ---------------------------------------------------------
        // دالة عرض التفاصيل (DOM Manipulation)
        // ---------------------------------------------------------
        document.querySelectorAll('.js-view-request').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('viewRequestDoctor').innerText = this.dataset.doctor || 'N/A';
                document.getElementById('viewRequestParent').innerText = this.dataset.parent || 'N/A';
                document.getElementById('viewRequestChild').innerText = this.dataset.child || 'N/A';
                document.getElementById('viewRequestStatus').innerText = this.dataset.status || 'N/A';
                document.getElementById('viewRequestDate').innerText = this.dataset.date || 'N/A';

                document.getElementById('requestViewModal').style.display = 'flex';
            });
        });
        // ---------------------------------------------------------
        // دوال الحذف (Safe Deletion Flow)
        // ---------------------------------------------------------
        document.querySelectorAll('.js-delete-request').forEach(function (button) {
            button.addEventListener('click', function () {
                requestDeleteForm = this.closest('form');

                document.getElementById('deleteRequestName').innerText =
                    this.dataset.name || 'this request';

                document.getElementById('requestDeleteModal').style.display = 'flex';
            });
        });

        document.getElementById('confirmRequestDeleteButton').addEventListener('click', function () {
            if (requestDeleteForm) {
                requestDeleteForm.submit();
            }
        });

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