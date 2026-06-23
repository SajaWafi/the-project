<div class="admin-sidebar">
    <div class="admin-sidebar-header">
        <img
            src="{{ asset('images/logo.png') }}"
            class="admin-sidebar-logo"
            alt="Logo"
        >

        <div class="admin-sidebar-title">
            {{ __('TAIF PROJECT') }}
        </div>
    </div>

    <div class="admin-sidebar-menu">
        <a
            href="{{ route('admin.dashboard') }}"
            class="admin-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
        >
            <i class="fas fa-th-large"></i>
            <span>{{ __('Dashboard') }}</span>
        </a>

        <a href="{{ route('admin.bracelets.index') }}" 
        class="admin-sidebar-link {{ request()->routeIs('admin.bracelets.*') ? 'active' : '' }}"
        >
            <i class="fas fa-microchip"></i>
            <span>{{ __('Manage Bracelets') }}</span>
        </a>

        <a
            href="{{ route('admin.doctors.index') }}"
            class="admin-sidebar-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}"
        >
            <i class="fas fa-user-md"></i>
            <span>{{ __('Manage Doctors') }}</span>
        </a>

        <a
            href="{{ route('admin.parents.index') }}"
            class="admin-sidebar-link {{ request()->routeIs('admin.parents.*') ? 'active' : '' }}"
        >
            <i class="fas fa-users"></i>
            <span>{{ __('Manage Parents') }}</span>
        </a>

        <a
            href="{{ route('admin.children.index') }}"
            class="admin-sidebar-link {{ request()->routeIs('admin.children.*') ? 'active' : '' }}"
        >
            <i class="fas fa-child"></i>
            <span>{{ __('Manage Children') }}</span>
        </a>

        <a
            href="{{ route('admin.doctor-requests.index') }}"
            class="admin-sidebar-link {{ request()->routeIs('admin.doctor-requests.*') ? 'active' : '' }}"
        >
            <i class="fas fa-link"></i>
            <span>{{ __('Linking Requests') }}</span>
        </a>

        <a
            href="{{ route('admin.appointments.index') }}"
            class="admin-sidebar-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}"
        >
            <i class="fas fa-calendar-check"></i>
            <span>{{ __('Appointments') }}</span>
        </a>

        <a
            href="{{ route('admin.complaints.index') }}"
            class="admin-sidebar-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}"
        >
            <i class="fas fa-clipboard-list"></i>
            <span>{{ __('Manage Complaints') }}</span>
        </a>

        <a
            href="{{ route('admin.alerts.index') }}"
            class="admin-sidebar-link {{ request()->routeIs('admin.alerts.*') ? 'active' : '' }}"
        >
            <i class="fas fa-bell"></i>
            <span>{{ __('Alerts') }}</span>
        </a>

        <a
            href="{{ route('admin.activity-logs.index') }}"
            class="admin-sidebar-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}"
        >
            <i class="fas fa-history"></i>
            <span>{{ __('Activity Logs') }}</span>
        </a>

        <a
            href="{{ route('admin.settings.index') }}"
            class="admin-sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
        >
            <i class="fas fa-cog"></i>
            <span>{{ __('Settings') }}</span>
        </a>
    </div>
</div>