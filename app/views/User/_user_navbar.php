<?php
/**
 * _user_navbar.php — Shared User Top Navigation
 * Expects: $activePage (string) set by the including view
 *          Session must already be started
 */
require_once __DIR__ . '/../../../config/Database.php';

$_navUnread = 0;
if (!empty($_SESSION['user_id'])) {
    try {
        $_navPdo  = (new Database())->getConnection();
        $_navStmt = $_navPdo->prepare(
            "SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0"
        );
        $_navStmt->execute([(int)$_SESSION['user_id']]);
        $_navUnread = (int)$_navStmt->fetchColumn();
        unset($_navPdo, $_navStmt);
    } catch (Exception $e) { $_navUnread = 0; }
}

$_navPage  = $activePage ?? '';
$_navName  = htmlspecialchars($_SESSION['user_name']  ?? 'User');
$_navEmail = htmlspecialchars($_SESSION['user_email'] ?? '');
$_navImg   = !empty($_SESSION['user_image'])
    ? 'assets/img/uploads/' . htmlspecialchars($_SESSION['user_image'])
    : 'assets/img/user.png';
?>
<nav class="eb-topnav">
    <a href="index.php?action=home" class="nav-logo">Estate<span>Book</span></a>
    <div class="nav-divider"></div>

    <div class="nav-links">
        <a href="index.php?action=dashboard" class="<?php echo $_navPage === 'dashboard' ? 'active' : ''; ?>">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            <span>Browse Estates</span>
        </a>
        <a href="index.php?action=my_bookings" class="<?php echo $_navPage === 'my_bookings' ? 'active' : ''; ?>">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <span>My Bookings</span>
        </a>
        <a href="index.php?action=profile" class="<?php echo $_navPage === 'profile' ? 'active' : ''; ?>">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>Profile</span>
        </a>
    </div>

    <div class="nav-right">
        <!-- Notification Bell -->
        <div class="notif-wrapper" id="notifWrapper">
            <button class="notif-bell-btn<?php echo $_navUnread > 0 ? ' has-unread' : ''; ?>"
                    onclick="toggleNotifDropdown(event)" title="Notifications">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span class="notif-badge<?php echo $_navUnread === 0 ? ' hidden' : ''; ?>" id="notifBadge">
                    <?php echo $_navUnread > 9 ? '9+' : ($_navUnread > 0 ? $_navUnread : ''); ?>
                </span>
            </button>
            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-dropdown-header">
                    <strong>Notifications</strong>
                    <button class="mark-read-btn" onclick="markAllRead()">Mark all read</button>
                </div>
                <div class="notif-list" id="notifList">
                    <div class="notif-empty" style="padding:20px;text-align:center;color:#9fa8b3;font-size:.8rem;">Loading…</div>
                </div>
            </div>
        </div>

        <!-- Profile -->
        <div class="profile-btn-wrap" id="profileBtnWrap">
            <button class="profile-btn" id="profileBtn" onclick="toggleProfileDropdown(event)">
                <img src="<?php echo $_navImg; ?>" alt="Profile" class="profile-avatar">
                <span class="profile-name"><?php echo $_navName; ?></span>
                <svg class="chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="profile-dropdown" id="profileDropdown">
                <div class="pd-header">
                    <img src="<?php echo $_navImg; ?>" alt="Profile">
                    <div>
                        <div class="pd-name"><?php echo $_navName; ?></div>
                        <div class="pd-email"><?php echo $_navEmail; ?></div>
                    </div>
                </div>
                <a href="index.php?action=profile">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    My Profile
                </a>
                <a href="index.php?action=my_bookings">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    My Bookings
                </a>
                <hr class="pd-divider">
                <a href="index.php?action=logout" class="logout-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#e74c3c" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
/* ── Shared Navbar JS (injected once per page) ── */
let _notifLoaded = false;

function toggleNotifDropdown(e) {
    e.stopPropagation();
    const dd = document.getElementById('notifDropdown');
    const isOpen = dd.classList.contains('open');
    _closeAllNavDropdowns();
    if (!isOpen) {
        dd.classList.add('open');
        if (!_notifLoaded) _loadNotifications();
    }
}

function toggleProfileDropdown(e) {
    e.stopPropagation();
    const pd  = document.getElementById('profileDropdown');
    const btn = document.getElementById('profileBtn');
    const isOpen = pd.classList.contains('open');
    _closeAllNavDropdowns();
    if (!isOpen) {
        pd.classList.add('open');
        btn.classList.add('open');
    }
}

function _closeAllNavDropdowns() {
    document.getElementById('notifDropdown')?.classList.remove('open');
    document.getElementById('profileDropdown')?.classList.remove('open');
    document.getElementById('profileBtn')?.classList.remove('open');
}

document.addEventListener('click', _closeAllNavDropdowns);

function _loadNotifications() {
    _notifLoaded = true;
    fetch('index.php?action=get_notifications')
        .then(r => r.json())
        .then(data => {
            const list  = document.getElementById('notifList');
            const items = data.notifications || [];
            if (!items.length) {
                list.innerHTML = '<div class="notif-empty">🔔 No notifications yet.</div>';
                return;
            }
            list.innerHTML = items.map(n => {
                const unread = n.is_read == 0;
                return `<div class="notif-item ${unread ? 'unread' : ''}">
                    <div class="notif-dot ${unread ? '' : 'read'}"></div>
                    <div class="notif-msg">${_escHtml(n.message)}</div>
                    <div class="notif-time">${_timeAgo(n.created_at)}</div>
                </div>`;
            }).join('');
        })
        .catch(() => {
            document.getElementById('notifList').innerHTML =
                '<div class="notif-empty">Could not load notifications.</div>';
        });
}

function markAllRead() {
    fetch('index.php?action=mark_notifications_read', { method: 'POST' })
        .then(() => {
            const badge = document.getElementById('notifBadge');
            if (badge) { badge.textContent = ''; badge.classList.add('hidden'); }
            document.querySelectorAll('.notif-item.unread').forEach(el => {
                el.classList.remove('unread');
                el.querySelector('.notif-dot')?.classList.add('read');
            });
            document.querySelector('.notif-bell-btn')?.classList.remove('has-unread');
        });
}

/* Auto-dismiss .eb-alert after 4s */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.eb-alert').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity .4s, max-height .4s, margin .4s, padding .4s';
            el.style.opacity = '0'; el.style.maxHeight = '0';
            el.style.marginBottom = '0'; el.style.paddingTop = '0'; el.style.paddingBottom = '0';
            el.style.overflow = 'hidden';
            setTimeout(() => el.remove(), 450);
        }, 4500);
    });
});

function _escHtml(s) { const d=document.createElement('div'); d.textContent=s; return d.innerHTML; }
function _timeAgo(ds) {
    const d = Math.floor((Date.now() - new Date(ds.replace(' ','T')).getTime()) / 1000);
    if (d < 60) return 'Just now';
    if (d < 3600) return Math.floor(d/60)+'m ago';
    if (d < 86400) return Math.floor(d/3600)+'h ago';
    return Math.floor(d/86400)+'d ago';
}
</script>
