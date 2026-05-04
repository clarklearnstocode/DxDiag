<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #080808; --card: #141414; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--dark); color: white; font-family: 'DM Sans', sans-serif; padding: 50px; }

        .container { max-width: 900px; margin: 0 auto; }

        /* Top nav */
        .header-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .back-btn { color: #555; text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 7px; transition: 0.2s; }
        .back-btn:hover { color: var(--primary); }
        .user-profile-container { position: relative; }
        .profile-btn { background: none; border: none; cursor: pointer; padding: 0; }
        .profile-icon { width: 38px; height: 38px; border-radius: 50%; border: 2px solid #222; object-fit: cover; transition: 0.2s; }
        .profile-btn:hover .profile-icon { border-color: var(--primary); }
        .dropdown-menu { display: none; position: absolute; right: 0; top: 48px; background: #141414; border: 1px solid #222; border-radius: 12px; min-width: 160px; z-index: 1000; box-shadow: 0 12px 35px rgba(0,0,0,0.6); overflow: hidden; }
        .dropdown-menu a { color: #888; padding: 12px 18px; text-decoration: none; display: block; font-size: 0.85rem; transition: 0.2s; }
        .dropdown-menu a:hover { background: #1c1c1c; color: var(--primary); }
        .show { display: block; }

        /* Page title */
        h1 { font-family: 'Playfair Display', serif; font-size: 2.2rem; margin-bottom: 5px; }
        .subtitle { color: #444; font-size: 0.875rem; margin-bottom: 32px; }

        /* Alerts */
        .alert { padding: 12px 18px; border-radius: 10px; margin-bottom: 24px; font-size: 0.85rem; font-weight: 600; }
        .alert-success { background: rgba(46,204,113,0.09); color: #2ecc71; border: 1px solid rgba(46,204,113,0.2); }
        .alert-error   { background: rgba(231,76,60,0.09);  color: #e74c3c; border: 1px solid rgba(231,76,60,0.2); }

        /* Booking card */
        .booking-card {
            background: var(--card);
            border: 1px solid #1e1e1e;
            border-radius: 18px;
            padding: 0;
            margin-bottom: 16px;
            overflow: hidden;
            transition: border-color 0.2s;
        }
        .booking-card:hover { border-color: #2a2a2a; }

        .card-top {
            display: grid;
            grid-template-columns: 72px 1fr auto;
            gap: 18px;
            align-items: center;
            padding: 22px 24px;
        }

        .prop-img { width: 72px; height: 60px; border-radius: 11px; object-fit: cover; border: 1px solid #2a2a2a; flex-shrink: 0; }

        .prop-name { font-weight: 700; font-size: 1rem; margin-bottom: 4px; }
        .prop-loc  { font-size: 0.78rem; color: #555; margin-bottom: 7px; }
        .date-range { font-size: 0.8rem; color: #666; }
        .date-range em { color: var(--primary); font-style: normal; font-weight: 700; }

        .right-col { text-align: right; flex-shrink: 0; }
        .amount { font-size: 1.1rem; font-weight: 800; margin-bottom: 8px; }

        /* Status badges */
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-pending   { background: rgba(241,196,15,0.1);  color: #f1c40f; }
        .badge-confirmed { background: rgba(46,204,113,0.1);  color: #2ecc71; }
        .badge-rejected  { background: rgba(231,76,60,0.1);   color: #e74c3c; }
        .badge-cancelled { background: rgba(120,120,120,0.1); color: #777; }
        .badge-completed { background: rgba(100,149,237,0.1); color: #6495ed; }

        .payment-method { font-size: 0.72rem; color: #383838; margin-top: 6px; }

        /* Action footer — only visible on pending bookings */
        .card-actions {
            border-top: 1px solid #1a1a1a;
            padding: 14px 24px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background: #0f0f0f;
        }
        .btn-edit-booking {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(201,160,122,0.1); color: var(--primary);
            border: 1px solid rgba(201,160,122,0.2);
            padding: 8px 16px; border-radius: 8px;
            text-decoration: none; font-size: 0.78rem; font-weight: 700;
            transition: 0.2s;
        }
        .btn-edit-booking:hover { background: var(--primary); color: #000; }
        .btn-cancel-booking {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(231,76,60,0.08); color: #e74c3c;
            border: 1px solid rgba(231,76,60,0.2);
            padding: 8px 16px; border-radius: 8px;
            text-decoration: none; font-size: 0.78rem; font-weight: 700;
            transition: 0.2s;
        }
        .btn-cancel-booking:hover { background: #e74c3c; color: white; border-color: #e74c3c; }
        .pending-note { font-size: 0.72rem; color: #333; display: flex; align-items: center; gap: 5px; margin-right: auto; }

        /* Empty state */
        .empty-state { text-align: center; padding: 60px 30px; background: var(--card); border-radius: 20px; border: 1px dashed #1e1e1e; }
        .empty-state .emoji { font-size: 2.5rem; margin-bottom: 14px; }
        .empty-state p { color: #444; margin-bottom: 20px; font-size: 0.9rem; }
        .empty-state a { color: var(--primary); text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
<div class="container">

    <div class="header-nav">
        <a href="index.php?action=dashboard" class="back-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to Dashboard
        </a>
        <div class="user-profile-container">
            <button class="profile-btn" onclick="toggleDropdown()">
                <img src="<?php echo !empty($_SESSION['user_image']) ? 'assets/img/uploads/'.htmlspecialchars($_SESSION['user_image']) : 'assets/img/user.png'; ?>"
                     alt="Profile" class="profile-icon">
            </button>
            <div id="profileDropdown" class="dropdown-menu">
                <a href="index.php?action=profile">My Profile</a>
                <a href="index.php?action=my_bookings">My Bookings</a>
                <hr style="border:0;border-top:1px solid #1e1e1e;margin:4px 0;">
                <a href="index.php?action=logout" style="color:#ff4444;">Logout</a>
            </div>
        </div>
    </div>

    <h1>My Bookings</h1>
    <p class="subtitle">All your property reservations. Pending bookings can be modified or cancelled.</p>

    <?php if (isset($_GET['success'])): ?>
        <?php $msgs = ['cancelled'=>'✓ Booking cancelled successfully.','updated'=>'✓ Booking updated successfully.']; ?>
        <div class="alert alert-success"><?php echo $msgs[$_GET['success']] ?? '✓ Done.'; ?></div>
    <?php elseif (isset($_GET['error'])): ?>
        <?php $errs = ['cannot_cancel'=>'✗ Only pending bookings can be cancelled.','cannot_edit'=>'✗ Only pending bookings can be edited.']; ?>
        <div class="alert alert-error"><?php echo $errs[$_GET['error']] ?? '✗ Something went wrong.'; ?></div>
    <?php endif; ?>

    <?php if (!empty($bookings)): ?>
        <?php foreach ($bookings as $b): ?>
        <?php
            $ci     = $b['Check_In']  ?? null;
            $co     = $b['Check_Out'] ?? null;
            $hasDates = ($ci && $ci !== '0000-00-00' && $co && $co !== '0000-00-00');
            $nights = 0;
            if ($hasDates) { $d1=new DateTime($ci); $d2=new DateTime($co); $nights=$d1->diff($d2)->days; }
            $status   = strtolower($b['Reservation_Status'] ?? 'pending');
            $isPending = $status === 'pending';
            $badgeMap  = ['pending'=>'badge-pending','confirmed'=>'badge-confirmed','rejected'=>'badge-rejected','cancelled'=>'badge-cancelled','completed'=>'badge-completed'];
            $badgeClass= $badgeMap[$status] ?? 'badge-pending';
        ?>
        <div class="booking-card">
            <div class="card-top">
                <!-- Thumbnail -->
                <img src="assets/img/<?php echo htmlspecialchars($b['image_path'] ?? 'villa1.png'); ?>"
                     alt="" class="prop-img">

                <!-- Info -->
                <div>
                    <div class="prop-name"><?php echo htmlspecialchars($b['Property_Name']); ?></div>
                    <div class="prop-loc">📍 <?php echo htmlspecialchars($b['Property_location']); ?></div>
                    <div class="date-range">
                        <?php if ($hasDates): ?>
                            <em>In:</em>
                            <?php
                                echo date('M d, Y', strtotime($ci));
                                if (!empty($b['Check_In_Time'])) echo ' · ' . date('g:i A', strtotime($b['Check_In_Time']));
                            ?>
                            &nbsp;→&nbsp;
                            <em>Out:</em>
                            <?php
                                echo date('M d, Y', strtotime($co));
                                if (!empty($b['Check_Out_Time'])) echo ' · ' . date('g:i A', strtotime($b['Check_Out_Time']));
                            ?>
                            &nbsp;·&nbsp; <?php echo $nights; ?> night<?php echo $nights !== 1 ? 's' : ''; ?>
                        <?php else: ?>
                            Dates not set
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Right: amount + badge -->
                <div class="right-col">
                    <div class="amount">₱<?php echo number_format($b['Amount'] ?? 0); ?></div>
                    <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars(ucfirst($b['Reservation_Status'] ?? 'Pending')); ?></span>
                    <?php if (!empty($b['Payment_Method'])): ?>
                        <div class="payment-method"><?php echo htmlspecialchars($b['Payment_Method']); ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action row — only for pending -->
            <?php if ($isPending): ?>
            <div class="card-actions">
                <span class="pending-note">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Changes allowed while Pending
                </span>
                <a href="index.php?action=edit_booking&id=<?php echo $b['Booking_Id']; ?>" class="btn-edit-booking">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </a>
                <a href="index.php?action=cancel_booking&id=<?php echo $b['Booking_Id']; ?>"
                   class="btn-cancel-booking"
                   onclick="return confirm('Cancel this booking for <?php echo htmlspecialchars(addslashes($b['Property_Name'])); ?>?\n\nThis action cannot be undone.');">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Cancel
                </a>
            </div>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>

    <?php else: ?>
        <div class="empty-state">
            <div class="emoji">🏡</div>
            <p>No reservations yet. Start exploring Bacolod estates!</p>
            <a href="index.php?action=dashboard">Browse Properties →</a>
        </div>
    <?php endif; ?>

</div>

<script>
function toggleDropdown() {
    document.getElementById("profileDropdown").classList.toggle("show");
}
window.onclick = function(e) {
    if (!e.target.closest('.user-profile-container')) {
        var d = document.getElementById("profileDropdown");
        if (d && d.classList.contains('show')) d.classList.remove('show');
    }
}
</script>
</body>
</html>
