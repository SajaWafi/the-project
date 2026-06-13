<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bracelets Management - Taif Project</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        /* (نفس الستايل الاحترافي بتاعك) */
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

        * { box-sizing: border-box; }
        body { margin: 0; display: flex; background-color: var(--page-bg); font-family: 'Public Sans', Arial, sans-serif; }
        .admin-main-content { width: calc(100% - 260px); margin-left: 260px; padding: 2rem; }
        .admin-page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding: 1.2rem 1.5rem; background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03); }
        .admin-page-title { margin-bottom: 0; font-weight: 700; }
        .admin-page-subtitle { color: #6b7280; }
        .bracelet-management-card { width: 100%; margin-top: 20px; overflow-x: auto; background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); }
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
        .bracelet-action-button:hover { transform: translateY(-1px); opacity: 0.9; }
        .add-bracelet-button { height: 34px; padding: 0 14px; display: flex; align-items: center; gap: 7px; border: none; border-radius: 10px; background: var(--taif-orange); color: white; font-size: 13px; font-weight: 800; cursor: pointer; white-space: nowrap; }
        .add-bracelet-button:hover { opacity: 0.9; transform: translateY(-1px); }
        .admin-modal-overlay { position: fixed; top: 0; left: 0; z-index: 2000; width: 100%; height: 100%; display: none; justify-content: center; align-items: center; background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(5px); animation: modalFadeIn 0.3s ease; }
        @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }
        .admin-modal-box { position: relative; width: 420px; max-width: 95%; background: white; border-radius: 25px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15); animation: modalSlideUp 0.4s ease; overflow: hidden; }
        @keyframes modalSlideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .admin-modal-header { display: flex; justify-content: center; align-items: center; gap: 10px; padding: 20px; color: white; font-size: 18px; font-weight: 800; text-align: center; }
        .view-modal .admin-modal-header { background: var(--taif-green); }
        .edit-modal .admin-modal-header { background: var(--taif-orange); }
        .delete-modal .admin-modal-header { background: var(--danger-red); }
        .create-modal .admin-modal-header { background: var(--taif-blue); }
        .admin-modal-close { position: absolute; top: 15px; right: 15px; color: rgba(255, 255, 255, 0.8); font-size: 22px; cursor: pointer; transition: 0.2s; }
        .admin-modal-close:hover { color: white; transform: scale(1.1); }
        .admin-modal-body { padding: 30px 25px; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; margin-bottom: 8px; color: #1f5b87; font-size: 14px; font-weight: 700; }
        .form-input { width: 100%; height: 45px; padding: 0 15px; background: #f8fafc; border: 2px solid var(--light-border); border-radius: 12px; font-size: 15px; }
        .form-input:focus { outline: none; background: white; border-color: var(--taif-orange); }
        .modal-save-btn { width: 100%; padding: 12px; background: var(--taif-orange); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 800; cursor: pointer; transition: 0.3s; }
        .modal-save-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(246, 173, 85, 0.3); }
        .details-box { padding: 15px; background: #f8fafc; border-radius: 15px; margin-top: 15px; }
        .details-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .details-row:last-child { border: none; }
        .details-label { color: var(--muted-text); font-weight: 700; }
        .details-value { color: var(--dark-text); font-weight: 600; }
        .large-icon { width: 80px; height: 80px; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; background: #f0fff4; color: var(--taif-green); border-radius: 12px; font-size: 35px; }
        .delete-warning-icon { width: 72px; height: 72px; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; background: #fee2e2; color: #dc2626; border-radius: 50%; font-size: 30px; }
        .delete-title { margin-bottom: 10px; color: #111827; font-size: 22px; font-weight: 800; }
        .delete-message { margin-bottom: 24px; color: #6b7280; font-size: 14px; line-height: 1.6; }
        .delete-actions { display: flex; justify-content: center; gap: 12px; }
        .delete-btn, .cancel-btn { min-width: 110px; height: 42px; border: none; border-radius: 12px; font-size: 14px; font-weight: 800; cursor: pointer; }
        .cancel-btn { background: #e5e7eb; color: #374151; }
        .delete-btn { background: var(--danger-red); color: white; }
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
                        <input type="text" id="braceletSearchInput" class="form-control form-control-sm bracelet-search-input" placeholder="Search ID...">
                    </div>
                </div>
            </div>

            <table id="braceletManagementTable" class="bracelet-table">
                <thead>
                    <tr>
                        <th>Device Info</th>
                        <th>Assigned Child</th>
                        <th>Last Sync</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>

                <tbody id="braceletTableBody">
                   <!--لو حالة السوار active نعطوها كلاس connected (أخضر)، ولو inactive نعطوها disconnected -->
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
                                    <button type="button" class="bracelet-action-button action-view js-view-bracelet"
                                        data-id="{{ $bracelet->id }}"
                                        data-child="{{ $childName }}"
                                        data-status="{{ $statusText }}"
                                        data-sync="{{ $bracelet->last_sync_at ?? 'Never' }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button type="button" class="bracelet-action-button action-edit js-edit-bracelet"
                                        data-id="{{ $bracelet->id }}"
                                        data-status="{{ $bracelet->status }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('admin.bracelets.destroy', $bracelet->id ?? 0) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="bracelet-action-button action-delete js-delete-bracelet"
                                            data-id="{{ $bracelet->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
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
                    <div class="form-group text-center">
                        <p style="color: #64748b; font-size: 14px; margin-bottom: 20px;">
                            The Bracelet ID will be generated automatically. Just set the initial status.
                        </p>
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
            <div class="admin-modal-header">
                <i class="fas fa-microchip"></i> Device Details
            </div>
            <span class="admin-modal-close" onclick="closeAdminModal('braceletViewModal')">&times;</span>

            <div class="admin-modal-body text-center">
                <div class="large-icon">
                    <i class="fas fa-link"></i>
                </div>

                <h3 id="viewId" style="color: #1f5b87; font-weight: 700; margin-bottom: 5px;"></h3>
                <span id="viewStatus" class="status-badge" style="margin-bottom: 20px;"></span>

                <div class="details-box">
                    <div class="details-row">
                        <span class="details-label">Assigned Child</span>
                        <span class="details-value" id="viewChild"></span>
                    </div>
                    <div class="details-row">
                        <span class="details-label">Last Synchronization</span>
                        <span class="details-value" id="viewSync"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="braceletEditModal" class="admin-modal-overlay edit-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-edit"></i> Edit Device
            </div>
            <span class="admin-modal-close" onclick="closeAdminModal('braceletEditModal')">&times;</span>

            <div class="admin-modal-body">
                <form id="braceletUpdateForm" method="POST">
                    @csrf
                    @method('PUT')

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

    <div id="braceletDeleteModal" class="admin-modal-overlay delete-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-trash"></i> Delete Device
            </div>
            <span class="admin-modal-close" onclick="closeAdminModal('braceletDeleteModal')">&times;</span>

            <div class="admin-modal-body text-center">
                <div class="delete-warning-icon">
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
<script>
        // ---------------------------------------------------------
        // 1. دوال التحكم بالنوافذ المنبثقة (Global Modal Controllers)
        // ---------------------------------------------------------
        //  استخدام دوال عامة لفتح وإغلاق المودال يقلل من تكرار الكود
        function openAdminModal(modalId) { document.getElementById(modalId).style.display = 'flex'; }
        function closeAdminModal(modalId) { document.getElementById(modalId).style.display = 'none'; }
        
        // 💡 إغلاق المودال عند النقر في المساحة الفارغة حوله
        window.onclick = function (event) { if (event.target.classList.contains('admin-modal-overlay')) event.target.style.display = 'none'; };

        // ---------------------------------------------------------
        // 2. الفلترة الفورية (Client-Side Search)
        // ---------------------------------------------------------
        const searchInput = document.getElementById('braceletSearchInput');
        const statusFilter = document.getElementById('braceletStatusFilter');

        function filterTable() {
            // سحب قيمة البحث وتوحيد حالة الأحرف لتجنب أخطاء Case-Sensitivity
            const term = searchInput ? searchInput.value.toLowerCase().trim() : '';
            // سحب حالة الاتصال المختارة
            const selectedStatus = statusFilter ? statusFilter.value : 'all';
            
            // جلب جميع الصفوف في الجدول
            const rows = document.querySelectorAll('#braceletTableBody tr');

            rows.forEach(row => {
                // تجاوز الصفوف الفارغة أو صف رسالة الخطأ
                if (!row.dataset.status) return;
                
                const text = row.innerText.toLowerCase(); // النص الظاهر
                const rowStatus = row.dataset.status; // الحالة المخفية في data-status
                
                // 💡 [Logic Gate]: التحقق من تطابق النص والحالة
                const matchesSearch = text.includes(term);
                const matchesStatus = selectedStatus === 'all' || rowStatus === selectedStatus;
                
                // إظهار الصف فقط إذا تحقق الشرطان (AND logic)
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }

        // ربط أحداث الكتابة والتغيير بدالة الفلترة
        if(searchInput) searchInput.addEventListener('keyup', filterTable);
        if(statusFilter) statusFilter.addEventListener('change', filterTable);

        // ---------------------------------------------------------
        // 3. عرض التفاصيل (View Details Modal)
        // ---------------------------------------------------------
        document.querySelectorAll('.js-view-bracelet').forEach(btn => {
            btn.addEventListener('click', function() {
                // 💡 [DOM Manipulation]: سحب البيانات من الـ data-attributes الخاصة بالزر وحقنها في المودال
                document.getElementById('viewId').innerText = "Bracelet #" + this.dataset.id;
                document.getElementById('viewChild').innerText = this.dataset.child;
                document.getElementById('viewSync').innerText = this.dataset.sync;
                
                const statusSpan = document.getElementById('viewStatus');
                statusSpan.innerText = this.dataset.status;
                
                // 💡 [Dynamic Styling]: تغيير لون كلاس الحالة (أخضر للنشط، رصاصي لغير النشط)
                statusSpan.className = 'status-badge ' + (this.dataset.status === 'Connected' ? 'status-connected' : 'status-disconnected');

                openAdminModal('braceletViewModal');
            });
        });

        // ---------------------------------------------------------
        // 4. دالة التعديل (Dynamic Form Action)
        // ---------------------------------------------------------
        document.querySelectorAll('.js-edit-bracelet').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('editStatus').value = this.dataset.status;
                
                // 💡 [Dynamic Route]: تركيب رابط الـ PUT برمجياً بناءً على ID السوار المختار
                document.getElementById('braceletUpdateForm').action = '/admin/bracelets/' + this.dataset.id;
                openAdminModal('braceletEditModal');
            });
        });

        // ---------------------------------------------------------
        // 5. دالة الحذف (Safe Deletion)
        // ---------------------------------------------------------
        let deleteForm = null; // ذاكرة مؤقتة لحفظ الـ Form
        
        document.querySelectorAll('.js-delete-bracelet').forEach(btn => {
            btn.addEventListener('click', function() {
                // 💡 الصعود في شجرة הـ DOM لالتقاط نموذج الحذف الخاص بهذا الزر تحديداً
                deleteForm = this.closest('form');
                document.getElementById('deleteIdText').innerText = "Bracelet #" + this.dataset.id;
                openAdminModal('braceletDeleteModal');
            });
        });

        document.getElementById('confirmBraceletDeleteBtn').addEventListener('click', function() {
            // تنفيذ الإرسال برمجياً فقط بعد موافقة الأدمن
            if (deleteForm) deleteForm.submit();
        });
    </script>
</body>
</html>