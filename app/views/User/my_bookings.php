<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/my-bookings.css">
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
                <hr class="dropdown-divider">
                <a href="index.php?action=logout" class="logout-link-danger">Logout</a>
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

<script src="assets/js/my-bookings.js"></script>
</body>
</html>
