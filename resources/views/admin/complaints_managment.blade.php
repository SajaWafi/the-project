<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Complaints Management - Taif Project') }}</title>

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
            --light-border: #edf2f7;
            --muted-text: #718096;
            --dark-text: #2d3748;
            --danger-red: #ef4444;
            --taif-blue: #2c5282;
        }

        body { 
            display: block; 
            background: var(--page-bg); 
            min-height: 100vh; 
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* 💡 استخدام الخصائص المنطقية للاتجاهات */
        .complaints-container {
            padding: 2rem;
            margin-inline-start: 260px; 
            width: calc(100% - 260px);
            min-height: 100vh;
            transition: all 0.3s;
        }

        .complaints-header {
            background: white;
            padding: 1.5rem;
            border-radius: 14px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }

        .complaints-title {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .filter-select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: white;
            font-size: 13px;
            color: #4a5568;
            outline: none;
            transition: .2s;
        }

        .filter-select:focus {
            border-color: var(--taif-orange);
            box-shadow: 0 0 0 3px rgba(246, 173, 85, 0.1);
        }

        .complaints-subtitle {
            color: #6b7280;
            font-size: 14px;
        }

        .complaints-card {
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .complaints-table-header {
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #edf2f7;
        }

        .complaints-table-title {
            margin: 0;
            font-weight: 700;
        }

        .complaints-search {
            width: 260px;
        }

        .complaints-table {
            width: 100%;
            border-collapse: collapse;
        }

        .complaints-table th,
        .complaints-table td {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            text-align: start;
        }

        .complaints-table th {
            background: #f8fafc;
            font-size: 12px;
            color: var(--muted-text);
            text-transform: uppercase;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-image {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            overflow: hidden;
            background: #edf2f7;
        }

        .user-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-name {
            font-weight: 700;
            color: #111827;
        }

        .user-role {
            font-size: 12px;
            color: #6b7280;
        }

        .role-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .role-doctor { background: #dbeafe; color: #2563eb; }
        .role-parent { background: #dcfce7; color: #15803d; }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .status-pending { background: #fef3c7; color: #d97706; }
        .status-resolved { background: #dcfce7; color: #15803d; }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: .2s;
        }

        .view-btn { background: #dbeafe; color: #2563eb; }
        .delete-btn { background: #fee2e2; color: #dc2626; }
        .update-btn { background: #fef3c7; color: #d97706; }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-box {
            width: 500px;
            max-width: 95%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
        }

        .modal-header {
            background: var(--sidebar-bg);
            color: white;
            padding: 18px;
            text-align: center;
            font-weight: 700;
            position: relative;
        }

        .close-modal {
            position: absolute;
            inset-inline-end: 20px;
            top: 15px;
            cursor: pointer;
            font-size: 22px;
        }

        .modal-body {
            padding: 25px;
        }

        .complaint-message {
            background: #f8fafc;
            padding: 15px;
            border-radius: 12px;
            line-height: 1.8;
            color: #374151;
        }
    </style>
</head>

<body>
    @include('admin.partials.sidebar')

    <div class="complaints-container">
        <div class="complaints-header">
            <div>
                <h3 class="complaints-title">{{ __('Complaints Management') }}</h3>
                <div class="complaints-subtitle">
                    {{ __('Manage all complaints from doctors and parents') }}
                </div>
            </div>
        </div>

        <div class="complaints-card">
            <div class="complaints-table-header">
                <h6 class="complaints-table-title">
                    {{ __('Complaints Directory') }}
                </h6>

                <form action="{{ route('admin.complaints.index') }}" method="GET" id="searchForm" style="display:flex; gap:15px; margin-bottom:0; align-items: center; justify-content: flex-end;">
                    
                    <select name="role" id="roleFilter" class="filter-select form-control form-control-sm" style="width: auto;">
                        <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>{{ __('All Roles') }}</option>
                        <option value="doctor" {{ request('role') == 'doctor' ? 'selected' : '' }}>{{ __('Doctor') }}</option>
                        <option value="parent" {{ request('role') == 'parent' ? 'selected' : '' }}>{{ __('Parent') }}</option>
                    </select>

                  <select name="category" id="categoryFilter" class="filter-select form-control form-control-sm" style="width: auto;">
                    <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>{{ __('All Categories') }}</option>
                    <option value="system_error_or_bug" {{ request('category') == 'system_error_or_bug' ? 'selected' : '' }}>{{ __('System Error or Bug') }}</option>
                    <option value="technical_issue" {{ request('category') == 'technical_issue' ? 'selected' : '' }}>{{ __('Technical Issue') }}</option>
                    <option value="parent_dispute" {{ request('category') == 'parent_dispute' ? 'selected' : '' }}>{{ __('Issue regarding a Parent') }}</option>
                    <option value="doctor_issue" {{ request('category') == 'doctor_issue' ? 'selected' : '' }}>{{ __('Issue regarding a Doctor') }}</option>
                    <option value="general_suggestion" {{ request('category') == 'general_suggestion' ? 'selected' : '' }}>{{ __('General Suggestion') }}</option>
                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                </select>

                    <input
                        type="text"
                        name="search"
                        id="complaintSearch"
                        class="form-control complaints-search form-control-sm"
                        placeholder="{{ __('Search name, email, or message...') }}"
                        value="{{ request('search') }}"
                        style="width: 250px;"
                    >
                </form>
            </div>

            <table class="complaints-table">
                <thead>
                    <tr>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Complaint') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th style="text-align:center;">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody id="complaintsTableBody">
                    @forelse($complaints as $complaint)
                        @php
                            $user = $complaint->user;
                            $fullName = $user ? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) : __('Unknown User');
                            $role = $user->role ?? 'parent';
                            $image = $user && $user->profile_image ? asset('storage/' . $user->profile_image) : asset('images/default-user.png');
                            
                            // تحويل الفئة لنص مقروء مع الترجمة
                            $categoryName = match($complaint->category) {
                                'system_error_or_bug' => __('System Error or Bug'),
                                'technical_issue' => __('Technical Issue'),
                                'parent_dispute' => __('Issue regarding a Parent'),
                                'doctor_issue' => __('Issue regarding a Doctor'),
                                'general_suggestion' => __('General Suggestion'),
                                'other' => __('Other'),
                                default => ucwords(str_replace('_', ' ', $complaint->category)),
                            };
                        @endphp
                        
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-image">
                                        <img src="{{ $image }}">
                                    </div>
                                    <div>
                                        <div class="user-name">{{ $fullName }}</div>
                                        <div class="user-role">{{ $user->email ?? __('No Email') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge {{ $role == 'doctor' ? 'role-doctor' : 'role-parent' }}">
                                    {{ __($role == 'doctor' ? 'Doctor' : 'Parent') }}
                                </span>
                            </td>
                            <td>{{ $categoryName }}</td>
                            <td>{{ Str::limit($complaint->message, 30) }}</td>
                            <td>
                                <span class="status-badge {{ $complaint->status == 'resolved' ? 'status-resolved' : 'status-pending' }}">
                                    {{ __($complaint->status) }}
                                </span>
                            </td>
                            <td>{{ $complaint->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view-btn js-view-complaint"
                                        data-user="{{ $fullName }}"
                                        data-role="{{ __($role == 'doctor' ? 'Doctor' : 'Parent') }}"
                                        data-category="{{ $categoryName }}"
                                        data-message="{{ $complaint->message }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <form action="{{ route('admin.complaints.destroy', $complaint->id) }}" method="POST" style="margin: 0;">
                                        @csrf @method('DELETE')
                                        <button class="action-btn delete-btn" title="{{ __('Delete Complaint') }}"><i class="fas fa-trash"></i></button>
                                    </form>

                                    <button type="button" class="action-btn update-btn" title="{{ __('Update Status') }}"
                                        data-id="{{ $complaint->id }}"
                                        data-status="{{ $complaint->status }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                {{ __('No complaints found matching your criteria.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($complaints->count())
                <div class="admin-pagination-wrapper" style="padding: 15px; border-top: 1px solid #eee; display: flex; justify-content: center; background-color: #fff; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                    {{ $complaints->links() }}
                </div>
            @endif
        </div>
    </div>

    <div class="modal-overlay" id="complaintModal">
        <div class="modal-box">
            <div class="modal-header">
                {{ __('Complaint Details') }}
                <span class="close-modal" onclick="closeComplaintModal()">&times;</span>
            </div>
            <div class="modal-body text-start">
                <h5 id="modalUserName"></h5>
                <div class="mb-3"><span id="modalUserRole" class="role-badge"></span></div>
                <div class="complaint-category" id="modalComplaintCategory" style="font-weight: bold; margin-bottom: 10px; color: var(--taif-blue);"></div>
                <div class="complaint-message" id="modalComplaintMessage" style="background: #f8fafc; padding: 15px; border-radius: 8px; border-inline-start: 4px solid var(--taif-orange);"></div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="updateModal">
        <div class="modal-box">
            <div class="modal-header">
                {{ __('Update Complaint Status') }}
                <span class="close-modal" onclick="closeUpdateModal()">&times;</span>
            </div>
            <div class="modal-body text-start">
                <input type="hidden" id="complaintId">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('Status') }}</label>
                    <select id="complaintStatus" class="form-control">
                        <option value="pending">{{ __('Pending') }}</option>
                        <option value="resolved">{{ __('Resolved') }}</option>
                    </select>
                </div>
                <button class="btn btn-primary w-100" onclick="saveStatus()" style="background-color: var(--taif-blue); border: none;">
                    {{ __('Save Changes') }}
                </button>
            </div>
        </div>
    </div>

    <script>
        // 1. الفلترة الفورية عبر السيرفر
        let searchTimeout = null;
        const searchForm = document.getElementById('searchForm');
        
        document.getElementById('complaintSearch')?.addEventListener('keyup', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => { if (searchForm) searchForm.submit(); }, 500);
        });

        document.getElementById('roleFilter')?.addEventListener('change', () => { if(searchForm) searchForm.submit(); });
        document.getElementById('categoryFilter')?.addEventListener('change', () => { if(searchForm) searchForm.submit(); });

        // 2. دوال عرض التفاصيل (View Modal)
        document.querySelectorAll('.js-view-complaint').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('modalUserName').innerText = this.dataset.user || '{{ __('Unknown User') }}';
                document.getElementById('modalUserRole').innerText = this.dataset.role || '{{ __('N/A') }}';
                document.getElementById('modalComplaintMessage').innerText = this.dataset.message || '{{ __('No details provided.') }}';
                document.getElementById('modalComplaintCategory').innerText = this.dataset.category || '{{ __('N/A') }}';
                
                // تحديد لون الـ Badge حسب الدور في نافذة العرض
                const roleSpan = document.getElementById('modalUserRole');
                if(this.dataset.role.toLowerCase() === '{{ mb_strtolower(__('Doctor')) }}' || this.dataset.role.toLowerCase() === 'doctor') {
                    roleSpan.className = 'role-badge role-doctor';
                } else {
                    roleSpan.className = 'role-badge role-parent';
                }

                document.getElementById('complaintModal').style.display = 'flex';
            });
        });

        function closeComplaintModal() {
            document.getElementById('complaintModal').style.display = 'none';
        }

        // 3. تجهيز نافذة التعديل السريع (Update Modal)
        document.querySelectorAll('.update-btn').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('complaintId').value = this.dataset.id;
                document.getElementById('complaintStatus').value = this.dataset.status;
                document.getElementById('updateModal').style.display = 'flex';
            });
        });

        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        // 4. التحديث في الخلفية (AJAX Fetch API) 
        function saveStatus() {
            const id = document.getElementById('complaintId').value;
            const status = document.getElementById('complaintStatus').value;

            fetch(`/admin/complaints/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload(); 
                } else {
                    alert("{{ __('Error: Could not update status.') }}");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("{{ __('An error occurred. Check the console.') }}");
            });
        }

        window.onclick = function (event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.style.display = 'none';
            }
        };
    </script>
</body>
</html>