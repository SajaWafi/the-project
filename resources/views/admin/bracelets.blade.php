<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bracelets Management - Taif Project</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
            --warning-yellow: #f59e0b;
        }

        * { box-sizing: border-box; }
        body { margin: 0; display: flex; background-color: var(--page-bg); font-family: 'Public Sans', Arial, sans-serif; }
        .admin-main-content { width: calc(100% - 260px); margin-left: 260px; padding: 2rem; }
        .admin-page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding: 1.2rem 1.5rem; background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03); }
        .admin-page-title { margin-bottom: 0; font-weight: 700; }
        .admin-page-subtitle { color: #6b7280; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px; }
        .stat-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 15px; border: 1px solid var(--light-border); }
        .stat-icon { width: 54px; height: 54px; border-radius: 14px; display: flex; justify-content: center; align-items: center; font-size: 24px; }
        .stat-info h3 { margin: 0; font-size: 24px; font-weight: 800; color: var(--dark-text); }
        .stat-info p { margin: 0; color: var(--muted-text); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        .bracelet-management-card { width: 100%; overflow-x: auto; background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); }
        .bracelet-table-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid #e5e7eb; }
        .bracelet-table-title { margin: 0; font-weight: 700; color: #111827; }
        .bracelet-filter-search-wrapper { display: flex; gap: 10px; align-items: center; }
        .bracelet-status-filter { width: 170px; background: #f8fafc; }
        .bracelet-search-wrapper { position: relative; width: 250px; }
        .bracelet-search-icon { position: absolute; left: 12px; top: 10px; color: #6b7280; }
        .bracelet-search-input { padding-left: 2.5rem; background: #f8fafc; }
        .bracelet-table { width: 100%; border-collapse: collapse; table-layout: auto; }
        .bracelet-table th, .bracelet-table td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .bracelet-table th { background-color: #f8fafd; color: var(--muted-text); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .bracelet-info-cell { display: flex; align-items: center; padding-left: 0.5rem; }
        .bracelet-icon { width: 40px; height: 40px; margin-right: 12px; display: flex; align-items: center; justify-content: center; background: #edf2f7; color: var(--taif-blue); border-radius: 10px; font-size: 18px; }
        .bracelet-id-main { color: #111827; font-weight: 800; font-size: 16px; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 800; text-transform: uppercase; }
        .status-connected { background: #c6f6d5; color: #22543d; }
        .status-disconnected { background: #fee2e2; color: #dc2626; }
        .child-badge { display: inline-block; padding: 4px 12px; background: #eef2ff; color: #4f46e5; border-radius: 6px; font-size: 12px; font-weight: 600; }
        .child-unassigned { background: #f1f5f9; color: #64748b; }
        
        .bracelet-action-buttons { display: flex; justify-content: center; align-items: center; gap: 8px; flex-wrap: wrap; }
        .bracelet-action-button { width: 32px; height: 32px; display: flex; justify-content: center; align-items: center; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; transition: 0.2s; }
        .action-view { background: #e0f2fe; color: #0ea5e9; }
        .action-edit { background: #e0e7ff; color: #4f46e5; }
        .action-delete { background: #fee2e2; color: #dc2626; }
        .action-unlink { background: #fef3c7; color: #d97706; } /* 💡 زر فك الربط */
        
        .bracelet-action-button:hover { transform: translateY(-1px); opacity: 0.9; }
        .add-bracelet-button { height: 34px; padding: 0 14px; display: flex; align-items: center; gap: 7px; border: none; border-radius: 10px; background: var(--taif-orange); color: white; font-size: 13px; font-weight: 800; cursor: pointer; white-space: nowrap; }
        .add-bracelet-button:hover { opacity: 0.9; transform: translateY(-1px); }
        
        .admin-modal-overlay { position: fixed; top: 0; left: 0; z-index: 2000; width: 100%; height: 100%; display: none; justify-content: center; align-items: center; background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(5px); animation: modalFadeIn 0.3s ease; }
        @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }
        .admin-modal-box { position: relative; width: 420px; max-width: 95%; background: white; border-radius: 25px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15); animation: modalSlideUp 0.4s ease; overflow: visible; }
        @keyframes modalSlideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .admin-modal-header { display: flex; justify-content: center; align-items: center; gap: 10px; padding: 20px; color: white; font-size: 18px; font-weight: 800; text-align: center; border-radius: 25px 25px 0 0; }
        .view-modal .admin-modal-header { background: var(--taif-green); }
        .edit-modal .admin-modal-header { background: var(--taif-orange); }
        .delete-modal .admin-modal-header { background: var(--danger-red); }
        .create-modal .admin-modal-header { background: var(--taif-blue); }
        .unlink-modal .admin-modal-header { background: var(--warning-yellow); }
        
        .admin-modal-close { position: absolute; top: 15px; right: 15px; color: rgba(255, 255, 255, 0.8); font-size: 22px; cursor: pointer; transition: 0.2s; z-index: 10; }
        .admin-modal-close:hover { color: white; transform: scale(1.1); }
        .admin-modal-body { padding: 30px 25px; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; color: #1f5b87; font-size: 14px; font-weight: 700; }
        .form-input { width: 100%; height: 45px; padding: 0 15px; background: #f8fafc; border: 2px solid var(--light-border); border-radius: 12px; font-size: 15px; }
        .form-input:focus { outline: none; background: white; border-color: var(--taif-orange); }
        .modal-save-btn { width: 100%; padding: 12px; background: var(--taif-orange); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 800; cursor: pointer; transition: 0.3s; }
        .modal-save-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(246, 173, 85, 0.3); }
        
        /* Select2 Custom Styling for Admin panel */
        .select2-container--default .select2-selection--single { height: 45px; background: #f8fafc; border: 2px solid var(--light-border); border-radius: 12px; display: flex; align-items: center; padding-left: 5px; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { color: #333; font-size: 15px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 43px; }
        .select2-container--default.select2-container--open .select2-selection--single { border-color: var(--taif-orange); background: white; }

        .details-box { padding: 15px; background: #f8fafc; border-radius: 15px; margin-top: 15px; }
        .details-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .details-row:last-child { border: none; }
        .details-label { color: var(--muted-text); font-weight: 700; }
        .details-value { color: var(--dark-text); font-weight: 600; }
        .large-icon { width: 80px; height: 80px; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; background: #f0fff4; color: var(--taif-green); border-radius: 12px; font-size: 35px; }
        .warning-icon { width: 72px; height: 72px; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; background: #fee2e2; color: #dc2626; border-radius: 50%; font-size: 30px; }
        .unlink-warning-icon { background: #fef3c7; color: #d97706; }
        
        .delete-title { margin-bottom: 10px; color: #111827; font-size: 22px; font-weight: 800; }
        .delete-message { margin-bottom: 24px; color: #6b7280; font-size: 14px; line-height: 1.6; }
        .delete-actions { display: flex; justify-content: center; gap: 12px; }
        .delete-btn, .unlink-btn, .cancel-btn { min-width: 110px; height: 42px; border: none; border-radius: 12px; font-size: 14px; font-weight: 800; cursor: pointer; }
        .cancel-btn { background: #e5e7eb; color: #374151; }
        .delete-btn { background: var(--danger-red); color: white; }
        .unlink-btn { background: var(--warning-yellow); color: white; }
        @media (max-width: 992px) { .admin-main-content { width: calc(100% - 75px); margin-left: 75px; } }
    </style>
</head>

<body>
    @include('admin.partials.sidebar')

    <div class="admin-main-content text-start">
        <div class="admin-page-header">
            <div>
                <h4 class="admin-page-title">Bracelets Management</h4>
                <small class="admin-page-subtitle">Manage smart devices, track connections, and assign bracelets</small>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e0f2fe; color: #0ea5e9;"><i class="fas fa-microchip"></i></div>
                <div class="stat-info">
                    <h3>{{ $totalBracelets }}</h3>
                    <p>Total Devices</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #dcfce7; color: #22c55e;"><i class="fas fa-link"></i></div>
                <div class="stat-info">
                    <h3>{{ $assignedBracelets }}</h3>
                    <p>Assigned</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #f1f5f9; color: #64748b;"><i class="fas fa-box-open"></i></div>
                <div class="stat-info">
                    <h3>{{ $unassignedBracelets }}</h3>
                    <p>In Stock</p>
                </div>
            </div>
        </div>

        <div class="bracelet-management-card">
            <div class="bracelet-table-header">
                <h6 class="bracelet-table-title">Devices Directory</h6>

                <div class="bracelet-filter-search-wrapper">
                    <button type="button" class="add-bracelet-button" onclick="openAdminModal('braceletCreateModal')">
                        <i class="fas fa-plus"></i> Add Bracelet
                    </button>

                    <select id="braceletStatusFilter" class="form-control form-control-sm bracelet-status-filter">
                        <option value="all">All statuses</option>
                        <option value="active">Connected</option>
                        <option value="inactive">Disconnected</option>
                    </select>

                    <div class="bracelet-search-wrapper">
                        <i class="fas fa-search bracelet-search-icon"></i>
                        <input type="text" id="braceletSearchInput" class="form-control form-control-sm bracelet-search-input" placeholder="Search ID or Serial...">
                    </div>
                </div>
            </div>

            <table id="braceletManagementTable" class="bracelet-table">
                <thead>
                    <tr>
                        <th>Device Info</th>
                        <th>Serial Number</th>
                        <th>Assigned Child</th>
                        <th>Last Sync</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>

                <tbody id="braceletTableBody">
                    @forelse($bracelets as $bracelet)
                        @php
                            $status = $bracelet->status ?? 'inactive';
                            $statusClass = $status === 'active' ? 'status-connected' : 'status-disconnected';
                            $statusText = $status === 'active' ? 'Connected' : 'Disconnected';
                            
                            $childName = $bracelet->child ? $bracelet->child->name : 'Unassigned';
                            $childClass = $bracelet->child ? 'child-badge' : 'child-badge child-unassigned';
                        @endphp

                        <tr data-status="{{ $status }}">
                            <td>
                                <div class="bracelet-info-cell">
                                    <div class="bracelet-icon">
                                        <i class="fas fa-microchip"></i>
                                    </div>
                                    <div>
                                        <div class="bracelet-id-main">Bracelet #{{ $bracelet->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <td style="font-family: monospace; font-size: 14px; color: #4b5563; font-weight: bold;">
                                {{ $bracelet->serial_number ?? 'N/A' }}
                            </td>

                            <td>
                                <span class="{{ $childClass }}">{{ $childName }}</span>
                            </td>

                            <td>
                                <span style="font-weight: 600; color: #4a5568;">
                                    {{ $bracelet->last_sync_at ? \Carbon\Carbon::parse($bracelet->last_sync_at)->format('M d, Y h:i A') : 'Never' }}
                                </span>
                            </td>

                            <td>
                                <span class="status-badge {{ $statusClass }}">
                                    @if($status === 'active') <i class="fas fa-circle" style="font-size: 8px; margin-right: 3px;"></i> @endif
                                    {{ $statusText }}
                                </span>
                            </td>

                            <td>
                                <div class="bracelet-action-buttons">
                                    <button type="button" class="bracelet-action-button action-view js-view-bracelet" title="View Details"
                                        data-id="{{ $bracelet->id }}"
                                        data-serial="{{ $bracelet->serial_number ?? 'N/A' }}"
                                        data-child="{{ $childName }}"
                                        data-status="{{ $statusText }}"
                                        data-sync="{{ $bracelet->last_sync_at ?? 'Never' }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button type="button" class="bracelet-action-button action-edit js-edit-bracelet" title="Edit & Link"
                                        data-id="{{ $bracelet->id }}"
                                        data-serial="{{ $bracelet->serial_number }}"
                                        data-child-id="{{ $bracelet->child_id }}"
                                        data-status="{{ $bracelet->status }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    @if($bracelet->child_id)
                                    <form action="{{ route('admin.bracelets.unlink', $bracelet->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="button" class="bracelet-action-button action-unlink js-unlink-bracelet" title="Unlink Child"
                                            data-id="{{ $bracelet->id }}" data-child="{{ $childName }}">
                                            <i class="fas fa-link-slash"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <form action="{{ route('admin.bracelets.destroy', $bracelet->id ?? 0) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="bracelet-action-button action-delete js-delete-bracelet" title="Delete Bracelet"
                                            data-id="{{ $bracelet->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                No bracelets found in the system.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="braceletCreateModal" class="admin-modal-overlay create-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-plus-circle"></i> Add New Bracelet
            </div>
            <span class="admin-modal-close" onclick="closeAdminModal('braceletCreateModal')">&times;</span>

            <div class="admin-modal-body">
                <form action="{{ route('admin.bracelets.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label>Serial Number (MAC Address or Code)</label>
                        <input type="text" name="serial_number" class="form-input" placeholder="e.g. ESP32-A1B2C3" required>
                    </div>

                    <div class="form-group">
                        <label>Initial Status</label>
                        <select name="status" class="form-input">
                            <option value="active">Connected</option>
                            <option value="inactive" selected>Disconnected</option>
                        </select>
                    </div>

                    <button type="submit" class="modal-save-btn">Add Bracelet</button>
                </form>
            </div>
        </div>
    </div>

    <div id="braceletViewModal" class="admin-modal-overlay view-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header"><i class="fas fa-microchip"></i> Device Details</div>
            <span class="admin-modal-close" onclick="closeAdminModal('braceletViewModal')">&times;</span>
            <div class="admin-modal-body text-center">
                <div class="large-icon"><i class="fas fa-link"></i></div>
                <h3 id="viewId" style="color: #1f5b87; font-weight: 700; margin-bottom: 5px;"></h3>
                <span id="viewStatus" class="status-badge" style="margin-bottom: 20px;"></span>
                <div class="details-box">
                    <div class="details-row"><span class="details-label">Serial Number</span><span class="details-value" id="viewSerial" style="font-family: monospace;"></span></div>
                    <div class="details-row"><span class="details-label">Assigned Child</span><span class="details-value" id="viewChild"></span></div>
                    <div class="details-row"><span class="details-label">Last Sync</span><span class="details-value" id="viewSync"></span></div>
                </div>
            </div>
        </div>
    </div>

    <div id="braceletEditModal" class="admin-modal-overlay edit-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-edit"></i> Edit & Link Device
            </div>
            <span class="admin-modal-close" onclick="closeAdminModal('braceletEditModal')">&times;</span>

            <div class="admin-modal-body">
                <form id="braceletUpdateForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Serial Number</label>
                        <input type="text" id="editSerialNumber" name="serial_number" class="form-input" required>
                    </div>

                   <div class="form-group">
                        <label>Assign to Child</label>
                        <select id="editChildId" name="child_id" class="form-input" style="width: 100%;">
                            <option value="">-- Unassigned (In Stock) --</option>
                            @foreach($children as $child)
                                @php
                                    $isAssigned = in_array($child->id, $assignedChildIds) ? 'true' : 'false';
                                @endphp
                                <option value="{{ $child->id }}" data-assigned="{{ $isAssigned }}">
                                    {{ $child->name }} {{ $isAssigned == 'true' ? '(Already Assigned)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Connection Status</label>
                        <select id="editStatus" name="status" class="form-input">
                            <option value="active">Connected</option>
                            <option value="inactive">Disconnected</option>
                        </select>
                    </div>

                    <button type="submit" class="modal-save-btn">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <div id="braceletUnlinkModal" class="admin-modal-overlay unlink-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-link-slash"></i> Unlink Child
            </div>
            <span class="admin-modal-close" onclick="closeAdminModal('braceletUnlinkModal')">&times;</span>

            <div class="admin-modal-body text-center">
                <div class="warning-icon unlink-warning-icon">
                    <i class="fas fa-link-slash"></i>
                </div>

                <h4 class="delete-title">Are you sure?</h4>
                <p class="delete-message">
                    You are about to unlink <strong id="unlinkChildText"></strong> from <strong id="unlinkIdText"></strong>.<br>
                    The device will be returned to stock.
                </p>

                <div class="delete-actions">
                    <button type="button" class="cancel-btn" onclick="closeAdminModal('braceletUnlinkModal')">Cancel</button>
                    <button type="button" class="unlink-btn" id="confirmBraceletUnlinkBtn">Unlink</button>
                </div>
            </div>
        </div>
    </div>

    <div id="braceletDeleteModal" class="admin-modal-overlay delete-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-trash"></i> Delete Device
            </div>
            <span class="admin-modal-close" onclick="closeAdminModal('braceletDeleteModal')">&times;</span>

            <div class="admin-modal-body text-center">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <h4 class="delete-title">Are you sure?</h4>
                <p class="delete-message">
                    You are about to delete <strong id="deleteIdText"></strong>.<br>
                    This action cannot be undone.
                </p>

                <div class="delete-actions">
                    <button type="button" class="cancel-btn" onclick="closeAdminModal('braceletDeleteModal')">Cancel</button>
                    <button type="button" class="delete-btn" id="confirmBraceletDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // دوال التحكم بالنوافذ
        function openAdminModal(modalId) { document.getElementById(modalId).style.display = 'flex'; }
        function closeAdminModal(modalId) { document.getElementById(modalId).style.display = 'none'; }
        window.onclick = function (event) { if (event.target.classList.contains('admin-modal-overlay')) event.target.style.display = 'none'; };

        // 💡 تفعيل مكتبة البحث Select2
        $(document).ready(function() {
            $('#editChildId').select2({
                placeholder: "Search for a child...",
                allowClear: true,
                dropdownParent: $('#braceletEditModal') // مهم جداً باش تخدم داخل المودال
            });
        });

        // الفلترة الفورية
        const searchInput = document.getElementById('braceletSearchInput');
        const statusFilter = document.getElementById('braceletStatusFilter');

        function filterTable() {
            const term = searchInput ? searchInput.value.toLowerCase().trim() : '';
            const selectedStatus = statusFilter ? statusFilter.value : 'all';
            const rows = document.querySelectorAll('#braceletTableBody tr');

            rows.forEach(row => {
                if (!row.dataset.status) return;
                const text = row.innerText.toLowerCase();
                const rowStatus = row.dataset.status;
                const matchesSearch = text.includes(term);
                const matchesStatus = selectedStatus === 'all' || rowStatus === selectedStatus;
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }

        if(searchInput) searchInput.addEventListener('keyup', filterTable);
        if(statusFilter) statusFilter.addEventListener('change', filterTable);

        // عرض التفاصيل
        document.querySelectorAll('.js-view-bracelet').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('viewId').innerText = "Bracelet #" + this.dataset.id;
                document.getElementById('viewSerial').innerText = this.dataset.serial;
                document.getElementById('viewChild').innerText = this.dataset.child;
                document.getElementById('viewSync').innerText = this.dataset.sync;
                
                const statusSpan = document.getElementById('viewStatus');
                statusSpan.innerText = this.dataset.status;
                statusSpan.className = 'status-badge ' + (this.dataset.status === 'Connected' ? 'status-connected' : 'status-disconnected');

                openAdminModal('braceletViewModal');
            });
        });

        // دالة التعديل (Edit)
     // دالة التعديل (Edit) المتقدمة
        document.querySelectorAll('.js-edit-bracelet').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('editStatus').value = this.dataset.status;
                document.getElementById('editSerialNumber').value = this.dataset.serial;
                
                let currentChildId = this.dataset.childId;

                // 💡 فلترة القائمة: إغلاق الأطفال المربوطين مسبقاً، إلا الطفل المربوط بهادي الإسوارة
                $('#editChildId option').each(function() {
                    if ($(this).val() === "") return; // تخطي خيار Unassigned
                    
                    if ($(this).data('assigned') == true && $(this).val() !== currentChildId) {
                        $(this).prop('disabled', true); // إغلاق الاختيار
                    } else {
                        $(this).prop('disabled', false); // فتح الاختيار
                    }
                });

                // تحديث الـ Select2 باش يعرض التغييرات
                $('#editChildId').val(currentChildId).trigger('change');
                
                document.getElementById('braceletUpdateForm').action = '/admin/bracelets/' + this.dataset.id;
                openAdminModal('braceletEditModal');
            });
        });

        // 💡 دالة فك الربط (Unlink)
        let unlinkForm = null;
        document.querySelectorAll('.js-unlink-bracelet').forEach(btn => {
            btn.addEventListener('click', function() {
                unlinkForm = this.closest('form');
                document.getElementById('unlinkIdText').innerText = "Bracelet #" + this.dataset.id;
                document.getElementById('unlinkChildText').innerText = this.dataset.child;
                openAdminModal('braceletUnlinkModal');
            });
        });
        document.getElementById('confirmBraceletUnlinkBtn').addEventListener('click', function() {
            if (unlinkForm) unlinkForm.submit();
        });

        // دالة الحذف النهائي
        let deleteForm = null;
        document.querySelectorAll('.js-delete-bracelet').forEach(btn => {
            btn.addEventListener('click', function() {
                deleteForm = this.closest('form');
                document.getElementById('deleteIdText').innerText = "Bracelet #" + this.dataset.id;
                openAdminModal('braceletDeleteModal');
            });
        });
        document.getElementById('confirmBraceletDeleteBtn').addEventListener('click', function() {
            if (deleteForm) deleteForm.submit();
        });
    </script>
</body>
</html>