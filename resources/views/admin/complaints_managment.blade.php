<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> Complaints Management - Taif Project</title>

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
        --light-border: #edf2f7;
        --muted-text: #718096;
        --dark-text: #2d3748;
        --danger-red: #ef4444;
    }

        body { display: flex; background: var(--bg-light); min-height: 100vh; direction: ltr; }


body { 
    display: block; /* غيريه من flex إلى block */
    background: var(--page-bg); 
    min-height: 100vh; 
    margin: 0;
    padding: 0;
}

.complaints-container {
    padding: 2rem;
    /* أضيفي هذا السطر لترك مساحة للشريط الجانبي */
    margin-left: 260px; 
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
        text-align: left;
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

    .role-doctor {
        background: #dbeafe;
        color: #2563eb;
    }

    .role-parent {
        background: #dcfce7;
        color: #15803d;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .status-resolved {
        background: #dcfce7;
        color: #15803d;
    }

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

    .view-btn {
        background: #dbeafe;
        color: #2563eb;
    }

    .delete-btn {
        background: #fee2e2;
        color: #dc2626;
    }

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
        right: 20px;
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

    .update-btn {
        background: #fef3c7;
        color: #d97706;
    }
</style>

<body>

  @include('admin.partials.sidebar')


<div class="complaints-container">

    <div class="complaints-header">
        <div>
            <h3 class="complaints-title">Complaints Management</h3>
            <div class="complaints-subtitle">
                Manage all complaints from doctors and parents
            </div>
        </div>
    </div>

    <div class="complaints-card">

        <div class="complaints-table-header">
            <h6 class="complaints-table-title">
                Complaints Directory
            </h6>

            <div style="display:flex; gap:15px; margin-bottom:20px;">

                <!-- role Filter -->
                <select id="roleFilter" class="filter-select">
                    <option value="">All Roles</option>
                    <option value="doctor">Doctor</option>
                    <option value="parent">Parent</option>
                </select>

                <!-- category Filter -->
                <select id="categoryFilter" class="filter-select">
                    <option value="">All Categories</option>
                    <option value="system_error_or_bug">System Error or Bug</option>
                    <option value="technical_issue">Technical Issue</option>
                    <option value="parent_dispute">Issue regarding a Parent</option>
                    <option value="doctor_issue">Issue regarding a Doctor</option>
                    <option value="general_suggestion">General Suggestion</option>
                    <option value="other">Other</option>
                </select>

                <input
                    type="text"
                    id="complaintSearch"
                    class="form-control complaints-search"
                    placeholder="Search..."
                >

            </div>



        </div>

        <table class="complaints-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Category</th>
                    <th>Complaint</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>

            <tbody id="complaintsTableBody">

                @forelse($complaints as $complaint)

                @php
                    $user = $complaint->user;

                    $fullName = $user
                        ? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))
                        : 'Unknown User';

                    $role = $user->role ?? 'Parent';

                    $image = $user && $user->profile_image
                        ? asset('storage/' . $user->profile_image)
                        : asset('images/default-user.png');
                @endphp

                <tr 
                    data-role="{{ strtolower($role) }}"
                    data-category="{{ $complaint->category }}"
                >

                    <td>
                        <div class="user-info">

                            <div class="user-image">
                                <img src="{{ $image }}">
                            </div>

                            <div>
                                <div class="user-name">
                                    {{ $fullName }}
                                </div>

                                <div class="user-role">
                                    {{ $user->email ?? 'No Email' }}
                                </div>
                            </div>

                        </div>
                    </td>

                    <td>
                        <span class="role-badge {{ $role == 'doctor' ? 'role-doctor' : 'role-parent' }}">
                            {{ ucfirst($role) }}
                        </span>
                    </td>

                    <td>
                        {{ $complaint->category }}
                    </td>

                    <td>
                        {{ $complaint->message }}
                    </td>

                    <td>
                        <span class="status-badge {{ $complaint->status == 'resolved' ? 'status-resolved' : 'status-pending' }}">
                            {{ ucfirst($complaint->status) }}
                        </span>
                    </td>

                    <td>
                        {{ $complaint->created_at->format('Y-m-d') }}
                    </td>

                    <td>

                        <div class="action-buttons">

                            <button
                                class="action-btn view-btn js-view-complaint"
                                data-user="{{ $fullName }}"
                                data-role="{{ ucfirst($role) }}"
                                data-category="{{ $complaint->category }}"
                                data-message="{{ $complaint->message }}"
                            >
                                <i class="fas fa-eye"></i>
                            </button>

                            <form
                                action="{{ route('admin.complaints.destroy', $complaint->id) }}"
                                method="POST"
                            >
                                @csrf
                                @method('DELETE')

                                <button class="action-btn delete-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                            <form
                                action="{{ route('admin.complaints.update', $complaint->id) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PUT')
                                <button
                                    type="button"
                                    class="action-btn update-btn"
                                    data-id="{{ $complaint->id }}"
                                    data-status="{{ $complaint->status }}"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>

                            </form>
                        </div>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        No complaints found
                    </td>
                </tr>

                @endforelse

            </tbody>
        </table>

    </div>
</div>

<!-- View Modal -->

<div class="modal-overlay" id="complaintModal">

    <div class="modal-box">

        <div class="modal-header">
            Complaint Details

            <span class="close-modal" onclick="closeComplaintModal()">
                &times;
            </span>
        </div>

        <div class="modal-body">

            <h5 id="modalUserName"></h5>

            <div class="mb-3">
                <span id="modalUserRole" class="role-badge"></span>
            </div>

            <div class="complaint-category" id="modalComplaintCategory"></div>
            <div class="complaint-message" id="modalComplaintMessage"></div>

        </div>

    </div>

</div>


<!-- Update Status Modal -->
<div class="modal-overlay" id="updateModal">

    <div class="modal-box">

        <div class="modal-header">
            Update Complaint Status

            <span class="close-modal" onclick="closeUpdateModal()">
                &times;
            </span>
        </div>

        <div class="modal-body">

            <input type="hidden" id="complaintId">

            <div class="mb-3">
                <label class="form-label">Status</label>

                <select id="complaintStatus" class="form-control">
                    <option value="pending">Pending</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>

            <button class="btn btn-primary w-100" onclick="saveStatus()">
                Save Changes
            </button>

        </div>

    </div>

</div>

<script>

    const searchInput = document.getElementById('complaintSearch');

    searchInput.addEventListener('keyup', function () {

        const value = this.value.toLowerCase();

        document.querySelectorAll('#complaintsTableBody tr').forEach(row => {

            row.style.display =
                row.innerText.toLowerCase().includes(value)
                ? ''
                : 'none';
        });
    });

    document.querySelectorAll('.js-view-complaint').forEach(button => {

        button.addEventListener('click', function () {

            document.getElementById('modalUserName').innerText =
                this.dataset.user;

            document.getElementById('modalUserRole').innerText =
                this.dataset.role;

            document.getElementById('modalComplaintMessage').innerText =
                this.dataset.message;

            document.getElementById('modalComplaintCategory').innerText =
                this.dataset.category;

            document.getElementById('complaintModal').style.display = 'flex';
        });
    });

    function closeComplaintModal() {

        document.getElementById('complaintModal').style.display = 'none';
    }

    const roleFilter = document.getElementById('roleFilter');
    const categoryFilter = document.getElementById('categoryFilter');

    function filterComplaints() {

        const selectedRole = roleFilter.value;
        const selectedCategory = categoryFilter.value;

        const rows = document.querySelectorAll('#complaintsTableBody tr');

        rows.forEach(row => {

            const role = row.dataset.role;
            const category = row.dataset.category;

            const roleMatch =
                !selectedRole || role === selectedRole;

            const categoryMatch =
                !selectedCategory || category === selectedCategory;

            if (roleMatch && categoryMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }

        });
    }

    roleFilter.addEventListener('change', filterComplaints);
    categoryFilter.addEventListener('change', filterComplaints);

   document.querySelectorAll('.update-btn').forEach(button => {

    button.addEventListener('click', function () {

        document.getElementById('complaintId').value =
            this.dataset.id;

        document.getElementById('complaintStatus').value =
            this.dataset.status;

        document.getElementById('updateModal').style.display = 'flex';
    });

});

    function closeUpdateModal() {

        document.getElementById('updateModal').style.display = 'none';
    }

function saveStatus() {
    const id = document.getElementById('complaintId').value;
    const status = document.getElementById('complaintStatus').value;

    fetch(`/admin/complaints/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            status: status
        })
    })
    .then(response => response.json()) // تحويل الاستجابة إلى JSON مباشرة
    .then(data => {
        if(data.success) {
            location.reload(); // إعادة تحميل الصفحة لتحديث الحالة
        } else {
            alert("Error: Could not update status.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An error occurred. Check the console.");
    });
}
</script>

</body>
</html>