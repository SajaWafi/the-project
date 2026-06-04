<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Taif Project</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
     <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        :root { --bg-sidebar: #2c5282; --bg-body: #f4f7fc; --taif-orange: #f6ad55; --taif-green: #48bb78; --taif-red: #e53e3e; }
        
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Cairo', sans-serif; }
        body { background-color: var(--bg-body); display: flex; color: #333; }

        .sidebar { width: 260px; min-height: 100vh; background-color: var(--bg-sidebar); color: white; position: fixed; display: flex; flex-direction: column; z-index: 100; }
        .sidebar-header { padding: 1.5rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-brand-img { max-width: 70px; margin-bottom: 10px; }
        
        .nav-link { color: #e2e8f0; padding: 14px 20px; display: flex; align-items: center; text-decoration: none; margin: 5px 15px; border-radius: 8px; font-weight: 600; transition: 0.3s; font-size: 15px; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.15); color: white; }
        .nav-link.active { border-left: 4px solid var(--taif-orange); }
        .nav-link i { margin-right: 15px; width: 20px; text-align: center; font-size: 18px; }

        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 30px 40px; }
        .header-top { background: white; padding: 20px 30px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }

        .settings-wrapper { display: flex; gap: 30px; }
        .settings-nav { width: 250px; background: white; border-radius: 12px; padding: 20px 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); height: fit-content; }
        .tab-btn { width: 100%; text-align: left; padding: 15px 20px; border: none; background: transparent; font-weight: 700; color: #4a5568; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 12px; margin-bottom: 5px; transition: 0.3s; font-size: 15px; }
        .tab-btn:hover { background: #f8fafc; color: var(--bg-sidebar); }
        .tab-btn.active { background: #eef2ff; color: #4f46e5; }

        .settings-content { flex: 1; background: white; border-radius: 12px; padding: 35px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); min-height: 450px; border: 1px solid #edf2f7; }
        
        .tab-panel { display: none; animation: fadeIn 0.4s ease; }
        .tab-panel.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .panel-title { font-size: 20px; font-weight: 800; color: #1f5b87; margin-bottom: 25px; padding-bottom: 10px; border-bottom: 1px solid #edf2f7; display: flex; align-items: center; gap: 10px; }

        .form-row { display: flex; gap: 20px; }
        .form-group { margin-bottom: 20px; flex: 1; }
        .form-label { display: block; font-weight: 700; color: #2d3748; margin-bottom: 8px; font-size: 14px; }
        
        .form-input { width: 100%; border: 2px solid #edf2f7; border-radius: 10px; padding: 12px 15px; background: #f8fafc; outline: none; transition: 0.3s; font-size: 15px; }
        .form-input:focus { border-color: var(--taif-orange); background: white; }
        
        .password-wrapper { position: relative; }
        .password-wrapper input { padding-right: 45px; }
        .password-wrapper i { position: absolute; right: 15px; top: 15px; cursor: pointer; color: #a0aec0; font-size: 16px; transition: 0.2s; }
        .password-wrapper i:hover { color: var(--taif-orange); }

        .btn-save { background: var(--taif-green); color: white; border: none; padding: 12px 30px; border-radius: 999px; font-weight: 800; cursor: pointer; transition: 0.3s; font-size: 16px; display: block; margin-top: 10px; }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(72,187,120,0.3); }

        .alert-success { background: #f0fff4; color: #276749; padding: 15px; border-radius: 10px; margin-bottom: 20px; font-weight: 700; border: 1px solid #9ae6b4; }
        .alert-error { background: #fff5f5; border: 1px solid #feb2b2; color: #c53030; padding: 15px; border-radius: 12px; margin-bottom: 25px; font-size: 14px; font-weight: 600; }
        
        .danger-box { border: 1px solid #feb2b2; background: #fff5f5; border-radius: 12px; padding: 25px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .danger-text h4 { color: #c53030; margin-bottom: 5px; font-weight: 800; }
        .danger-text p { color: #e53e3e; font-size: 14px; max-width: 400px; font-weight: 600; }
        .btn-delete { background: white; color: var(--taif-red); border: 2px solid var(--taif-red); padding: 10px 20px; border-radius: 8px; font-weight: 800; cursor: pointer; transition: 0.3s; font-size: 15px; }
        .btn-delete:hover { background: var(--taif-red); color: white; }

        /* ستايل النوافذ المنبثقة (Modals) */
        .custom-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(3px); }
        .modal-box { background: white; padding: 35px; border-radius: 15px; width: 400px; max-width: 90%; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.1); animation: popIn 0.3s ease; }
        @keyframes popIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .btn-cancel { background: #edf2f7; color: #4a5568; border: none; padding: 12px 25px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.3s; font-size: 15px; }
        .btn-cancel:hover { background: #e2e8f0; }
        .btn-confirm { background: #e53e3e; color: white; border: none; padding: 12px 25px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.3s; font-size: 15px; }
        .btn-confirm:hover { background: #c53030; box-shadow: 0 4px 15px rgba(229, 62, 62, 0.3); }
    </style>
</head>
<body>

    @include('admin.partials.sidebar')

    <div class="main-content">
        <div class="header-top">
            <h2>Settings</h2>
            <small style="color:#718096;">Manage your personal profile and account preferences.</small>
        </div>

        @if(session('success'))
            <div class="alert-success"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <ul style="margin-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="settings-wrapper">
            <div class="settings-nav">
                <!-- تبويبة الملف الشخصي -->
                <button class="tab-btn active" onclick="openTab(event, 'profile')">
                    <i class="fas fa-user-edit"></i> Profile Details
                </button>

                <!-- تبويبة إضافة أدمن (الجديدة) -->
                <button class="tab-btn" onclick="openTab(event, 'add-admin')" style="color: var(--taif-green);">
                    <i class="fas fa-user-plus"></i> Add Admin
                </button>

                <!-- تبويبة حذف الحساب -->
                <button class="tab-btn" onclick="openTab(event, 'security')" style="color: #c53030;">
                    <i class="fas fa-trash-alt"></i> Delete Account
                </button>

                <hr style="border: 0; border-top: 1px solid #edf2f7; margin: 15px 0;">

                <button type="button" class="tab-btn" style="color: #718096;" onclick="openLogoutModal()">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </button>
            </div>

            <div class="settings-content">
                
                <!-- 1. Profile Details -->
                <div id="profile" class="tab-panel active">
                    <h3 class="panel-title"><i class="fas fa-user-circle" style="color: var(--taif-orange);"></i> Update Information</h3>
                    
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-input" value="{{ $admin->first_name }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-input" value="{{ $admin->last_name }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-input" value="{{ $admin->email }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-input" value="{{ $admin->phone }}">
                        </div>

                        <hr style="border: 0; border-top: 1px solid #edf2f7; margin: 30px 0;">
                        <h4 style="color: #4a5568; margin-bottom: 15px; font-size: 16px;">Change Password</h4>

                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <div class="password-wrapper">
                                <input type="password" name="current_password" id="current_password" class="form-input" placeholder="Enter current password">
                                <i class="fas fa-eye-slash" onclick="togglePassword('current_password', this)"></i>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 30px;">
                            <label class="form-label">New Password <span style="font-weight:normal; color:#a0aec0;">(Leave blank to keep current)</span></label>
                            <div class="password-wrapper">
                                <input type="password" name="password" id="new_password" class="form-input" placeholder="Enter new password">
                                <i class="fas fa-eye-slash" onclick="togglePassword('new_password', this)"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn-save"><i class="fas fa-save me-2"></i> Save Changes</button>
                    </form>
                </div>

                <!-- 2. Add New Admin (التبويبة الجديدة) -->
           <div id="add-admin" class="tab-panel">
                    <h3 class="panel-title"><i class="fas fa-user-plus" style="color: var(--taif-green);"></i> Add New Admin</h3>
                    
                    <form action="{{ route('admin.settings.storeAdmin') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-input" placeholder="Enter first name" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-input" placeholder="Enter last name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-input" placeholder="Enter email address" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-input" placeholder="Enter phone number">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-input" required style="cursor: pointer; appearance: auto;">
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row" style="margin-bottom: 30px;">
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <div class="password-wrapper">
                                    <input type="password" name="password" id="add_admin_password" class="form-input" placeholder="Enter strong password" required>
                                    <i class="fas fa-eye-slash" onclick="togglePassword('add_admin_password', this)"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm Password</label>
                                <div class="password-wrapper">
                                    <input type="password" name="password_confirmation" id="add_admin_password_confirm" class="form-input" placeholder="Confirm password" required>
                                    <i class="fas fa-eye-slash" onclick="togglePassword('add_admin_password_confirm', this)"></i>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-save" style="background: var(--taif-green);"><i class="fas fa-plus-circle me-2"></i> Create Admin Account</button>
                    </form>
                </div>

                <!-- 3. Account Security (Delete) -->
                <div id="security" class="tab-panel">
                    <h3 class="panel-title" style="color: #c53030;"><i class="fas fa-exclamation-triangle"></i> Account Security</h3>
                    
                    <div class="danger-box">
                        <div class="danger-text">
                            <h4>Delete Account</h4>
                            <p>Once you delete your account, there is no going back. All your associated data will be permanently removed.</p>
                        </div>
                        <form id="delete-account-form" action="{{ route('admin.settings.destroy') }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-delete" onclick="openDeleteModal()"><i class="fas fa-trash-alt me-2"></i> Delete Account</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- النافذة المنبثقة (Modal) لتسجيل الخروج -->
    <div id="logoutModal" class="custom-modal">
        <div class="modal-box">
            <i class="fas fa-sign-out-alt" style="font-size: 40px; color: #fc8181; margin-bottom: 15px;"></i>
            <h3 style="color: #2d3748; font-weight: 800; margin-bottom: 10px;">Log Out</h3>
            <p style="color: #718096; font-size: 15px; margin-bottom: 25px; font-weight: 600;">Are you sure you want to log out of your account?</p>
            <div style="display: flex; justify-content: center; gap: 10px;">
                <button type="button" class="btn-cancel" onclick="closeLogoutModal()">Cancel</button>
                <button type="button" class="btn-confirm" onclick="document.getElementById('logout-form').submit();">Yes, Log Out</button>
            </div>
        </div>
    </div>

    <!-- النافذة المنبثقة (Modal) لحذف الحساب -->
    <div id="deleteModal" class="custom-modal">
        <div class="modal-box">
            <i class="fas fa-exclamation-triangle" style="font-size: 40px; color: #e53e3e; margin-bottom: 15px;"></i>
            <h3 style="color: #2d3748; font-weight: 800; margin-bottom: 10px;">Delete Account</h3>
            <p style="color: #718096; font-size: 15px; margin-bottom: 25px; font-weight: 600;">Are you absolutely sure you want to delete your account? This action cannot be undone.</p>
            <div style="display: flex; justify-content: center; gap: 10px;">
                <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button type="button" class="btn-confirm" onclick="document.getElementById('delete-account-form').submit();">Yes, Delete</button>
            </div>
        </div>
    </div>

    <!-- فورم مخفي لتسجيل الخروج الفعلي -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        function openTab(evt, tabName) {
            let tabPanels = document.getElementsByClassName("tab-panel");
            for (let i = 0; i < tabPanels.length; i++) {
                tabPanels[i].classList.remove("active");
            }

            let tabBtns = document.getElementsByClassName("tab-btn");
            for (let i = 0; i < tabBtns.length; i++) {
                tabBtns[i].classList.remove("active");
            }

            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }

        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        function openLogoutModal() {
            document.getElementById('logoutModal').style.display = 'flex';
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        function openDeleteModal() {
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>

</body>
</html>