<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Management - Taif Project</title>

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

        .parent-management-card {
            width: 100%;
            margin-top: 20px;
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .parent-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .parent-table-title {
            margin: 0;
            font-weight: 700;
            color: #111827;
        }

        .parent-search-wrapper {
            position: relative;
            width: 250px;
        }

        .parent-search-icon {
            position: absolute;
            left: 12px;
            top: 10px;
            color: #6b7280;
        }

        .parent-search-input {
            padding-left: 2.5rem;
            background: #f8fafc;
        }

        .parent-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        .parent-table th,
        .parent-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }

        .parent-table th {
            background-color: #f8fafd;
            color: var(--muted-text);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .parent-info-cell {
            display: flex;
            align-items: center;
            padding-left: 1rem;
        }

        .parent-profile-image {
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
            font-size: 18px;
        }

        .parent-profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .parent-full-name {
            color: #111827;
            font-weight: 700;
        }

        .parent-email {
            color: #6b7280;
            font-size: 13px;
        }

        .child-name-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #eef2ff;
            color: #4f46e5;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .autism-level-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #fff2e6;
            color: #d97706;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .parent-action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .parent-action-button {
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

        .parent-action-view {
            background: #e0f2fe;
            color: #0ea5e9;
        }

        .parent-action-edit {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .parent-action-delete {
            background: #fef3c7;
            color: #d97706;
        }

        .parent-action-button:hover {
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
            width: 440px;
            max-width: 95%;
            max-height: 92vh;
            overflow-y: auto;
            background: white;
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

        .view-parent-modal .admin-modal-header {
            background: var(--taif-green);
        }

        .edit-parent-modal .admin-modal-header {
            background: var(--taif-orange);
        }

        .delete-parent-modal .admin-modal-header {
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

        .view-parent-large-image {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0fff4;
            color: var(--taif-green);
            border-radius: 14px;
            font-size: 34px;
        }

        .view-parent-name {
            margin-bottom: 5px;
            color: #1f5b87;
            font-size: 24px;
            font-weight: 700;
        }

        .view-parent-details-box {
            padding: 15px;
            background: #f8fafc;
            border-radius: 15px;
        }

        .view-parent-info-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .view-parent-info-row:last-child {
            border: none;
        }

        .view-parent-info-label {
            color: var(--muted-text);
            font-weight: 700;
        }

        .view-parent-info-value {
            color: var(--dark-text);
            font-weight: 600;
            text-align: right;
        }

        .parent-edit-form-group {
            margin-bottom: 16px;
            text-align: left;
        }

        .parent-edit-form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1f5b87;
            font-size: 14px;
            font-weight: 700;
        }

        .parent-edit-input,
        .parent-edit-select {
            width: 100%;
            height: 45px;
            padding: 0 15px;
            background: #f8fafc;
            border: 2px solid var(--light-border);
            border-radius: 12px;
            font-size: 15px;
        }

        .parent-edit-input:focus,
        .parent-edit-select:focus {
            outline: none;
            background: white;
            border-color: var(--taif-orange);
        }

        .parent-edit-section-title {
            margin: 20px 0 12px;
            color: var(--taif-blue);
            font-size: 15px;
            font-weight: 800;
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 8px;
        }

        .parent-edit-save-button {
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

        .parent-edit-save-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(246, 173, 85, 0.3);
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
        .add-new-button {
        height: 34px;
        padding: 0 16px;
        border: none;
        border-radius: 10px;
        background: var(--taif-orange);
        color: white;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
    }

    .add-new-button:hover {
        opacity: 0.9;
    }
    </style>
<body>
    @include('admin.partials.sidebar')

    <div class="admin-main-content text-start">
        <div class="admin-page-header">
            <div>
                <h4 class="admin-page-title">
                    Parent Management
                </h4>
                <small class="admin-page-subtitle">
                    Manage parents and their children information
                </small>
            </div>

            <div class="admin-status-wrapper">
                <div class="admin-status-text">
                    <div class="admin-status-title">Admin Panel</div>
                    <small class="admin-online-status">
                        <i class="fas fa-circle me-1"></i> Online
                    </small>
                </div>
                <button class="admin-logout-button">
                    <i class="fas fa-sign-out-alt text-danger"></i>
                </button>
            </div>
        </div>

        <div class="parent-management-card">
            <div class="parent-table-header">
                <h6 class="parent-table-title">Parents Directory</h6>

                <form action="{{ route('admin.parents.index') }}" method="GET" id="searchForm" style="display:flex; gap:10px; align-items:center;">
                    <button type="button" class="add-new-button" onclick="openAdminModal('parentCreateModal')">
                        + Add Parent
                    </button>

                    <div class="parent-search-wrapper">
                        <i class="fas fa-search parent-search-icon"></i>
                        <input
                            type="text"
                            name="search"
                            id="parentSearchInput"
                            value="{{ request('search') }}"
                            class="form-control form-control-sm parent-search-input"
                            placeholder="Search names or phones..."
                        >
                    </div>
                </form>
            </div>

            <table id="parentManagementTable" class="parent-table">
                <thead>
                    <tr>
                        <th>Parent</th>
                        <th>Phone</th>
                        <th>Relation</th>
                        <th>Child</th>
                        <th>Joined Date</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>

                <tbody id="parentTableBody">
                    @forelse($parents as $parent)
                        @php
                            $child = $parent->children->first();
                            $parentFullName = trim(($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? ''));

                            if ($parentFullName === '') {
                                $parentFullName = 'No Name';
                            }

                            $parentImage = $parent->user?->profile_image
                                ? asset('storage/' . $parent->user->profile_image)
                                : asset('images/default-user.png');
                        @endphp

                        <tr>
                            <td>
                                <div class="parent-info-cell">
                                    <div class="parent-profile-image">
                                        <img src="{{ $parentImage }}" alt="Parent Profile">
                                    </div>
                                    <div>
                                        <div class="parent-full-name">{{ $parentFullName }}</div>
                                        <div class="parent-email">{{ $parent->user->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $parent->user->phone ?? 'N/A' }}</td>
                            <td>{{ $parent->relation_to_child ?? 'N/A' }}</td>
                            <td>
                                <span class="child-name-badge">{{ $child->name ?? 'N/A' }}</span>
                            </td>
                            <td>{{ $parent->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="parent-action-buttons">
                                    <button type="button" class="parent-action-button parent-action-view js-view-parent"
                                        data-name="{{ $parentFullName }}"
                                        data-email="{{ $parent->user->email ?? 'N/A' }}"
                                        data-phone="{{ $parent->user->phone ?? 'N/A' }}"
                                        data-relation="{{ $parent->relation_to_child ?? 'N/A' }}"
                                        data-child-name="{{ $child->name ?? 'N/A' }}"
                                        data-child-gender="{{ $child->gender ?? 'N/A' }}"
                                        data-autism-level="{{ $child->autism_level ?? 'N/A' }}"
                                        data-image="{{ $parentImage }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button type="button" class="parent-action-button parent-action-edit js-edit-parent"
                                        data-id="{{ $parent->id }}"
                                        data-first-name="{{ $parent->user->first_name ?? '' }}"
                                        data-last-name="{{ $parent->user->last_name ?? '' }}"
                                        data-phone="{{ $parent->user->phone ?? '' }}"
                                        data-relation="{{ $parent->relation_to_child ?? '' }}"
                                        data-child-name="{{ $child->name ?? '' }}"
                                        data-child-gender="{{ $child->gender ?? '' }}"
                                        data-autism-level="{{ $child->autism_level ?? '' }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('admin.parents.destroy', $parent->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="parent-action-button parent-action-delete js-delete-parent" data-name="{{ $parentFullName }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No parents found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-3">
                {{ $parents->links() }}
            </div>
        </div>
    </div>

    <div id="parentViewModal" class="admin-modal-overlay view-parent-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header"><i class="fas fa-id-card"></i> Parent Profile</div>
            <span class="admin-modal-close" onclick="closeAdminModal('parentViewModal')">&times;</span>
            <div class="admin-modal-body text-center">
                <div class="view-parent-large-image"><i class="fas fa-user"></i></div>
                <h3 id="viewParentName" class="view-parent-name"></h3>
                <div class="view-parent-details-box">
                    <div class="view-parent-info-row">
                        <span class="view-parent-info-label">Email</span>
                        <span class="view-parent-info-value" id="viewParentEmail"></span>
                    </div>
                    <div class="view-parent-info-row">
                        <span class="view-parent-info-label">Phone</span>
                        <span class="view-parent-info-value" id="viewParentPhone"></span>
                    </div>
                    <div class="view-parent-info-row">
                        <span class="view-parent-info-label">Relation</span>
                        <span class="view-parent-info-value" id="viewParentRelation"></span>
                    </div>
                    <div class="view-parent-info-row">
                        <span class="view-parent-info-label">Child</span>
                        <span class="view-parent-info-value" id="viewChildName"></span>
                    </div>
                    <div class="view-parent-info-row">
                        <span class="view-parent-info-label">Child Gender</span>
                        <span class="view-parent-info-value" id="viewChildGender"></span>
                    </div>
                    <div class="view-parent-info-row">
                        <span class="view-parent-info-label">Autism Level</span>
                        <span class="view-parent-info-value" id="viewAutismLevel"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="parentEditModal" class="admin-modal-overlay edit-parent-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header"><i class="fas fa-user-edit"></i> Edit Parent</div>
            <span class="admin-modal-close" onclick="closeAdminModal('parentEditModal')">&times;</span>
            <div class="admin-modal-body">
                <form id="parentUpdateForm" method="POST">
                    @csrf @method('PUT')
                    <div class="parent-edit-section-title">Parent Information</div>
                    <div class="parent-edit-form-group">
                        <label>First Name</label>
                        <input type="text" id="editParentFirstName" name="first_name" class="parent-edit-input">
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Last Name</label>
                        <input type="text" id="editParentLastName" name="last_name" class="parent-edit-input">
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Phone</label>
                        <input type="text" id="editParentPhone" name="phone" class="parent-edit-input">
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Relation To Child</label>
                        <input type="text" id="editParentRelation" name="relation_to_child" class="parent-edit-input">
                    </div>

                    <div class="parent-edit-section-title">Child Information</div>
                    <div class="parent-edit-form-group">
                        <label>Child Name</label>
                        <input type="text" id="editChildName" name="child_name" class="parent-edit-input">
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Child Gender</label>
                        <select id="editChildGender" name="child_gender" class="parent-edit-select">
                            <option value="">Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Autism Level</label>
                        <select id="editAutismLevel" name="autism_level" class="parent-edit-select">
                            <option value="">Select level</option>
                            <option value="Mild">Mild</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Severe">Severe</option>
                        </select>
                    </div>
                    <button type="submit" class="parent-edit-save-button">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <div id="parentDeleteModal" class="admin-modal-overlay delete-parent-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header"><i class="fas fa-trash"></i> Delete Parent</div>
            <span class="admin-modal-close" onclick="closeAdminModal('parentDeleteModal')">&times;</span>
            <div class="admin-modal-body text-center">
                <div class="delete-warning-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <h4 class="delete-title">Are you sure?</h4>
                <p class="delete-message">You are about to delete <strong id="deleteParentName">this parent</strong>. This action cannot be undone.</p>
                <div class="delete-actions">
                    <button type="button" class="delete-cancel-button" onclick="closeAdminModal('parentDeleteModal')">Cancel</button>
                    <button type="button" class="delete-confirm-button" id="confirmParentDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div id="parentCreateModal" class="admin-modal-overlay create-parent-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header" style="background: var(--taif-blue);"><i class="fas fa-user-plus"></i> Add Parent And Child</div>
            <span class="admin-modal-close" onclick="closeAdminModal('parentCreateModal')">&times;</span>
            <div class="admin-modal-body">
                <form action="{{ route('admin.parents.store') }}" method="POST">
                    @csrf
                    <div class="parent-edit-section-title">Parent Information</div>
                    <div class="parent-edit-form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="parent-edit-input" required>
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="parent-edit-input" required>
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="parent-edit-input" required>
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="parent-edit-input">
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Gender</label>
                        <select name="gender" class="parent-edit-select">
                            <option value="">Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="parent-edit-input" required>
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Relation To Child</label>
                        <input type="text" name="relation_to_child" class="parent-edit-input">
                    </div>

                    <div class="parent-edit-section-title">Child Information</div>
                    <div class="parent-edit-form-group">
                        <label>Child Name</label>
                        <input type="text" name="child_name" class="parent-edit-input" required>
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Child Gender</label>
                        <select name="child_gender" class="parent-edit-select">
                            <option value="">Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Child Birth Date</label>
                        <input type="date" name="child_birth_date" class="parent-edit-input">
                    </div>
                    <div class="parent-edit-form-group">
                        <label>Autism Level</label>
                        <select name="autism_level" class="parent-edit-select">
                            <option value="">Select level</option>
                            <option value="Mild">Mild</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Severe">Severe</option>
                        </select>
                    </div>
                    <button type="submit" class="parent-edit-save-button">Add Parent And Child</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let parentDeleteForm = null;

        // ---------------------------------------------------------
        // 1. الفلترة الفورية عبر السيرفر (Server-Side Debouncing)
        // ---------------------------------------------------------
        let searchTimeout = null;
        const parentSearchInput = document.getElementById('parentSearchInput');
        const searchForm = document.getElementById('searchForm');

        if (parentSearchInput) {
            parentSearchInput.addEventListener('keyup', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (searchForm) searchForm.submit();
                }, 500);
            });
        }

        // ---------------------------------------------------------
        // 2. دالة عرض التفاصيل (Composite Data Population)
        // ---------------------------------------------------------
        document.querySelectorAll('.js-view-parent').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('viewParentName').innerText = this.dataset.name || 'N/A';
                document.getElementById('viewParentEmail').innerText = this.dataset.email || 'N/A';
                document.getElementById('viewParentPhone').innerText = this.dataset.phone || 'N/A';
                document.getElementById('viewParentRelation').innerText = this.dataset.relation || 'N/A';
                document.getElementById('viewChildName').innerText = this.dataset.childName || 'N/A';
                document.getElementById('viewChildGender').innerText = this.dataset.childGender || 'N/A';
                document.getElementById('viewAutismLevel').innerText = this.dataset.autismLevel || 'N/A';

                document.getElementById('parentViewModal').style.display = 'flex';
            });
        });

        // ---------------------------------------------------------
        // 3. دالة التعديل (Dynamic Form Routing & Pre-filling)
        // ---------------------------------------------------------
        document.querySelectorAll('.js-edit-parent').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('editParentFirstName').value = this.dataset.firstName || '';
                document.getElementById('editParentLastName').value = this.dataset.lastName || '';
                document.getElementById('editParentPhone').value = this.dataset.phone || '';
                document.getElementById('editParentRelation').value = this.dataset.relation || '';
                document.getElementById('editChildName').value = this.dataset.childName || '';
                document.getElementById('editChildGender').value = this.dataset.childGender || '';
                document.getElementById('editAutismLevel').value = this.dataset.autismLevel || '';

                if (document.getElementById('parentUpdateForm')) {
                    document.getElementById('parentUpdateForm').action = '/admin/parents/' + this.dataset.id;
                }
                document.getElementById('parentEditModal').style.display = 'flex';
            });
        });

        // ---------------------------------------------------------
        // 4. دوال الحذف (Safe Deletion Flow)
        // ---------------------------------------------------------
        document.querySelectorAll('.js-delete-parent').forEach(function (button) {
            button.addEventListener('click', function () {
                parentDeleteForm = this.closest('form');
                document.getElementById('deleteParentName').innerText = this.dataset.name || 'this parent';
                document.getElementById('parentDeleteModal').style.display = 'flex';
            });
        });

        const confirmParentDeleteBtn = document.getElementById('confirmParentDeleteButton');
        if (confirmParentDeleteBtn) {
            confirmParentDeleteBtn.addEventListener('click', function () {
                if (parentDeleteForm) {
                    parentDeleteForm.submit();
                }
            });
        }

        // ---------------------------------------------------------
        // 5. دوال التحكم بنوافذ الـ Modals (UX Polish)
        // ---------------------------------------------------------
        function closeAdminModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function openAdminModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        window.onclick = function (event) {
            if (event.target.classList.contains('admin-modal-overlay')) {
                event.target.style.display = 'none';
            }
        };
    </script>
</body>
</html>