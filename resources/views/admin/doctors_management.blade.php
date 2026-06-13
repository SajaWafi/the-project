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

        .doctor-filter-search-wrapper {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .doctor-approval-filter {
            width: 170px;
            background: #f8fafc;
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

        .approval-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .approval-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .approval-approved {
            background: #c6f6d5;
            color: #22543d;
        }

        .approval-rejected {
            background: #fee2e2;
            color: #dc2626;
        }

        .doctor-action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
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

        .doctor-action-approve {
            background: #dcfce7;
            color: #16a34a;
        }

        .doctor-action-reject {
            background: #fee2e2;
            color: #dc2626;
        }

        .doctor-action-delete {
            background: #fef3c7;
            color: #d97706;
        }

        .doctor-action-button:hover {
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
            width: 420px;
            max-width: 95%;
            overflow: hidden;
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
        .add-doctor-button {
            height: 34px;
            padding: 0 14px;
            display: flex;
            align-items: center;
            gap: 7px;
            border: none;
            border-radius: 10px;
            background: var(--taif-orange);
            color: white;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            white-space: nowrap;
        }

        .add-doctor-button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    @include('admin.partials.sidebar')

   
    <div class="admin-main-content text-start">
        <div class="admin-page-header">
            <div>
                <h4 class="admin-page-title">
                    Doctor Management
                </h4>

                <small class="admin-page-subtitle">
                    Manage doctors and approval requests
                </small>
            </div>

            <div class="admin-status-wrapper">
                <div class="admin-status-text">
                
                </div>
            </div>
        </div>

        <div class="doctor-management-card">
            <div class="doctor-table-header">
                <h6 class="doctor-table-title">
                    Professional Directory
                </h6>

               <div class="doctor-filter-search-wrapper">
    <button
        type="button"
        class="add-doctor-button"
        onclick="openAdminModal('doctorCreateModal')"
    >
        <i class="fas fa-plus"></i>
        Add Doctor
    </button>

    <select
        id="doctorApprovalFilter"
        class="form-control form-control-sm doctor-approval-filter"
    >
        <option value="all">All statuses</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
    </select>

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
            </div>

            <table id="doctorManagementTable" class="doctor-table">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Specialty</th>
                        <th>Phone</th>
                        <th>Joined Date</th>
                        <th>Approval</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>

                <tbody id="doctorTableBody">
                    @forelse($doctors as $doctor)
                        @php
                            $doctorFullName = trim(($doctor->user->first_name ?? '') . ' ' . ($doctor->user->last_name ?? ''));

                            if ($doctorFullName === '') {
                                $doctorFullName = 'No Name';
                            }

                            $doctorImage = $doctor->user->profile_image
                                ? asset('storage/' . $doctor->user->profile_image)
                                : asset('images/default-user.png');

                            $approvalStatus = $doctor->approval_status ?? 'pending';

                            $approvalClass = match($approvalStatus) {
                                'approved' => 'approval-approved',
                                'rejected' => 'approval-rejected',
                                default => 'approval-pending',
                            };
                        @endphp

                        <tr data-approval="{{ $approvalStatus }}">
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
                                            {{ $doctor->user->email ?? 'N/A' }}
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
                                <span class="approval-badge {{ $approvalClass }}">
                                    {{ ucfirst($approvalStatus) }}
                                </span>
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

                                    @if($approvalStatus !== 'approved')
                                        <form
                                            action="{{ route('admin.doctors.approve', $doctor->id) }}"
                                            method="POST"
                                            class="m-0"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="doctor-action-button doctor-action-approve"
                                                title="Approve Doctor"
                                            >
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($approvalStatus !== 'rejected')
                                        <form
                                            action="{{ route('admin.doctors.reject', $doctor->id) }}"
                                            method="POST"
                                            class="m-0"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="doctor-action-button doctor-action-reject"
                                                title="Reject Doctor"
                                            >
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif

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
                            <td colspan="7" class="text-center py-5 text-muted">
                                No doctors found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-3">
                {{ $doctors->links() }}
            </div>
        </div>
    </div>

    <!-- view doctor modal -->
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

    <!-- edit doctor modal -->
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

                    <div class="doctor-edit-form-group" action="{{ route('admin.doctors.update', $doctor->id) }}">
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

    <!-- delete doctor modal -->
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

    <!-- create doctor modal -->
<div id="doctorCreateModal" class="admin-modal-overlay create-doctor-modal">
    <div class="admin-modal-box">
        <div class="admin-modal-header" style="background: var(--taif-blue);">
            <i class="fas fa-user-plus"></i>
            Add New Doctor
        </div>

        <span
            class="admin-modal-close"
            onclick="closeAdminModal('doctorCreateModal')"
        >
            &times;
        </span>

        <div class="admin-modal-body">
            <form action="{{ route('admin.doctors.store') }}" method="POST">
                @csrf

                <div class="doctor-edit-form-group">
                    <label>First Name</label>
                    <input
                        type="text"
                        name="first_name"
                        class="doctor-edit-input"
                        required
                    >
                </div>

                <div class="doctor-edit-form-group">
                    <label>Last Name</label>
                    <input
                        type="text"
                        name="last_name"
                        class="doctor-edit-input"
                        required
                    >
                </div>

                <div class="doctor-edit-form-group">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        class="doctor-edit-input"
                        required
                    >
                </div>

                <div class="doctor-edit-form-group">
                    <label>Phone</label>
                    <input
                        type="text"
                        name="phone"
                        class="doctor-edit-input"
                    >
                </div>

                <div class="doctor-edit-form-group">
                    <label>Gender</label>
                    <select name="gender" class="doctor-edit-input">
                        <option value="">Select gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div class="doctor-edit-form-group">
                    <label>Password</label>
                    <input
                        type="password"
                        name="password"
                        class="doctor-edit-input"
                        required
                    >
                </div>

                <div class="doctor-edit-form-group">
                    <label>Specialization</label>
                    <input
                        type="text"
                        name="specialization"
                        class="doctor-edit-input"
                        required
                    >
                </div>

                <div class="doctor-edit-form-group">
                    <label>Bio</label>
                    <input
                        type="text"
                        name="bio"
                        class="doctor-edit-input"
                    >
                </div>

                <button type="submit" class="doctor-edit-save-button">
                    Add Doctor
                </button>
            </form>
        </div>
    </div>
</div>
    <script>
        let doctorDeleteForm = null;

        const doctorSearchInput = document.getElementById('doctorSearchInput');
        const doctorApprovalFilter = document.getElementById('doctorApprovalFilter');
        // ---------------------------------------------------------
        // 1. دالة الفلترة المزدوجة (Client-Side Filtering)
        // ---------------------------------------------------------
        function filterDoctorsTable() {
            const searchTerm = doctorSearchInput
                ? doctorSearchInput.value.toLowerCase().trim()
                : '';

            const selectedApproval = doctorApprovalFilter
                ? doctorApprovalFilter.value
                : 'all';
        // جلب جميع صفوف الأطباء في الجدول
            const doctorRows = document.querySelectorAll('#doctorTableBody tr');

            doctorRows.forEach(function (row) {
                if (!row.dataset.approval) {
                    return;
                }
        // سحب النص المعروض في الصف والحالة المخفية في (data-attributes)
                const rowText = row.innerText.toLowerCase();
                const rowApproval = row.dataset.approval;
        // التحقق المنطقي: هل يتطابق النص؟ وهل تتطابق الحالة؟
                const matchesSearch = rowText.includes(searchTerm);
                const matchesApproval =
                    selectedApproval === 'all' || rowApproval === selectedApproval;

                row.style.display = matchesSearch && matchesApproval ? '' : 'none';
            });
        }

        if (doctorSearchInput) {
            doctorSearchInput.addEventListener('keyup', filterDoctorsTable);
        }

        if (doctorApprovalFilter) {
            doctorApprovalFilter.addEventListener('change', filterDoctorsTable);
        }
    // ---------------------------------------------------------
    // 2. دالة عرض التفاصيل (View Modal Population)
    // ---------------------------------------------------------
        document.querySelectorAll('.js-view-doctor').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('viewDoctorName').innerText =
                    this.dataset.name || 'N/A';

                document.getElementById('viewDoctorSpecialization').innerText =
                    this.dataset.specialization || 'N/A';

                document.getElementById('viewDoctorEmail').innerText =
                    this.dataset.email || 'N/A';

                document.getElementById('viewDoctorPhone').innerText =
                    this.dataset.phone || 'N/A';

                document.getElementById('viewDoctorImage').src =
                    this.dataset.image;

                document.getElementById('doctorViewModal').style.display = 'flex';
            });
        });
    // ---------------------------------------------------------
    // 3. دالة التعديل (Dynamic Form Routing)
    // ---------------------------------------------------------
        document.querySelectorAll('.js-edit-doctor').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('editDoctorFirstName').value =
                    this.dataset.firstName || '';

                document.getElementById('editDoctorLastName').value =
                    this.dataset.lastName || '';

                document.getElementById('editDoctorSpecialization').value =
                    this.dataset.specialization || '';
    // [Dynamic Action]: تركيب مسار التعديل برمجياً ليتطابق مع الـ ID الخاص بالطبيب المختار
                document.getElementById('doctorUpdateForm').action =
                    '/admin/doctors/' + this.dataset.id;

                document.getElementById('doctorEditModal').style.display = 'flex';
            });
        });
    // ---------------------------------------------------------
    // 4. دالة الحذف (Safe Deletion Flow)
    // ---------------------------------------------------------
        document.querySelectorAll('.js-delete-doctor').forEach(function (button) {
            button.addEventListener('click', function () {
                doctorDeleteForm = this.closest('form');
    // عرض اسم الدكتور في رسالة التأكيد
                document.getElementById('deleteDoctorName').innerText =
                    this.dataset.name || 'this doctor';

                document.getElementById('doctorDeleteModal').style.display = 'flex';
            });
        });

        const confirmDoctorDeleteButton = document.getElementById('confirmDoctorDeleteButton');
    // [Programmatic Submission]: إرسال الفورم برمجياً بعد موافقة الأدمن
        if (confirmDoctorDeleteButton) {
            confirmDoctorDeleteButton.addEventListener('click', function () {
                if (doctorDeleteForm) {
                    doctorDeleteForm.submit();
                }
            });
        }
    // ---------------------------------------------------------
    // 5. دوال التحكم بالواجهة (UX Polish)
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