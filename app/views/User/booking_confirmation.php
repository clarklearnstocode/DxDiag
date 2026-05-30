<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Submitted | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/user-layout.css?v=1.0">
    <style>
        .confirm-wrap { max-width: 560px; margin: 0 auto; padding: 50px 20px 60px; text-align: center; }
        .success-ring {
            width: 80px; height: 80px; border-radius: 50%;
            background: linear-gradient(135deg, #edfaf5, #c5f0dd);
            border: 3px solid #2ecc71;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            animation: popIn 0.45s cubic-bezier(0.34,1.56,0.64,1);
        }
        @keyframes popIn { from { transform: scale(0.3); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .confirm-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.9rem; font-weight: 700; color: #1e2a3a; margin-bottom: 10px;
        }
        .confirm-sub { font-size: 0.93rem; color: #6b7685; line-height: 1.6; margin-bottom: 32px; }
        .confirm-sub strong { color: #005f56; }

        .prop-card {
            background: #fff; border: 1px solid #e4dccb;
            border-radius: 14px; overflow: hidden;
            text-align: left; margin-bottom: 22px;
            box-shadow: 0 4px 18px rgba(0,31,63,0.08);
        }
        .prop-card img { width: 100%; height: 180px; object-fit: cover; display: block; }
        .prop-card-body { padding: 20px 22px; }
        .prop-card-name { font-family: 'Playfair Display', serif; font-size: 1.15rem; font-weight: 700; color: #1e2a3a; margin-bottom: 4px; }
        .prop-card-loc  { font-size: 0.82rem; color: #6b7685; margin-bottom: 18px; }
        .summary-row { display: flex; justify-content: space-between; align-items: baseline; padding: 9px 0; border-bottom: 1px solid #f5f0e8; font-size: 0.86rem; }
        .summary-row:last-child { border-bottom: none; }
        .summary-label { color: #6b7685; }
        .summary-value { font-weight: 600; color: #1e2a3a; }
        .summary-value.highlight { color: #005f56; font-family: 'Playfair Display', serif; font-size: 1.05rem; }
        .status-pill { background: #fff8e6; color: #b07800; border: 1px solid #f0d080; padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }

        .next-steps {
            background: linear-gradient(135deg, #f5faf9, #edf7f4);
            border: 1px solid #a8dfcc; border-radius: 12px;
            padding: 16px 20px; text-align: left; margin-bottom: 26px;
            font-size: 0.84rem; color: #2a5c49; line-height: 1.65;
        }
        .next-steps strong { color: #005f56; }

        .btn-row { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-bottom: 22px; }
        .ref-tag { font-size: 0.78rem; color: #9fa8b3; }
        .ref-tag span { font-weight: 700; color: #6b7685; letter-spacing: 0.5px; }
    </style>
</head>
<body>
<?php $activePage = ''; require_once __DIR__ . '/_user_navbar.php'; ?>

<div class="confirm-wrap">
    <div class="success-ring">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#2ecc71" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>

    <h1 class="confirm-title">Booking Submitted!</h1>
    <p class="confirm-sub">
        Thank you, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest'); ?></strong>!<br>
        Your reservation for <strong><?php echo htmlspecialchars($booking['Property_Name']); ?></strong> is now pending admin review.
    </p>

    <div class="prop-card">
        <img src="assets/img/<?php echo htmlspecialchars($booking['image_path'] ?? 'villa1.png'); ?>"
             alt="<?php echo htmlspecialchars($booking['Property_Name']); ?>"
             onerror="this.src='assets/img/villa1.png'">
        <div class="prop-card-body">
            <div class="prop-card-name"><?php echo htmlspecialchars($booking['Property_Name']); ?></div>
            <div class="prop-card-loc">📍 <?php echo htmlspecialchars($booking['Property_location']); ?></div>

            <?php
                $ci = $booking['Check_In'];  $co = $booking['Check_Out'];
                $nights = 0;
                if ($ci && $co && $ci !== '0000-00-00' && $co !== '0000-00-00') {
                    $nights = (new DateTime($ci))->diff(new DateTime($co))->days;
                }
            ?>
            <div class="summary-row">
                <span class="summary-label">Check-In</span>
                <span class="summary-value">
                    <?php echo $ci && $ci !== '0000-00-00' ? date('D, M d, Y', strtotime($ci)) : '—';
                    if (!empty($booking['Check_In_Time'])) echo ' · '.date('g:i A', strtotime($booking['Check_In_Time'])); ?>
                </span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Check-Out</span>
                <span class="summary-value">
                    <?php echo $co && $co !== '0000-00-00' ? date('D, M d, Y', strtotime($co)) : '—';
                    if (!empty($booking['Check_Out_Time'])) echo ' · '.date('g:i A', strtotime($booking['Check_Out_Time'])); ?>
                </span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Duration</span>
                <span class="summary-value"><?php echo $nights; ?> night<?php echo $nights !== 1 ? 's' : ''; ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Payment</span>
                <span class="summary-value"><?php echo htmlspecialchars($booking['Payment_Method'] ?? '—'); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Paid</span>
                <span class="summary-value highlight">₱<?php echo number_format($booking['Amount'] ?? 0); ?></span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Status</span>
                <span class="summary-value"><span class="status-pill">Pending Review</span></span>
            </div>
        </div>
    </div>

    <div class="next-steps">
        <strong>What happens next?</strong> Our admin team will review your booking and confirm it shortly.
        Track the status or make changes on your <strong>My Bookings</strong> page.
        Cancellations are only allowed while your booking is still <strong>Pending</strong>.
        Once your stay is complete, you'll be able to <strong>leave a review</strong>!
    </div>

    <div class="btn-row">
        <a href="index.php?action=my_bookings" class="eb-btn eb-btn-primary">View My Bookings</a>
        <a href="index.php?action=dashboard"   class="eb-btn eb-btn-ghost">Browse More Estates</a>
    </div>
    <div class="ref-tag">Booking Reference: <span>#<?php echo str_pad($booking['Booking_Id'], 5, '0', STR_PAD_LEFT); ?></span></div>
</div>
</body>
</html>
