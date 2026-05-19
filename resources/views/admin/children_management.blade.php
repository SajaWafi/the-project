<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>children Management - Taif Project</title>

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


        /* =========================
           Admin Main Content
        ========================= */

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

        /* =========================
           Children Table Section
        ========================= */

        .children-management-card {
            width: 100%;
            margin-top: 20px;
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .children-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .children-table-title {
            margin: 0;
            font-weight: 700;
            color: #111827;
        }

        .children-search-wrapper {
            position: relative;
            width: 250px;
        }

        .children-search-icon {
            position: absolute;
            left: 12px;
            top: 10px;
            color: #6b7280;
        }

        .children-search-input {
            padding-left: 2.5rem;
            background: #f8fafc;
        }

        .children-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        .children-table th,
        .children-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }

        .children-table th {
            background-color: #f8fafd;
            color: var(--muted-text);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .children-table th:nth-child(1),
        .children-table td:nth-child(1) {
            width: 30%;
        }

        .children-table th:nth-child(2),
        .children-table td:nth-child(2) {
            width: 20%;
        }

        .children-table th:nth-child(3),
        .children-table td:nth-child(3) {
            width: 15%;
        }

        .children-table th:nth-child(4),
        .children-table td:nth-child(4) {
            width: 15%;
        }

        .children-table th:nth-child(5),
        .children-table td:nth-child(5) {
            width: 10%;
        }

        .children-table th:nth-child(6),
        .children-table td:nth-child(6) {
            width: 10%;
            text-align: center;
        }

        .children-info-cell {
            display: flex;
            align-items: center;
            padding-left: 1rem;
        }

        .children-profile-image {
            width: 40px;
            height: 40px;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #edf2f7;
            color: var(--sidebar-bg);
            border-radius: 10px;
        }

        .children-profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .children-full-name {
            color: #111827;
            font-weight: 700;
        }

        .children-email {
            color: #6b7280;
            font-size: 13px;
        }

        .children-specialization-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #eef2ff;
            color: #4f46e5;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .children-status-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #c6f6d5;
            color: #22543d;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .children-action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .children-action-button {
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

        .children-action-view {
            background: #e0f2fe;
            color: #0ea5e9;
        }

        .children-action-edit {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .children-action-delete {
            background: #fef3c7;
            color: #d97706;
        }

        .children-action-button:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        /* =========================
           Custom Modals
        ========================= */

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
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .admin-modal-box {
            position: relative;
            width: 420px;
            max-width: 95%;
            overflow: hidden;
            background: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            animation: modalSlideUp 0.4s ease;
        }

        @keyframes modalSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .view-children-modal .admin-modal-header {
            background: var(--taif-green);
        }

        .edit-children-modal .admin-modal-header {
            background: var(--taif-orange);
        }

        .delete-children-modal .admin-modal-header {
            background: var(--danger-red);
        }

        .admin-modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 22px;
            cursor: pointer;
            transition: 0.2s;
        }

        .admin-modal-close:hover {
            color: white;
            transform: scale(1.1);
        }

        .admin-modal-body {
            padding: 30px 25px;
        }

        .view-children-large-image {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            overflow: hidden;
            background: #f0fff4;
            border-radius: 12px;
        }

        .view-children-large-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .view-children-name {
            margin-bottom: 5px;
            color: #1f5b87;
            font-size: 24px;
            font-weight: 700;
        }

        .view-children-details-box {
            padding: 15px;
            background: #f8fafc;
            border-radius: 15px;
        }

        .view-children-info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .view-children-info-row:last-child {
            border: none;
        }

        .view-children-info-label {
            color: var(--muted-text);
            font-weight: 700;
        }

        .view-children-info-value {
            color: var(--dark-text);
            font-weight: 600;
        }

        /* =========================
           Edit Form
        ========================= */

        .children-edit-form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .children-edit-form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1f5b87;
            font-size: 14px;
            font-weight: 700;
        }

        .children-edit-input {
            width: 100%;
            height: 45px;
            padding: 0 15px;
            background: #f8fafc;
            border: 2px solid var(--light-border);
            border-radius: 12px;
            font-size: 15px;
        }

        .children-edit-input:focus {
            outline: none;
            background: white;
            border-color: var(--taif-orange);
        }

        .children-edit-save-button {
            width: 100%;
            padding: 12px;
            background: var(--taif-orange);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.3s;
        }

        .children-edit-save-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(246, 173, 85, 0.3);
        }

        /* =========================
           Delete Modal
        ========================= */

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

        .delete-cancel-button:hover,
        .delete-confirm-button:hover {
            opacity: 0.9;
        }

        /* =========================
           Responsive
        ========================= */

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
    </style>
</head>

<body>
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="admin-main-content text-start">
        <div class="admin-page-header">
            <div>
                <h4 class="admin-page-title">
                    Children Management
                </h4>

                <small class="admin-page-subtitle">
                    Manage all children in the system
                </small>
            </div>

            <div class="admin-status-wrapper">
                <div class="admin-status-text">
                    <div class="admin-status-title">
                        Admin Panel
                    </div>

                    <small class="admin-online-status">
                        <i class="fas fa-circle me-1"></i>
                        Online
                    </small>
                </div>

                <button class="admin-logout-button">
                    <i class="fas fa-sign-out-alt text-danger"></i>
                </button>
            </div>
        </div>

        <div class="children-management-card">
            <div class="children-table-header">
                <h6 class="children-table-title">
                    Children Directory
                </h6>

                <div class="children-search-wrapper">
                    <i class="fas fa-search children-search-icon"></i>

                    <input
                        type="text"
                        id="childrenSearchInput"
                        class="form-control form-control-sm children-search-input"
                        placeholder="Search..."
                    >
                </div>
            </div>

            <table id="childrenManagementTable" class="children-table">
                <thead>
                    <tr>
                        <th>Child Name</th>
                        <th>Child ID</th>
                        <th>Parent Name</th>
                        <th>Autism Level</th>
                        <th>Gender</th>
                        <th>Birth date</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>

                <tbody id="childrenTableBody">
                    @forelse($children as $child)
                    @php
                        $user = $child->parentProfile?->user;

                        $childFullName = $child->name; // اسم الطفل من جدول الأطفال مباشرة

                        $parentName = $user 
                            ? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) 
                            : 'No Parent';

                        $childImage = ($user && $user->profile_image)
                            ? asset('storage/' . $user->profile_image)
                            : asset('images/default-user.png');
                    @endphp

                        <tr>
                            <td>
                                <div class="children-info-cell">
                                    <div class="children-profile-image">
                                        <img
                                            src="{{ $childImage }}"
                                            alt="Children Profile"
                                        >
                                    </div>

                                    <div>
                                        <div class="children-full-name">
                                            {{ $child->name }}
                                        </div>

                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="children-specialization-badge">
                                    {{ $child->id ?? 'N/A' }}
                                </span>
                            </td>

                            <td>
                                <span class="children-specialization-badge">
                                    {{ $parentName ?? 'N/A' }}
                                </span>
                            </td>

                            <td>
                                {{ $child->autism_level ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $child->gender ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $child->birth_date ?? 'N/A' }}
                            </td>

                            <td>
                                <div class="children-action-buttons">
                                    <button
                                        type="button"
                                        class="children-action-button children-action-view js-view-children"
                                        data-name="{{ $childFullName }}"
                                        data-parent-name="{{ $parentName ?? 'N/A' }}"
                                        data-autism-level="{{ $child->autism_level ?? 'N/A' }}"
                                        data-gender="{{ $child->gender ?? 'N/A' }}"
                                        data-birth-date="{{ $child->birth_date ?? 'N/A' }}"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="children-action-button children-action-edit js-edit-children"
                                        data-id="{{ $child->id }}"
                                        data-name="{{ $child->name ?? '' }}"
                                        data-parent-name="{{ $parentName ?? '' }}"
                                        data-autism-level="{{ $child->autism_level ?? '' }}"
                                        data-gender="{{ $child->gender ?? '' }}"
                                        data-birth-date="{{ $child->birth_date ?? '' }}"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form
                                        action="{{ route('admin.children.destroy', $child->id) }}"
                                        method="POST"
                                        class="m-0"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            class="children-action-button children-action-delete js-delete-children"
                                            data-name="{{ $childFullName }}"
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
                                No children found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- View children Modal -->
    <div id="childrenViewModal" class="admin-modal-overlay view-children-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-id-card"></i>
                Children Profile
            </div>

            <span
                class="admin-modal-close"
                onclick="closeAdminModal('childrenViewModal')"
            >
                &times;
            </span>

            <div class="admin-modal-body text-center">
                <div class="view-doctor-large-image">
                    <img
                        id="viewChildrenImage"
                        src="{{ asset('images/default-user.png') }}"
                        alt="children Image"
                    >
                </div>

                <h3 id="viewchildrenName" class="view-children-name"></h3>

                <span
                    id="viewChildrenSpecialization"
                    class="children-specialization-badge d-inline-block mb-4"
                    style="font-size: 13px;"
                ></span>

                <div class="view-children-details-box">
                    <div class="view-children-info-row">
                        <span class="view-children-info-label">Child name</span>
                        <span class="view-children-info-value" id="viewChildrenName"></span>
                    </div>

                    <div class="view-children-info-row">
                        <span class="view-children-info-label">Parent name</span>
                        <span class="view-children-info-value" id="viewParentName"></span>
                    </div>

                    <div class="view-children-info-row">
                        <span class="view-children-info-label">Autism level</span>
                        <span class="view-children-info-value" id="viewAutismLevel"></span>
                    </div>

                    <div class="view-children-info-row">
                        <span class="view-children-info-label">Gender</span>
                        <span class="view-children-info-value" id="viewGender"></span>
                    </div>

                    <div class="view-children-info-row">
                        <span class="view-children-info-label">Birth date</span>
                        <span class="view-children-info-value" id="viewBirthDate"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit children Modal -->
    <div id="childrenEditModal" class="admin-modal-overlay edit-children-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-user-edit"></i>
                Edit Information
            </div>

            <span
                class="admin-modal-close"
                onclick="closeAdminModal('childrenEditModal')"
            >
                &times;
            </span>

            <div class="admin-modal-body">
                <form id="childrenUpdateForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="children-edit-form-group">
                        <label>Id</label>

                        <input
                            type="text"
                            id="editchildId"
                            name="id"
                            class="children-edit-input"
                            readonly
                        >
                    </div>

                    <div class="children-edit-form-group">
                        <label> Child name</label>

                        <input
                            type="text"
                            id="editchildrenName"
                            name="name"
                            class="children-edit-input"
                        >
                    </div>

                    <div class="children-edit-form-group">
                        <label>Autism Level</label>

                        <select
                            id="editAutismLevel"
                            name="autism_level"
                            class="children-edit-input"
                        >
                            <option value="">Select Level</option>
                            <option value="Mild">Mild</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Severe">Severe</option>
                        </select>
                    </div>

                    <div class="children-edit-form-group">
                        <label>Gender</label>

                        <select
                            id="editGender"
                            name="gender"
                            class="children-edit-input"
                        >
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <div class="children-edit-form-group">
                        <label>Birth Date</label>

                        <input
                            type="date"
                            id="editBirthDate"
                            name="birth_date"
                            class="children-edit-input"
                        >
                    </div>

                    <button type="submit" class="children-edit-save-button">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete children Modal -->
    <div id="childrenDeleteModal" class="admin-modal-overlay delete-children-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-trash"></i>
                Delete children
            </div>

            <span
                class="admin-modal-close"
                onclick="closeAdminModal('childrenDeleteModal')"
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
                    You are about to delete
                    <strong id="deletechildrenName">this children</strong>.
                    This action cannot be undone.
                </p>

                <div class="delete-actions">
                    <button
                        type="button"
                        class="delete-cancel-button"
                        onclick="closeAdminModal('childrenDeleteModal')"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        class="delete-confirm-button"
                        id="confirmchildrenDeleteButton"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

<script>
    let childrenDeleteForm = null;

    // search 
    const childrenSearchInput = document.getElementById('childrenSearchInput');
    if (childrenSearchInput) {
        childrenSearchInput.addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase();
            const childrenRows = document.querySelectorAll('#childrenTableBody tr');
            childrenRows.forEach(function (row) {
                row.style.display = row.innerText.toLowerCase().includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // view 
    document.querySelectorAll('.js-view-children').forEach(function (button) {
        button.addEventListener('click', function () {
            const d = this.dataset;
            document.getElementById('viewchildrenName').innerText = d.name || 'N/A';
            document.getElementById('viewParentName').innerText = d.parentName || 'N/A';
            document.getElementById('viewAutismLevel').innerText = d.autismLevel || 'N/A';
            document.getElementById('viewGender').innerText = d.gender || 'N/A';
            document.getElementById('viewBirthDate').innerText = d.birthDate || 'N/A';
            if(document.getElementById('viewChildrenImage')) {
                document.getElementById('viewChildrenImage').src = d.image || '';
            }
            document.getElementById('childrenViewModal').style.display = 'flex';
        });
    });

    // edit 
   document.querySelectorAll('.js-edit-children').forEach(function (button) {
    button.addEventListener('click', function () {
        const d = this.dataset;
        console.log("تجهيز بيانات التعديل للطفل:", d.id);

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
            console.error("خطأ في فتح مودال التعديل:", error);
        }
    });
    });
    
    // delet
    document.querySelectorAll('.js-delete-children').forEach(function (button) {
        button.addEventListener('click', function () {
            childrenDeleteForm = this.closest('form');
            const nameDisplay = document.getElementById('deletechildrenName');
            if(nameDisplay) nameDisplay.innerText = this.dataset.name || 'this child';
            document.getElementById('childrenDeleteModal').style.display = 'flex';
        });
    });

    document.getElementById('confirmChildrenDeleteButton').addEventListener('click', function () {
        if (childrenDeleteForm) childrenDeleteForm.submit();
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