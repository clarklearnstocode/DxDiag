<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmed | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/booking-confirmation.css">
</head>
<body>
    <div class="confirm-wrap">

        <!-- Animated success icon -->
        <div class="success-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#2ecc71" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>

        <h1 class="confirm-title">Booking Submitted!</h1>
        <p class="confirm-sub">
            Thank you, <strong class="confirm-sub-user"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?></strong>!<br>
            Your reservation for <strong class="confirm-sub-property"><?php echo htmlspecialchars($booking['Property_Name']); ?></strong> is now pending review.
        </p>

        <!-- Property card with summary -->
        <div class="prop-card">
            <img src="assets/img/<?php echo htmlspecialchars($booking['image_path'] ?? 'villa1.png'); ?>"
                 alt="<?php echo htmlspecialchars($booking['Property_Name']); ?>"
                 class="prop-img">
            <div class="prop-body">
                <div class="prop-name"><?php echo htmlspecialchars($booking['Property_Name']); ?></div>
                <div class="prop-loc">📍 <?php echo htmlspecialchars($booking['Property_location']); ?></div>

                <?php
                    $ci = $booking['Check_In'];
                    $co = $booking['Check_Out'];
                    $nights = 0;
                    if ($ci && $co && $ci !== '0000-00-00' && $co !== '0000-00-00') {
                        $d1 = new DateTime($ci); $d2 = new DateTime($co);
                        $nights = $d1->diff($d2)->days;
                    }
                ?>

                <div class="summary-row">
                    <span class="summary-label">Check-In</span>
                    <span class="summary-value">
                        <?php
                            if ($ci && $ci !== '0000-00-00') {
                                echo date('D, M d, Y', strtotime($ci));
                                if (!empty($booking['Check_In_Time'])) echo ' · ' . date('g:i A', strtotime($booking['Check_In_Time']));
                            } else { echo '—'; }
                        ?>
                    </span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Check-Out</span>
                    <span class="summary-value">
                        <?php
                            if ($co && $co !== '0000-00-00') {
                                echo date('D, M d, Y', strtotime($co));
                                if (!empty($booking['Check_Out_Time'])) echo ' · ' . date('g:i A', strtotime($booking['Check_Out_Time']));
                            } else { echo '—'; }
                        ?>
                    </span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Duration</span>
                    <span class="summary-value"><?php echo $nights; ?> night<?php echo $nights !== 1 ? 's' : ''; ?></span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Payment Method</span>
                    <span class="summary-value"><?php echo htmlspecialchars($booking['Payment_Method'] ?? '—'); ?></span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total Paid</span>
                    <span class="summary-value highlight">₱<?php echo number_format($booking['Amount'] ?? 0); ?></span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Reservation Status</span>
                    <span class="summary-value"><span class="status-pill">Pending Review</span></span>
                </div>
            </div>
        </div>

        <!-- Notice -->
        <div class="notice">
            <strong>What happens next?</strong> Our admin team will review your booking and confirm it shortly.
            You can track the status or make changes on your <strong>My Bookings</strong> page.
            Cancellations are only allowed while your booking is still <strong>Pending</strong>.
        </div>

        <!-- Action buttons -->
        <div class="btn-row">
            <a href="index.php?action=my_bookings" class="btn btn-primary">View My Bookings</a>
            <a href="index.php?action=dashboard"   class="btn btn-ghost">Browse More Estates</a>
        </div>

        <div class="booking-id">Booking Reference: <span>#<?php echo str_pad($booking['Booking_Id'], 5, '0', STR_PAD_LEFT); ?></span></div>

    </div>
</body>
</html>
