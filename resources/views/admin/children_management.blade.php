<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Children Management - Taif Project') }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        * { box-sizing: border-box; }

        body {
            margin: 0;
            display: flex;
            background-color: var(--page-bg);
            font-family: 'Public Sans', Arial, sans-serif;
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

        .admin-page-title { margin-bottom: 0; font-weight: 700; }
        .admin-page-subtitle { color: #6b7280; }

        .admin-status-wrapper { display: flex; align-items: center; }
        .admin-status-text { margin-inline-end: 1rem; text-align: end; }
        .admin-status-title { font-size: 13px; font-weight: 700; }
        .admin-online-status { color: #198754; font-size: 11px; }

        .admin-logout-button {
            width: 38px; height: 38px; border: none; background: white;
            border-radius: 50%; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            display: flex; align-items: center; justify-content: center;
        }

        .children-management-card {
            width: 100%; margin-top: 20px; overflow-x: auto;
            background: white; border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .children-table-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1rem; border-bottom: 1px solid #e5e7eb;
        }

        .children-table-title { margin: 0; font-weight: 700; color: #111827; }

        .children-search-wrapper { position: relative; width: 250px; }
        .children-search-icon { position: absolute; inset-inline-start: 12px; top: 10px; color: #6b7280; }
        .children-search-input { padding-inline-start: 2.5rem; background: #f8fafc; }

        .children-table { width: 100%; border-collapse: collapse; table-layout: auto; }
        .children-table th, .children-table td { padding: 15px 20px; text-align: start; border-bottom: 1px solid #f1f5f9; }
        .children-table th {
            background-color: #f8fafd; color: var(--muted-text);
            font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;
        }

        .children-table th:nth-child(1), .children-table td:nth-child(1) { width: 25%; }
        .children-table th:nth-child(2), .children-table td:nth-child(2) { width: 15%; }
        .children-table th:nth-child(3), .children-table td:nth-child(3) { width: 20%; }
        .children-table th:nth-child(4), .children-table td:nth-child(4) { width: 10%; }
        .children-table th:nth-child(5), .children-table td:nth-child(5) { width: 10%; }
        .children-table th:nth-child(6), .children-table td:nth-child(6) { width: 10%; text-align: center; }
        .children-table th:nth-child(7), .children-table td:nth-child(7) { width: 10%; text-align: center; }

        .children-info-cell { display: flex; align-items: center; padding-inline-start: 1rem; }
        .children-profile-image {
            width: 40px; height: 40px; margin-inline-end: 12px; display: flex;
            align-items: center; justify-content: center; overflow: hidden;
            background: #edf2f7; color: var(--sidebar-bg); border-radius: 10px;
        }
        .children-profile-image img { width: 100%; height: 100%; object-fit: cover; }
        .children-full-name { color: #111827; font-weight: 700; }

        .children-specialization-badge {
            display: inline-block; padding: 4px 12px; border-radius: 6px;
            font-size: 11px; font-weight: 600;
        }

        .children-action-buttons { display: flex; justify-content: center; align-items: center; gap: 8px; }
        .children-action-button {
            width: 32px; height: 32px; display: flex; justify-content: center; align-items: center;
            border: none; border-radius: 6px; cursor: pointer; font-size: 13px; transition: 0.2s;
        }
        .children-action-view { background: #e0f2fe; color: #0ea5e9; }
        .children-action-edit { background: #e0e7ff; color: #4f46e5; }
        .children-action-button:hover { transform: translateY(-1px); opacity: 0.9; }

        /* Modals */
        .admin-modal-overlay {
            position: fixed; top: 0; left: 0; z-index: 2000; width: 100%; height: 100%;
            display: none; justify-content: center; align-items: center;
            background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(5px);
            animation: modalFadeIn 0.3s ease;
        }
        @keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }

        .admin-modal-box {
            position: relative; width: 420px; max-width: 95%; overflow: hidden;
            background: white; border-radius: 25px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        .admin-modal-header {
            display: flex; justify-content: center; align-items: center; gap: 10px;
            padding: 20px; color: white; font-size: 18px; font-weight: 800; text-align: center;
        }
        .view-children-modal .admin-modal-header { background: var(--taif-green); }
        .edit-children-modal .admin-modal-header { background: var(--taif-orange); }

        .admin-modal-close {
            position: absolute; top: 15px; inset-inline-end: 15px; color: rgba(255, 255, 255, 0.8);
            font-size: 22px; cursor: pointer; transition: 0.2s;
        }
        .admin-modal-close:hover { color: white; transform: scale(1.1); }

        .admin-modal-body { padding: 30px 25px; }

        .view-children-large-image {
            width: 80px; height: 80px; margin: 0 auto 1rem; overflow: hidden;
            background: #f0fff4; border-radius: 12px;
        }
        .view-children-large-image img { width: 100%; height: 100%; object-fit: cover; }
        .view-children-name { margin-bottom: 5px; color: #1f5b87; font-size: 24px; font-weight: 700; }
        .view-children-details-box { padding: 15px; background: #f8fafc; border-radius: 15px; }
        .view-children-info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .view-children-info-row:last-child { border: none; }
        .view-children-info-label { color: var(--muted-text); font-weight: 700; }
        .view-children-info-value { color: var(--dark-text); font-weight: 600; text-align: end; }

        .children-edit-form-group { margin-bottom: 20px; text-align: start; }
        .children-edit-form-group label { display: block; margin-bottom: 8px; color: #1f5b87; font-size: 14px; font-weight: 700; }
        .children-edit-input {
            width: 100%; height: 45px; padding: 0 15px; background: #f8fafc;
            border: 2px solid var(--light-border); border-radius: 12px; font-size: 15px;
        }
        .children-edit-input:focus { outline: none; background: white; border-color: var(--taif-orange); }
        .children-edit-save-button {
            width: 100%; padding: 12px; background: var(--taif-orange); color: white;
            border: none; border-radius: 12px; font-size: 16px; font-weight: 800; cursor: pointer; transition: 0.3s;
        }
        .children-edit-save-button:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(246, 173, 85, 0.3); }

        @media (max-width: 992px) {
            .admin-sidebar { width: 75px; }
            .admin-sidebar-title, .admin-sidebar-link span { display: none; }
            .admin-main-content { width: calc(100% - 75px); margin-inline-start: 75px; }
        }
    </style>
</head>
<body>
    @include('admin.partials.sidebar')

    <div class="admin-main-content text-start">
        <div class="admin-page-header">
            <div>
                <h4 class="admin-page-title">{{ __('Children Management') }}</h4>
                <small class="admin-page-subtitle">{{ __('Manage all children in the system') }}</small>
            </div>
            <div class="admin-status-wrapper">
                <div class="admin-status-text">
                    <div class="admin-status-title">{{ __('Admin Panel') }}</div>
                    <small class="admin-online-status"><i class="fas fa-circle me-1"></i> {{ __('Online') }}</small>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="admin-logout-button">
                        <i class="fas fa-sign-out-alt text-danger"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="children-management-card">
            <div class="children-table-header">
                <h6 class="children-table-title">{{ __('Children Directory') }}</h6>
                <form action="{{ route('admin.children.index') }}" method="GET" id="searchForm" class="children-search-wrapper">
                    <i class="fas fa-search children-search-icon"></i>
                    <input type="text" name="search" id="childrenSearchInput" value="{{ request('search') }}" class="form-control form-control-sm children-search-input" placeholder="{{ __('Search child name...') }}">
                </form>
            </div>

            <table id="childrenManagementTable" class="children-table">
                <thead>
                    <tr>
                        <th>{{ __('Child Name') }}</th>
                        <th>{{ __('Bracelet') }}</th>
                        <th>{{ __('Parent Name') }}</th>
                        <th>{{ __('Autism Level') }}</th>
                        <th>{{ __('Gender') }}</th>
                        <th style="text-align: center;">{{ __('Birth date') }}</th>
                        <th style="text-align: center;">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody id="childrenTableBody">
                    @forelse($children as $child)
                        @php
                            $user = $child->parentProfile?->user;
                            $childFullName = $child->name; 
                            $parentName = $user ? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) : __('No Parent');
                            $childImage = ($user && $user->profile_image) ? asset('storage/' . $user->profile_image) : asset('images/default-user.png');
                            
                            // تجهيز الرقم التسلسلي للإسوارة
                            $braceletSerial = null;
                            if($child->bracelet) {
                                $braceletSerial = !empty($child->bracelet->serial_number) ? $child->bracelet->serial_number : 'ID: ' . $child->bracelet->id;
                            }
                        @endphp

                        <tr>
                            <td>
                                <div class="children-info-cell">
                                    <div class="children-profile-image">
                                        <img src="{{ $childImage }}" alt="Children Profile">
                                    </div>
                                    <div>
                                        <div class="children-full-name">{{ $childFullName }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($braceletSerial)
                                    <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: bold; border: 1px solid #bbf7d0;">
                                        <i class="fas fa-microchip me-1"></i> {{ $braceletSerial }}
                                    </span>
                                @else
                                    <span style="background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: bold; border: 1px solid #e2e8f0;">
                                        {{ __('Unlinked') }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="children-specialization-badge" style="background: #e6fffa; color: #276749;">
                                    {{ $parentName }}
                                </span>
                            </td>

                            <td>{{ __($child->autism_level ?? 'N/A') }}</td>
                            <td>{{ __($child->gender ?? 'N/A') }}</td>

                            <td dir="ltr" style="text-align: center;">
                                {{ $child->birth_date ? \Carbon\Carbon::parse($child->birth_date)->format('Y-m-d') : 'N/A' }}
                            </td>

                            <td>
                                <div class="children-action-buttons">
                                    <button type="button" class="children-action-button children-action-view js-view-children"
                                        data-name="{{ $childFullName }}"
                                        data-parent-name="{{ $parentName }}"
                                        data-autism-level="{{ __($child->autism_level ?? 'N/A') }}"
                                        data-gender="{{ __($child->gender ?? 'N/A') }}"
                                        data-birth-date="{{ $child->birth_date ? \Carbon\Carbon::parse($child->birth_date)->format('Y-m-d') : __('N/A') }}"
                                        data-image="{{ $childImage }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <button type="button" class="children-action-button children-action-edit js-edit-children"
                                        data-id="{{ $child->id }}"
                                        data-name="{{ $child->name ?? '' }}"
                                        data-autism-level="{{ $child->autism_level ?? '' }}"
                                        data-gender="{{ $child->gender ?? '' }}"
                                        data-birth-date="{{ $child->birth_date ? \Carbon\Carbon::parse($child->birth_date)->format('Y-m-d') : '' }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                {{ __('No children found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="p-3">
                {{ $children->links() }}
            </div>
        </div>
    </div>

    <div id="childrenViewModal" class="admin-modal-overlay view-children-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-id-card"></i> {{ __('Children Profile') }}
            </div>
            <span class="admin-modal-close" onclick="closeAdminModal('childrenViewModal')">&times;</span>
            <div class="admin-modal-body text-center">
                <div class="view-children-large-image">
                    <img id="viewChildrenImage" src="{{ asset('images/default-user.png') }}" alt="children Image">
                </div>
                <h3 id="viewchildrenNameTitle" class="view-children-name"></h3>
                <div class="view-children-details-box">
                    <div class="view-children-info-row">
                        <span class="view-children-info-label">{{ __('Child name') }}</span>
                        <span class="view-children-info-value" id="viewChildrenName"></span>
                    </div>
                    <div class="view-children-info-row">
                        <span class="view-children-info-label">{{ __('Parent name') }}</span>
                        <span class="view-children-info-value" id="viewParentName"></span>
                    </div>
                    <div class="view-children-info-row">
                        <span class="view-children-info-label">{{ __('Autism Level') }}</span>
                        <span class="view-children-info-value" id="viewAutismLevel"></span>
                    </div>
                    <div class="view-children-info-row">
                        <span class="view-children-info-label">{{ __('Gender') }}</span>
                        <span class="view-children-info-value" id="viewGender"></span>
                    </div>
                    <div class="view-children-info-row">
                        <span class="view-children-info-label">{{ __('Birth date') }}</span>
                        <span class="view-children-info-value" id="viewBirthDate" dir="ltr" style="text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="childrenEditModal" class="admin-modal-overlay edit-children-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-user-edit"></i> {{ __('Edit Information') }}
            </div>
            <span class="admin-modal-close" onclick="closeAdminModal('childrenEditModal')">&times;</span>
            <div class="admin-modal-body">
                <form id="childrenUpdateForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="children-edit-form-group">
                        <label>{{ __('Child ID') }}</label>
                        <input type="text" id="editchildId" name="id" class="children-edit-input" readonly>
                    </div>
                    <div class="children-edit-form-group">
                        <label>{{ __('Child name') }}</label>
                        <input type="text" id="editchildrenName" name="name" class="children-edit-input">
                    </div>
                    <div class="children-edit-form-group">
                        <label>{{ __('Autism Level') }}</label>
                        <select id="editAutismLevel" name="autism_level" class="children-edit-input">
                            <option value="">{{ __('Select Level') }}</option>
                            <option value="Mild">{{ __('Mild') }}</option>
                            <option value="Moderate">{{ __('Moderate') }}</option>
                            <option value="Severe">{{ __('Severe') }}</option>
                        </select>
                    </div>
                    <div class="children-edit-form-group">
                        <label>{{ __('Gender') }}</label>
                        <select id="editGender" name="gender" class="children-edit-input">
                            <option value="">{{ __('Select Gender') }}</option>
                            <option value="Male">{{ __('Male') }}</option>
                            <option value="Female">{{ __('Female') }}</option>
                        </select>
                    </div>
                    <div class="children-edit-form-group">
                        <label>{{ __('Birth date') }}</label>
                        <input type="date" id="editBirthDate" name="birth_date" class="children-edit-input">
                    </div>
                    <button type="submit" class="children-edit-save-button">
                        {{ __('Save Changes') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

<script>
    let searchTimeout = null;
    const childrenSearchInput = document.getElementById('childrenSearchInput');
    const searchForm = document.getElementById('searchForm');

    if (childrenSearchInput) {
        childrenSearchInput.addEventListener('keyup', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (searchForm) searchForm.submit();
            }, 500);
        });
    }

    document.querySelectorAll('.js-view-children').forEach(function (button) {
        button.addEventListener('click', function () {
            const d = this.dataset; 
            document.getElementById('viewchildrenNameTitle').innerText = d.name || '{{ __('N/A') }}';
            document.getElementById('viewChildrenName').innerText = d.name || '{{ __('N/A') }}';
            document.getElementById('viewParentName').innerText = d.parentName || '{{ __('N/A') }}';
            document.getElementById('viewAutismLevel').innerText = d.autismLevel || '{{ __('N/A') }}';
            document.getElementById('viewGender').innerText = d.gender || '{{ __('N/A') }}';
            document.getElementById('viewBirthDate').innerText = d.birthDate || '{{ __('N/A') }}';
            
            if(document.getElementById('viewChildrenImage')) {
                document.getElementById('viewChildrenImage').src = d.image || '';
            }
            document.getElementById('childrenViewModal').style.display = 'flex';
        });
    });

   document.querySelectorAll('.js-edit-children').forEach(function (button) {
    button.addEventListener('click', function () {
        const d = this.dataset;
        try {
            const form = document.getElementById('childrenUpdateForm');
            if(form) form.action = '/admin/children/' + d.id;
            
            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.value = val || '';
            };

            setVal('editchildId', d.id); 
            setVal('editchildrenName', d.name);
            setVal('editAutismLevel', d.autismLevel);
            setVal('editGender', d.gender);
            setVal('editBirthDate', d.birthDate);

            document.getElementById('childrenEditModal').style.display = 'flex';
        } catch (error) {
            console.error("Error opening edit modal:", error);
        }
    });
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