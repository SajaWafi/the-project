<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Management - Taif Project</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    >

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
           Admin Sidebar
        ========================= */

        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .admin-sidebar-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar-logo {
            max-width: 70px;
            margin-bottom: 10px;
        }

        .admin-sidebar-title {
            color: white;
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .admin-sidebar-menu {
            margin-top: 1rem;
            text-align: left;
        }

        .admin-sidebar-link {
            display: flex;
            align-items: center;
            margin: 0.2rem 0.8rem;
            padding: 0.8rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .admin-sidebar-link:hover,
        .admin-sidebar-link.active {
            background-color: var(--sidebar-active-bg);
            color: white;
        }

        .admin-sidebar-link i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
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
           Doctors Table Section
        ========================= */

        .doctor-management-card {
            width: 100%;
            margin-top: 20px;
            overflow-x: auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }

        .doctor-table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .doctor-table-title {
            margin: 0;
            font-weight: 700;
            color: #111827;
        }

        .doctor-search-wrapper {
            position: relative;
            width: 250px;
        }

        .doctor-search-icon {
            position: absolute;
            left: 12px;
            top: 10px;
            color: #6b7280;
        }

        .doctor-search-input {
            padding-left: 2.5rem;
            background: #f8fafc;
        }

        .doctor-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        .doctor-table th,
        .doctor-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }

        .doctor-table th {
            background-color: #f8fafd;
            color: var(--muted-text);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .doctor-table th:nth-child(1),
        .doctor-table td:nth-child(1) {
            width: 30%;
        }

        .doctor-table th:nth-child(2),
        .doctor-table td:nth-child(2) {
            width: 20%;
        }

        .doctor-table th:nth-child(3),
        .doctor-table td:nth-child(3) {
            width: 15%;
        }

        .doctor-table th:nth-child(4),
        .doctor-table td:nth-child(4) {
            width: 15%;
        }

        .doctor-table th:nth-child(5),
        .doctor-table td:nth-child(5) {
            width: 10%;
        }

        .doctor-table th:nth-child(6),
        .doctor-table td:nth-child(6) {
            width: 10%;
            text-align: center;
        }

        .doctor-info-cell {
            display: flex;
            align-items: center;
            padding-left: 1rem;
        }

        .doctor-profile-image {
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

        .doctor-profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .doctor-full-name {
            color: #111827;
            font-weight: 700;
        }

        .doctor-email {
            color: #6b7280;
            font-size: 13px;
        }

        .doctor-specialization-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #eef2ff;
            color: #4f46e5;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .doctor-status-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #c6f6d5;
            color: #22543d;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .doctor-action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .doctor-action-button {
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

        .doctor-action-view {
            background: #e0f2fe;
            color: #0ea5e9;
        }

        .doctor-action-edit {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .doctor-action-delete {
            background: #fef3c7;
            color: #d97706;
        }

        .doctor-action-button:hover {
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

        .view-doctor-modal .admin-modal-header {
            background: var(--taif-green);
        }

        .edit-doctor-modal .admin-modal-header {
            background: var(--taif-orange);
        }

        .delete-doctor-modal .admin-modal-header {
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

        .view-doctor-large-image {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            overflow: hidden;
            background: #f0fff4;
            border-radius: 12px;
        }

        .view-doctor-large-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .view-doctor-name {
            margin-bottom: 5px;
            color: #1f5b87;
            font-size: 24px;
            font-weight: 700;
        }

        .view-doctor-details-box {
            padding: 15px;
            background: #f8fafc;
            border-radius: 15px;
        }

        .view-doctor-info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .view-doctor-info-row:last-child {
            border: none;
        }

        .view-doctor-info-label {
            color: var(--muted-text);
            font-weight: 700;
        }

        .view-doctor-info-value {
            color: var(--dark-text);
            font-weight: 600;
        }

        /* =========================
           Edit Form
        ========================= */

        .doctor-edit-form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .doctor-edit-form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1f5b87;
            font-size: 14px;
            font-weight: 700;
        }

        .doctor-edit-input {
            width: 100%;
            height: 45px;
            padding: 0 15px;
            background: #f8fafc;
            border: 2px solid var(--light-border);
            border-radius: 12px;
            font-size: 15px;
        }

        .doctor-edit-input:focus {
            outline: none;
            background: white;
            border-color: var(--taif-orange);
        }

        .doctor-edit-save-button {
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

        .doctor-edit-save-button:hover {
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
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="admin-sidebar-header">
            <img
                src="{{ asset('images/logo.png') }}"
                class="admin-sidebar-logo"
                alt="Logo"
            >

            <div class="admin-sidebar-title">
                TAIF PROJECT
            </div>
        </div>

        <div class="admin-sidebar-menu">
            <a
                href="{{ route('admin.dashboard') }}"
                class="admin-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            >
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>

            <a
                href="{{ route('admin.doctors.index') }}"
                class="admin-sidebar-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}"
            >
                <i class="fas fa-user-md"></i>
                <span>Manage Doctors</span>
            </a>

            <a href="#" class="admin-sidebar-link">
                <i class="fas fa-users"></i>
                <span>Manage Parents</span>
            </a>

            <a href="#" class="admin-sidebar-link">
                <i class="fas fa-child"></i>
                <span>Manage Children</span>
            </a>

            <a href="#" class="admin-sidebar-link">
                <i class="fas fa-link"></i>
                <span>Linking Requests</span>
            </a>

            <a href="#" class="admin-sidebar-link">
                <i class="fas fa-exclamation-circle"></i>
                <span>Complaints</span>
            </a>

            <a href="#" class="admin-sidebar-link">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-main-content text-start">
        <div class="admin-page-header">
            <div>
                <h4 class="admin-page-title">
                    Doctor Management
                </h4>

                <small class="admin-page-subtitle">
                    Manage all healthcare professionals in the system
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

        <div class="doctor-management-card">
            <div class="doctor-table-header">
                <h6 class="doctor-table-title">
                    Professional Directory
                </h6>

                <div class="doctor-search-wrapper">
                    <i class="fas fa-search doctor-search-icon"></i>

                    <input
                        type="text"
                        id="doctorSearchInput"
                        class="form-control form-control-sm doctor-search-input"
                        placeholder="Search..."
                    >
                </div>
            </div>

            <table id="doctorManagementTable" class="doctor-table">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Specialty</th>
                        <th>Phone</th>
                        <th>Joined Date</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>

                <tbody id="doctorTableBody">
                    @forelse($doctors as $doctor)
                        @php
                            $doctorFullName = trim(($doctor->user->first_name ?? '') . ' ' . ($doctor->user->last_name ?? ''));

                            $doctorImage = $doctor->user->profile_image
                                ? asset('storage/' . $doctor->user->profile_image)
                                : asset('images/default-user.png');
                        @endphp

                        <tr>
                            <td>
                                <div class="doctor-info-cell">
                                    <div class="doctor-profile-image">
                                        <img
                                            src="{{ $doctorImage }}"
                                            alt="Doctor Profile"
                                        >
                                    </div>

                                    <div>
                                        <div class="doctor-full-name">
                                            {{ $doctorFullName }}
                                        </div>

                                        <div class="doctor-email">
                                            {{ $doctor->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="doctor-specialization-badge">
                                    {{ $doctor->specialization ?? 'N/A' }}
                                </span>
                            </td>

                            <td>
                                {{ $doctor->user->phone ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $doctor->created_at->format('M d, Y') }}
                            </td>

                            <td>
                                <span class="doctor-status-badge">
                                    Active
                                </span>
                            </td>

                            <td>
                                <div class="doctor-action-buttons">
                                    <button
                                        type="button"
                                        class="doctor-action-button doctor-action-view js-view-doctor"
                                        data-name="{{ $doctorFullName }}"
                                        data-specialization="{{ $doctor->specialization ?? 'N/A' }}"
                                        data-email="{{ $doctor->user->email ?? 'N/A' }}"
                                        data-phone="{{ $doctor->user->phone ?? 'N/A' }}"
                                        data-image="{{ $doctorImage }}"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="doctor-action-button doctor-action-edit js-edit-doctor"
                                        data-id="{{ $doctor->id }}"
                                        data-first-name="{{ $doctor->user->first_name ?? '' }}"
                                        data-last-name="{{ $doctor->user->last_name ?? '' }}"
                                        data-specialization="{{ $doctor->specialization ?? '' }}"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form
                                        action="{{ route('admin.doctors.destroy', $doctor->id) }}"
                                        method="POST"
                                        class="m-0"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            class="doctor-action-button doctor-action-delete js-delete-doctor"
                                            data-name="{{ $doctorFullName }}"
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
                                No doctors found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Doctor Modal -->
    <div id="doctorViewModal" class="admin-modal-overlay view-doctor-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-id-card"></i>
                Doctor Profile
            </div>

            <span
                class="admin-modal-close"
                onclick="closeAdminModal('doctorViewModal')"
            >
                &times;
            </span>

            <div class="admin-modal-body text-center">
                <div class="view-doctor-large-image">
                    <img
                        id="viewDoctorImage"
                        src="{{ asset('images/default-user.png') }}"
                        alt="Doctor Image"
                    >
                </div>

                <h3 id="viewDoctorName" class="view-doctor-name"></h3>

                <span
                    id="viewDoctorSpecialization"
                    class="doctor-specialization-badge d-inline-block mb-4"
                    style="font-size: 13px;"
                ></span>

                <div class="view-doctor-details-box">
                    <div class="view-doctor-info-row">
                        <span class="view-doctor-info-label">Email</span>
                        <span class="view-doctor-info-value" id="viewDoctorEmail"></span>
                    </div>

                    <div class="view-doctor-info-row">
                        <span class="view-doctor-info-label">Phone</span>
                        <span class="view-doctor-info-value" id="viewDoctorPhone"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Doctor Modal -->
    <div id="doctorEditModal" class="admin-modal-overlay edit-doctor-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-user-edit"></i>
                Edit Information
            </div>

            <span
                class="admin-modal-close"
                onclick="closeAdminModal('doctorEditModal')"
            >
                &times;
            </span>

            <div class="admin-modal-body">
                <form id="doctorUpdateForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="doctor-edit-form-group">
                        <label>First Name</label>

                        <input
                            type="text"
                            id="editDoctorFirstName"
                            name="first_name"
                            class="doctor-edit-input"
                        >
                    </div>

                    <div class="doctor-edit-form-group">
                        <label>Last Name</label>

                        <input
                            type="text"
                            id="editDoctorLastName"
                            name="last_name"
                            class="doctor-edit-input"
                        >
                    </div>

                    <div class="doctor-edit-form-group">
                        <label>Specialization</label>

                        <input
                            type="text"
                            id="editDoctorSpecialization"
                            name="specialization"
                            class="doctor-edit-input"
                        >
                    </div>

                    <button type="submit" class="doctor-edit-save-button">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Doctor Modal -->
    <div id="doctorDeleteModal" class="admin-modal-overlay delete-doctor-modal">
        <div class="admin-modal-box">
            <div class="admin-modal-header">
                <i class="fas fa-trash"></i>
                Delete Doctor
            </div>

            <span
                class="admin-modal-close"
                onclick="closeAdminModal('doctorDeleteModal')"
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
                    <strong id="deleteDoctorName">this doctor</strong>.
                    This action cannot be undone.
                </p>

                <div class="delete-actions">
                    <button
                        type="button"
                        class="delete-cancel-button"
                        onclick="closeAdminModal('doctorDeleteModal')"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        class="delete-confirm-button"
                        id="confirmDoctorDeleteButton"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let doctorDeleteForm = null;

        const doctorSearchInput = document.getElementById('doctorSearchInput');

        if (doctorSearchInput) {
            doctorSearchInput.addEventListener('keyup', function () {
                const searchTerm = this.value.toLowerCase();
                const doctorRows = document.querySelectorAll('#doctorTableBody tr');

                doctorRows.forEach(function (row) {
                    row.style.display = row.innerText.toLowerCase().includes(searchTerm) ? '' : 'none';
                });
            });
        }

        document.querySelectorAll('.js-view-doctor').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('viewDoctorName').innerText = this.dataset.name || 'N/A';
                document.getElementById('viewDoctorSpecialization').innerText = this.dataset.specialization || 'N/A';
                document.getElementById('viewDoctorEmail').innerText = this.dataset.email || 'N/A';
                document.getElementById('viewDoctorPhone').innerText = this.dataset.phone || 'N/A';
                document.getElementById('viewDoctorImage').src = this.dataset.image;

                document.getElementById('doctorViewModal').style.display = 'flex';
            });
        });

        document.querySelectorAll('.js-edit-doctor').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('editDoctorFirstName').value = this.dataset.firstName || '';
                document.getElementById('editDoctorLastName').value = this.dataset.lastName || '';
                document.getElementById('editDoctorSpecialization').value = this.dataset.specialization || '';

                document.getElementById('doctorUpdateForm').action = '/admin/doctors/' + this.dataset.id;
                document.getElementById('doctorEditModal').style.display = 'flex';
            });
        });

        document.querySelectorAll('.js-delete-doctor').forEach(function (button) {
            button.addEventListener('click', function () {
                doctorDeleteForm = this.closest('form');

                document.getElementById('deleteDoctorName').innerText =
                    this.dataset.name || 'this doctor';

                document.getElementById('doctorDeleteModal').style.display = 'flex';
            });
        });

        document.getElementById('confirmDoctorDeleteButton').addEventListener('click', function () {
            if (doctorDeleteForm) {
                doctorDeleteForm.submit();
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