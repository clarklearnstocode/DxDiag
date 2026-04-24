<?php
/**
 * Shared Admin Sidebar Partial
 * Include this at the top of every admin page body:
 *   <?php $activePage = 'dashboard'; require '_sidebar.php'; ?>
 *
 * $activePage values: dashboard | add_property | reservations | user_management
 */
$activePage = $activePage ?? '';
$adminName  = $_SESSION['admin_name'] ?? 'Administrator';
$initials   = strtoupper(substr($adminName, 0, 1));
?>
<aside class="admin-sidebar">

    <!-- Logo -->
    <a href="index.php?action=admin_dashboard" class="admin-logo">
        <div class="logo-mark">E</div>
        <div class="logo-text">Estate<span>Book</span></div>
    </a>

    <!-- Admin identity chip -->
    <div class="admin-chip">
        <div class="chip-avatar"><?php echo $initials; ?></div>
        <div>
            <div class="chip-name"><?php echo htmlspecialchars($adminName); ?></div>
            <div class="chip-role">Administrator</div>
        </div>
    </div>

    <!-- Main nav -->
    <div class="nav-group">
        <span class="nav-label">Main Menu</span>

        <a href="index.php?action=admin_dashboard"
           class="nav-link <?php echo $activePage === 'dashboard' ? 'active' : ''; ?>">
            <svg class="nav-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            Dashboard
        </a>

        <a href="index.php?action=add_property"
           class="nav-link <?php echo $activePage === 'add_property' ? 'active' : ''; ?>">
            <svg class="nav-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Add Property
        </a>

        <a href="index.php?action=reservations"
           class="nav-link <?php echo $activePage === 'reservations' ? 'active' : ''; ?>">
            <svg class="nav-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Reservations
        </a>

        <a href="index.php?action=user_management"
           class="nav-link <?php echo $activePage === 'user_management' ? 'active' : ''; ?>">
            <svg class="nav-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            User Management
        </a>
    </div>

    <div class="sidebar-spacer"></div>
    <hr class="sidebar-divider">

    <!-- Footer nav -->
    <div class="nav-group" style="margin-bottom:0;">
        <span class="nav-label">Session</span>
        <a href="index.php?action=home" class="nav-link nav-danger">
            <svg class="nav-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Exit Admin
        </a>
    </div>

</aside>
