<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #0a0a0a; --card: #161616; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--dark); color: white; font-family: 'Inter', sans-serif; padding: 50px; }
        .container { max-width: 860px; margin: 0 auto; }

        .header-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .back-btn { color: #888; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; transition: 0.2s; }
        .back-btn:hover { color: var(--primary); }

        .user-profile-container { position: relative; }
        .profile-btn { background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; }
        .profile-icon { width: 40px; height: 40px; border-radius: 50%; border: 2px solid transparent; transition: 0.3s; object-fit: cover; }
        .profile-btn:hover .profile-icon { border-color: var(--primary); }
        .dropdown-menu { display: none; position: absolute; right: 0; top: 50px; background: #161616; border: 1px solid #222; border-radius: 12px; min-width: 160px; z-index: 1000; box-shadow: 0 10px 30px rgba(0,0,0,0.5); overflow: hidden; }
        .dropdown-menu a { color: #ccc; padding: 12px 20px; text-decoration: none; display: block; font-size: 0.85rem; transition: 0.2s; }
        .dropdown-menu a:hover { background: #1f1f1f; color: var(--primary); }
        .show { display: block; }

        h1 { font-size: 2.5rem; margin-bottom: 8px; font-weight: 800; }
        .subtitle { color: #555; font-size: 0.9rem; margin-bottom: 35px; }

        .alert { padding: 12px 18px; border-radius: 10px; margin-bottom: 25px; font-size: 0.85rem; font-weight: 600; }
        .alert-success { background: rgba(46,204,113,0.1); color: #2ecc71; border: 1px solid rgba(46,204,113,0.25); }

        .booking-item {
            background: var(--card);
            border: 1px solid #222;
            border-radius: 20px;
            padding: 25px 28px;
            display: grid;
            grid-template-columns: 60px 1fr auto;
            gap: 20px;
            align-items: center;
            margin-bottom: 16px;
            transition: 0.3s;
            text-decoration: none;
            color: white;
        }
        .booking-item:hover { border-color: var(--primary); transform: translateX(6px); }

        .prop-img { width: 60px; height: 60px; border-radius: 12px; object-fit: cover; border: 1px solid #333; }

        .prop-name { font-weight: 700; font-size: 1rem; margin-bottom: 4px; }
        .prop-loc { color: #666; font-size: 0.8rem; margin-bottom: 6px; }
        .date-range { font-size: 0.8rem; color: #888; }
        .date-range span { color: var(--primary); font-weight: 700; }

        .right-info { text-align: right; }
        .amount { font-size: 1.15rem; font-weight: 800; color: white; margin-bottom: 8px; }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-pending  { background: rgba(241,196,15,0.1); color: #f1c40f; }
        .status-confirmed { background: rgba(46,204,113,0.1); color: #2ecc71; }
        .status-rejected { background: rgba(231,76,60,0.1); color: #e74c3c; }

        .empty-state { text-align: center; padding: 60px 30px; background: var(--card); border-radius: 20px; border: 1px dashed #2a2a2a; }
        .empty-state p { color: #555; margin-bottom: 20px; }
        .empty-state a { color: var(--primary); text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container">

        <div class="header-nav">
            <a href="index.php?action=dashboard" class="back-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to Dashboard
            </a>
            <div class="user-profile-container">
                <button class="profile-btn" onclick="toggleDropdown()">
                    <img src="assets/img/user.png" alt="Profile" class="profile-icon">
                </button>
                <div id="profileDropdown" class="dropdown-menu">
                    <a href="index.php?action=profile">My Profile</a>
                    <a href="index.php?action=my_bookings">My Bookings</a>
                    <hr style="border:0; border-top:1px solid #222; margin: 5px 0;">
                    <a href="index.php?action=logout" style="color:#ff4444;">Logout</a>
                </div>
            </div>
        </div>

        <h1>My Bookings</h1>
        <p class="subtitle">All your property reservations in one place.</p>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="alert alert-success">✓ Your reservation has been submitted! It will be confirmed shortly by the admin.</div>
        <?php endif; ?>

        <?php if (!empty($bookings)): ?>
            <?php foreach ($bookings as $booking): ?>
                <?php
                    $ci = $booking['Check_In'] ?? null;
                    $co = $booking['Check_Out'] ?? null;
                    $hasDateRange = ($ci && $ci !== '0000-00-00' && $co && $co !== '0000-00-00');
                    $nights = 0;
                    if ($hasDateRange) {
                        $d1 = new DateTime($ci); $d2 = new DateTime($co);
                        $nights = $d1->diff($d2)->days;
                    }
                    $status = strtolower($booking['Reservation_Status'] ?? 'pending');
                    $badgeClass = $status === 'confirmed' ? 'status-confirmed' : ($status === 'rejected' ? 'status-rejected' : 'status-pending');
                ?>
                <div class="booking-item">
                    <img src="assets/img/<?php echo htmlspecialchars($booking['image_path'] ?? 'villa1.png'); ?>"
                         alt="Property" class="prop-img">
                    <div>
                        <div class="prop-name"><?php echo htmlspecialchars($booking['Property_Name']); ?></div>
                        <div class="prop-loc">📍 <?php echo htmlspecialchars($booking['Property_location']); ?></div>
                        <div class="date-range">
                            <?php if ($hasDateRange): ?>
                                <span>Check-in:</span> <?php echo date('M d, Y', strtotime($ci)); ?>
                                &nbsp;→&nbsp;
                                <span>Check-out:</span> <?php echo date('M d, Y', strtotime($co)); ?>
                                (<?php echo $nights; ?> night<?php echo $nights !== 1 ? 's' : ''; ?>)
                            <?php else: ?>
                                Dates not set
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="right-info">
                        <div class="amount">₱<?php echo number_format($booking['Amount'] ?? 0); ?></div>
                        <span class="status-badge <?php echo $badgeClass; ?>">
                            <?php echo htmlspecialchars(ucfirst($booking['Reservation_Status'] ?? 'Pending')); ?>
                        </span>
                        <?php if (!empty($booking['Payment_Method'])): ?>
                            <div style="font-size:0.75rem; color:#444; margin-top:6px;"><?php echo htmlspecialchars($booking['Payment_Method']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <p style="font-size:2rem; margin-bottom:12px;">🏡</p>
                <p>No reservations yet. Start exploring Bacolod estates!</p>
                <a href="index.php?action=dashboard">Explore Properties →</a>
            </div>
        <?php endif; ?>

    </div>

    <script>
        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("show");
        }
        window.onclick = function(e) {
            if (!e.target.matches('.profile-btn') && !e.target.matches('.profile-icon')) {
                var d = document.getElementById("profileDropdown");
                if (d && d.classList.contains('show')) d.classList.remove('show');
            }
        }
    </script>
</body>
</html>
