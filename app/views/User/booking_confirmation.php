<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmed | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #080808; --card: #141414; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--dark); color: white; font-family: 'DM Sans', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }

        .confirm-wrap { width: 100%; max-width: 560px; }

        /* Animated checkmark */
        .success-icon {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: rgba(46,204,113,0.12);
            border: 2px solid rgba(46,204,113,0.3);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 28px;
            animation: popIn 0.5s cubic-bezier(0.34,1.56,0.64,1) both;
        }
        .success-icon svg { width: 36px; height: 36px; }
        @keyframes popIn { from { transform: scale(0.4); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        .confirm-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.1rem;
            text-align: center;
            margin-bottom: 8px;
        }
        .confirm-sub {
            text-align: center;
            color: #555;
            font-size: 0.9rem;
            margin-bottom: 36px;
        }

        /* Property card */
        .prop-card {
            background: var(--card);
            border: 1px solid #222;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .prop-img { width: 100%; height: 200px; object-fit: cover; display: block; }
        .prop-body { padding: 22px 24px; }
        .prop-name { font-family: 'Playfair Display', serif; font-size: 1.4rem; margin-bottom: 4px; }
        .prop-loc  { color: #555; font-size: 0.85rem; margin-bottom: 18px; }

        /* Summary rows */
        .summary-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 11px 0;
            border-bottom: 1px solid #1e1e1e;
            font-size: 0.875rem;
        }
        .summary-row:last-child { border-bottom: none; }
        .summary-label { color: #555; }
        .summary-value { font-weight: 700; color: white; text-align: right; }
        .summary-value.highlight { color: var(--primary); font-size: 1.05rem; }

        /* Status pill */
        .status-pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: rgba(241,196,15,0.12);
            color: #f1c40f;
        }

        /* Notice box */
        .notice {
            background: rgba(201,160,122,0.08);
            border: 1px solid rgba(201,160,122,0.2);
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 0.82rem;
            color: #a07c5a;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .notice strong { color: var(--primary); }

        /* Buttons */
        .btn-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .btn {
            display: block; text-align: center; text-decoration: none;
            padding: 15px; border-radius: 12px;
            font-weight: 700; font-size: 0.875rem;
            transition: 0.25s; font-family: 'DM Sans', sans-serif;
        }
        .btn-primary { background: var(--primary); color: #000; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(201,160,122,0.25); }
        .btn-ghost  { background: #161616; color: #888; border: 1px solid #222; }
        .btn-ghost:hover  { color: white; border-color: #444; }

        /* Booking ID badge */
        .booking-id {
            text-align: center;
            font-size: 0.75rem;
            color: #333;
            margin-top: 18px;
        }
        .booking-id span { color: #555; font-weight: 700; }
    </style>
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
            Thank you, <strong style="color:white;"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?></strong>!<br>
            Your reservation for <strong style="color:var(--primary);"><?php echo htmlspecialchars($booking['Property_Name']); ?></strong> is now pending review.
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
