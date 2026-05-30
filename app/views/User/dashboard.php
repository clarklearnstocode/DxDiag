<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook | Available Estates</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css?v=2.0">
    <link rel="stylesheet" href="assets/css/dashboard-page.css">
    <style>
        /* ── Announcement Banner ── */
        .eb-announcement {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
            border-left: 4px solid #cdaa56;
            border-radius: 10px;
            padding: 16px 20px;
            margin: 0 0 22px;
            position: relative;
            box-shadow: 0 4px 18px rgba(0,31,63,0.18);
        }
        .eb-announcement .ann-icon {
            font-size: 1.35rem;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .eb-announcement .ann-body { flex: 1; }
        .eb-announcement .ann-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #cdaa56;
            margin-bottom: 4px;
        }
        .eb-announcement .ann-text {
            color: #f0ece2;
            font-size: 0.92rem;
            line-height: 1.55;
        }
        .eb-announcement .ann-close {
            background: none;
            border: none;
            color: rgba(205,170,86,0.7);
            font-size: 1.1rem;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: color 0.2s;
            flex-shrink: 0;
        }
        .eb-announcement .ann-close:hover { color: #cdaa56; }

        /* ── Countdown Widget ── */
        .eb-countdown {
            background: linear-gradient(135deg, #005f56 0%, #007a6d 60%, #004f48 100%);
            border-radius: 14px;
            padding: 22px 28px;
            margin: 0 0 24px;
            display: flex;
            align-items: center;
            gap: 22px;
            box-shadow: 0 6px 28px rgba(0,95,86,0.22);
            overflow: hidden;
            position: relative;
        }
        .eb-countdown::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 160px; height: 160px;
            background: rgba(205,170,86,0.08);
            border-radius: 50%;
        }
        .eb-countdown .cd-img-wrap {
            width: 72px; height: 72px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid rgba(205,170,86,0.45);
        }
        .eb-countdown .cd-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .eb-countdown .cd-body { flex: 1; }
        .eb-countdown .cd-eyebrow {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            color: #cdaa56;
            margin-bottom: 4px;
        }
        .eb-countdown .cd-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
        }
        .eb-countdown .cd-timeline {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.72);
            margin-bottom: 8px;
        }
        .eb-countdown .cd-chip {
            display: inline-block;
            background: rgba(205,170,86,0.2);
            border: 1px solid rgba(205,170,86,0.5);
            color: #f0e0a0;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
        }
        .eb-countdown .cd-days-bubble {
            text-align: center;
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(205,170,86,0.4);
            border-radius: 12px;
            padding: 12px 20px;
            flex-shrink: 0;
        }
        .eb-countdown .cd-days-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: #cdaa56;
            line-height: 1;
        }
        .eb-countdown .cd-days-label {
            font-size: 0.65rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.6);
            margin-top: 3px;
        }

        /* ── Notification Bell ── */
        .notif-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .notif-bell-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px 8px;
            border-radius: 8px;
            color: #5f6b7a !important;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: background 0.18s, color 0.18s;
        }
        .notif-bell-btn:hover { background: rgba(0,95,86,0.08); color: #005f56 !important; }
        @keyframes bellRing {
            0%,100% { transform: rotate(0deg); }
            15%      { transform: rotate(18deg); }
            30%      { transform: rotate(-14deg); }
            45%      { transform: rotate(10deg); }
            60%      { transform: rotate(-6deg); }
            75%      { transform: rotate(3deg); }
        }
        .notif-bell-btn.has-unread svg {
            animation: bellRing 2.4s ease-in-out 0.6s 3;
            transform-origin: top center;
        }
        .notif-badge {
            position: absolute;
            top: 3px; right: 3px;
            background: #e74c3c;
            color: #fff;
            font-size: 0.6rem;
            font-weight: 700;
            min-width: 16px; height: 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
            line-height: 1;
            border: 2px solid #f8f6f1;
        }
        .notif-badge.notif-hidden { display: none; }

        /* ── Notification Dropdown ── */
        .notif-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 320px;
            background: #ffffff;
            border: 1px solid #e4dccb;
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(0,31,63,0.14);
            z-index: 999;
            overflow: hidden;
        }
        .notif-dropdown.open { display: block; }
        .notif-dropdown-header {
            padding: 14px 18px 10px;
            border-bottom: 1px solid #f0ebe0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .notif-dropdown-header span {
            font-weight: 700;
            font-size: 0.85rem;
            color: #1e2a3a !important;
        }
        .notif-dropdown-header .mark-read-btn {
            font-size: 0.72rem;
            color: #005f56 !important;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            padding: 0;
        }
        .notif-list {
            max-height: 280px;
            overflow-y: auto;
        }
        .notif-item {
            padding: 12px 18px;
            border-bottom: 1px solid #f7f3eb;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            transition: background 0.15s;
        }
        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: #fdf9f3; }
        .notif-item.unread { background: #f5faf9; }
        .notif-item.unread:hover { background: #ebf5f3; }
        .notif-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #005f56;
            flex-shrink: 0;
            margin-top: 5px;
        }
        .notif-dot.read { background: transparent; border: 1.5px solid #d0cabb; }
        .notif-msg {
            font-size: 0.8rem;
            color: #2a3a4a !important;
            line-height: 1.45;
            flex: 1;
        }
        .notif-time {
            font-size: 0.68rem;
            color: #9fa8b3 !important;
            white-space: nowrap;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .notif-empty {
            padding: 28px 18px;
            text-align: center;
            color: #9fa8b3;
            font-size: 0.82rem;
        }
        .notif-loading {
            padding: 20px;
            text-align: center;
            color: #9fa8b3;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <a href="index.php?action=home" class="logo">Estate<span>Book</span></a>

        <!-- Availability Filter -->
        <div class="filter-section">
            <span class="filter-label">Availability</span>
            <label class="radio-group">
                <input type="radio" name="availability" value="all" checked> All Properties
            </label>
            <label class="radio-group">
                <input type="radio" name="availability" value="available"> Available Only
            </label>
            <label class="radio-group">
                <input type="radio" name="availability" value="occupied"> Currently Occupied
            </label>
        </div>

        <!-- Amenities Filter -->
        <div class="filter-section">
            <span class="filter-label">Amenities</span>
            <label class="checkbox-group">
                <input type="checkbox" id="filter-pool"> <span>🏊 Has Swimming Pool</span>
            </label>
        </div>

        <!-- Guest Capacity -->
        <div class="filter-section">
            <span class="filter-label">Min. Guest Capacity</span>
            <input type="number" id="filterCapacity" class="sidebar-input" placeholder="e.g. 6" min="1">
        </div>

        <!-- Bathrooms -->
        <div class="filter-section">
            <span class="filter-label">Min. Bathrooms</span>
            <input type="number" id="filterBathrooms" class="sidebar-input" placeholder="e.g. 2" min="0">
        </div>

        <!-- Villa Size -->
        <div class="filter-section">
            <span class="filter-label">Min. Size (m²)</span>
            <input type="number" id="filterSize" class="sidebar-input" placeholder="e.g. 150" min="0">
        </div>

        <!-- Price Range Filter -->
        <div class="filter-section">
            <span class="filter-label">Price Range / Night</span>
            <div class="price-inputs">
                <input type="number" id="priceMin" placeholder="Min ₱" min="0">
                <span class="price-sep">—</span>
                <input type="number" id="priceMax" placeholder="Max ₱" min="0">
            </div>
            <button class="btn-apply" onclick="applyFilters()">Apply Filters</button>
            <button class="btn-reset" onclick="resetFilters()">Reset</button>
        </div>

        <!-- Results Count -->
        <div class="filter-section result-filter-section">
            <span class="filter-label">Showing</span>
            <span id="resultCount" class="result-count">—</span>
            <span class="result-suffix"> properties</span>
        </div>
    </aside>

    <main class="main-content">

        <!-- Top Navbar -->
        <div class="header-top">
            <div class="search-wrap">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="searchBar" class="search-bar" placeholder="Search by name or location...">
                <button class="search-clear search-clear-hidden" id="clearSearch" onclick="clearSearch()">✕</button>
            </div>

            <nav class="top-nav">
                <a href="index.php?action=dashboard" class="nav-item active">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Browse
                </a>
                <a href="index.php?action=my_bookings" class="nav-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    My Bookings
                </a>

                <!-- Notification Bell -->
                <div class="notif-wrapper" id="notifWrapper">
                    <button class="notif-bell-btn<?php echo ($unreadCount ?? 0) > 0 ? ' has-unread' : ''; ?>" onclick="toggleNotifications(event)" title="Notifications" aria-label="Notifications">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <span class="notif-badge <?php echo ($unreadCount ?? 0) === 0 ? 'notif-hidden' : ''; ?>" id="notifBadge">
                            <?php echo ($unreadCount ?? 0) > 0 ? ($unreadCount > 9 ? '9+' : $unreadCount) : ''; ?>
                        </span>
                    </button>
                    <div class="notif-dropdown" id="notifDropdown">
                        <div class="notif-dropdown-header">
                            <span>Notifications</span>
                            <button class="mark-read-btn" onclick="markAllRead()">Mark all read</button>
                        </div>
                        <div class="notif-list" id="notifList">
                            <div class="notif-loading">Loading…</div>
                        </div>
                    </div>
                </div>

                <div class="user-profile-container">
                    <button class="profile-btn" onclick="toggleDropdown(event)">
                        <img src="<?php echo !empty($_SESSION['user_image']) ? 'assets/img/uploads/' . htmlspecialchars($_SESSION['user_image']) : 'assets/img/user.png'; ?>" alt="Profile" class="profile-icon">
                        <span class="profile-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div id="profileDropdown" class="dropdown-menu">
                        <div class="dropdown-header">
                            <img src="<?php echo !empty($_SESSION['user_image']) ? 'assets/img/uploads/' . htmlspecialchars($_SESSION['user_image']) : 'assets/img/user.png'; ?>" alt="Profile" class="dropdown-profile-image">
                            <div>
                                <div class="dropdown-profile-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></div>
                                <div class="dropdown-profile-email"><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></div>
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
                        <hr class="dropdown-divider">
                        <a href="index.php?action=logout" class="dropdown-logout-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ff4c4c" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Logout
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- ── Feature 3: Announcement Banner ── -->
        <?php if (!empty($announcement)): ?>
        <div class="eb-announcement" id="announcementBanner" data-ann-id="<?php echo (int)$announcement['id']; ?>">
            <span class="ann-icon">📢</span>
            <div class="ann-body">
                <div class="ann-label">Official Announcement</div>
                <div class="ann-text"><?php echo htmlspecialchars($announcement['message']); ?></div>
            </div>
            <button class="ann-close" onclick="this.closest('.eb-announcement').style.display='none'" title="Dismiss">✕</button>
        </div>
        <?php endif; ?>

        <!-- ── Feature 2: Upcoming Stay Countdown Widget ── -->
        <?php if (!empty($countdownWidget)): ?>
        <div class="eb-countdown">
            <div class="cd-img-wrap">
                <img src="assets/img/<?php echo htmlspecialchars($countdownWidget['image_path']); ?>"
                     alt="<?php echo htmlspecialchars($countdownWidget['property_name']); ?>"
                     onerror="this.src='assets/img/villa1.png'">
            </div>
            <div class="cd-body">
                <div class="cd-eyebrow">Your Next Elite Stay</div>
                <div class="cd-title"><?php echo htmlspecialchars($countdownWidget['property_name']); ?></div>
                <div class="cd-timeline">
                    📅 <?php echo htmlspecialchars($countdownWidget['check_in']); ?>
                    &nbsp;→&nbsp;
                    <?php echo htmlspecialchars($countdownWidget['check_out']); ?>
                    &nbsp;·&nbsp; <?php echo (int)$countdownWidget['nights']; ?> night<?php echo $countdownWidget['nights'] !== 1 ? 's' : ''; ?>
                </div>
                <span class="cd-chip">✨ <?php echo htmlspecialchars($countdownWidget['countdown_text']); ?></span>
            </div>
            <div class="cd-days-bubble">
                <div class="cd-days-num"><?php echo (int)$countdownWidget['days_left']; ?></div>
                <div class="cd-days-label"><?php echo $countdownWidget['days_left'] === 1 ? 'Day' : 'Days'; ?> Left</div>
            </div>
        </div>
        <?php endif; ?>

        <div class="page-heading">
            <h2>Available Estates <span id="noResults" class="no-results-text">— No properties match your filters</span></h2>
            <p class="page-subtext">Luxury villas across Bacolod & Negros Occidental</p>
        </div>

        <div class="property-grid" id="propertyGrid">
            <?php if (!empty($properties)): ?>
                <?php foreach ($properties as $property): ?>
                    <?php
                        $isOccupied = strtolower($property['Status'] ?? '') === 'occupied';
                        $hasPool    = !empty($property['Has_pool']) && $property['Has_pool'] != '0';
                    ?>
                    <div class="property-card"
                         data-name="<?php echo strtolower(htmlspecialchars($property['Property_Name'])); ?>"
                         data-location="<?php echo strtolower(htmlspecialchars($property['Property_location'])); ?>"
                         data-status="<?php echo strtolower($property['Status'] ?? 'available'); ?>"
                         data-pool="<?php echo $hasPool ? '1' : '0'; ?>"
                         data-bathrooms="<?php echo intval($property['Property_bathrooms'] ?? 0); ?>"
                         data-capacity="<?php echo intval($property['Property_capacity'] ?? 0); ?>"
                         data-size="<?php echo intval($property['Property_size'] ?? 0); ?>"
                         data-rate="<?php echo floatval($property['Property_rate'] ?? 0); ?>">

                        <div class="card-image-wrap">
                            <img src="assets/img/<?php echo htmlspecialchars($property['image_path']); ?>"
                                 alt="<?php echo htmlspecialchars($property['Property_Name']); ?>"
                                 class="property-image"
                                 onerror="this.onerror=null;this.src='assets/img/villa1.png'">
                            <div class="card-status-tag <?php echo $isOccupied ? 'tag-occupied' : 'tag-available'; ?>">
                                <?php echo $isOccupied ? '🔴 Occupied' : '🟢 Available'; ?>
                            </div>
                            <?php if ($hasPool): ?>
                                <div class="card-pool-tag">🏊 Pool</div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body">
                            <div class="card-top-row">
                                <h3 class="card-title"><?php echo htmlspecialchars($property['Property_Name']); ?></h3>
                                <span class="card-rate">₱<?php echo number_format($property['Property_rate']); ?><small>/night</small></span>
                            </div>
                            <span class="card-loc">📍 <?php echo htmlspecialchars($property['Property_location']); ?></span>
                            <div class="property-specs">
                                <span title="Size">📐 <?php echo $property['Property_size'] ?? '—'; ?>m²</span>
                                <span title="Capacity">👥 <?php echo $property['Property_capacity'] ?? '—'; ?> guests</span>
                                <span title="Bathrooms">🚿 <?php echo $property['Property_bathrooms'] ?? '—'; ?> bath</span>
                            </div>
                            <!-- All properties always show View & Reserve (non-blocking model) -->
                            <a href="index.php?action=view_property&id=<?php echo $property['Property_Id']; ?>" class="btn-view">
                                View & Reserve →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-properties-text">No properties found in the database.</p>
            <?php endif; ?>
        </div>

    </main>

<script src="assets/js/dashboard.js"></script>
<script>
        /* ── Notification Bell Logic ── */
        let notifLoaded = false;

        function toggleNotifications(e) {
            e.stopPropagation();
            const dd = document.getElementById('notifDropdown');
            const isOpen = dd.classList.contains('open');
            closeAllDropdowns();
            if (!isOpen) {
                dd.classList.add('open');
                if (!notifLoaded) loadNotifications();
            }
        }

        function loadNotifications() {
            notifLoaded = true;
            fetch('index.php?action=get_notifications')
                .then(r => r.json())
                .then(data => {
                    const list = document.getElementById('notifList');
                    const items = data.notifications || [];
                    if (items.length === 0) {
                        list.innerHTML = '<div class="notif-empty">🔔 No notifications yet.</div>';
                        return;
                    }
                    list.innerHTML = items.map(n => {
                        const isUnread = n.is_read == 0;
                        const timeAgo  = formatTimeAgo(n.created_at);
                        return `<div class="notif-item ${isUnread ? 'unread' : ''}">
                            <div class="notif-dot ${isUnread ? '' : 'read'}"></div>
                            <div class="notif-msg">${escapeHtml(n.message)}</div>
                            <div class="notif-time">${timeAgo}</div>
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
                    badge.textContent = '';
                    badge.classList.add('notif-hidden');
                    document.querySelectorAll('.notif-item.unread').forEach(el => {
                        el.classList.remove('unread');
                        const dot = el.querySelector('.notif-dot');
                        if (dot) dot.classList.add('read');
                    });
                });
        }

        function closeAllDropdowns() {
            document.getElementById('notifDropdown')?.classList.remove('open');
            const pd = document.getElementById('profileDropdown');
            if (pd) pd.style.display = 'none';
        }

        document.addEventListener('click', (e) => {
            if (!document.getElementById('notifWrapper')?.contains(e.target)) {
                document.getElementById('notifDropdown')?.classList.remove('open');
            }
        });

        /* ── Announcement persistent dismissal ── */
        (function() {
            const banner = document.getElementById('announcementBanner');
            if (!banner) return;
            const annId  = banner.getAttribute('data-ann-id');
            const key    = 'eb_dismissed_ann_' + annId;
            // Hide immediately if already dismissed
            if (localStorage.getItem(key) === '1') {
                banner.style.display = 'none';
                return;
            }
            // Wire the close button to persist dismissal
            const closeBtn = banner.querySelector('.ann-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    localStorage.setItem(key, '1');
                    banner.style.transition = 'opacity 0.3s, transform 0.3s';
                    banner.style.opacity    = '0';
                    banner.style.transform  = 'translateY(-6px)';
                    setTimeout(() => banner.style.display = 'none', 320);
                });
            }
        })();

        function escapeHtml(str) {
            const d = document.createElement('div');
            d.textContent = str;
            return d.innerHTML;
        }

        function formatTimeAgo(dateStr) {
            const now  = Date.now();
            const then = new Date(dateStr.replace(' ', 'T')).getTime();
            const diff = Math.floor((now - then) / 1000);
            if (diff < 60)    return 'Just now';
            if (diff < 3600)  return Math.floor(diff / 60) + 'm ago';
            if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
            return Math.floor(diff / 86400) + 'd ago';
        }
</script>
</body>
</html>
