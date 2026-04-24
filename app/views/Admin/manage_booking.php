<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook Admin | Booking #<?php echo $booking['Booking_Id']; ?></title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        .booking-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 24px; }
        .action-btn-full { display: block; width: 100%; padding: 14px; border: none; border-radius: 10px; font-weight: 800; font-size: 0.9rem; cursor: pointer; text-align: center; text-decoration: none; transition: 0.25s; margin-bottom: 10px; font-family: 'Inter', sans-serif; }
        .action-approve { background: var(--success); color: #000; }
        .action-approve:hover { opacity: 0.85; transform: translateY(-2px); }
        .action-reject  { background: rgba(231,76,60,0.12); color: var(--danger); border: 1px solid var(--danger); }
        .action-reject:hover  { background: var(--danger); color: white; }
        .prop-hero { width:100%; height:190px; object-fit:cover; border-radius:12px; margin-bottom:18px; border:1px solid var(--border); display:block; }
    </style>
</head>
<body>
<?php $activePage = 'reservations'; require __DIR__ . '/_sidebar.php'; ?>

<main class="main-admin">

    <a href="index.php?action=reservations" class="back-link">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Reservations
    </a>

    <div class="page-header">
        <div class="page-header-left">
            <h1>Booking #<?php echo $booking['Booking_Id']; ?></h1>
            <p>Review reservation details and take action.</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">✓ Booking status updated successfully.</div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger">✗ An error occurred. Please try again.</div>
    <?php endif; ?>

    <div class="booking-grid">

        <!-- LEFT: Details -->
        <div>
            <div class="detail-card">
                <span class="detail-card-title">Client Information</span>
                <div class="detail-row"><span class="detail-label">Full Name</span><span class="detail-value"><?php echo htmlspecialchars($booking['Name']); ?></span></div>
                <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value"><?php echo htmlspecialchars($booking['Email']); ?></span></div>
                <div class="detail-row"><span class="detail-label">Phone</span><span class="detail-value"><?php echo htmlspecialchars($booking['Phone'] ?? 'N/A'); ?></span></div>
            </div>

            <div class="detail-card">
                <span class="detail-card-title">Reservation Details</span>
                <div class="detail-row"><span class="detail-label">Property</span><span class="detail-value"><?php echo htmlspecialchars($booking['Property_Name']); ?></span></div>
                <div class="detail-row"><span class="detail-label">Location</span><span class="detail-value"><?php echo htmlspecialchars($booking['Property_location']); ?></span></div>
                <div class="detail-row">
                    <span class="detail-label">Check-In</span>
                    <span class="detail-value"><?php $ci=$booking['Check_In']; echo ($ci&&$ci!=='0000-00-00')?date('M d, Y',strtotime($ci)):'<span style="color:var(--danger)">Not set</span>'; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Check-Out</span>
                    <span class="detail-value"><?php $co=$booking['Check_Out']; echo ($co&&$co!=='0000-00-00')?date('M d, Y',strtotime($co)):'<span style="color:var(--danger)">Not set</span>'; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nights</span>
                    <span class="detail-value"><?php if($ci&&$co&&$ci!=='0000-00-00'&&$co!=='0000-00-00'){$d1=new DateTime($ci);$d2=new DateTime($co);echo $d1->diff($d2)->days.' night(s)';}else{echo'N/A';} ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Booking Date</span>
                    <span class="detail-value"><?php $bd=$booking['Booking_Date']; echo ($bd&&$bd!=='0000-00-00')?date('M d, Y',strtotime($bd)):'N/A'; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <?php $s=$booking['Reservation_Status']??'Pending'; $cls=strtolower($s)==='confirmed'?'badge-confirmed':(strtolower($s)==='rejected'?'badge-rejected':'badge-pending'); ?>
                        <span class="badge <?php echo $cls; ?>"><?php echo htmlspecialchars($s); ?></span>
                    </span>
                </div>
            </div>

            <div class="detail-card">
                <span class="detail-card-title">Payment Information</span>
                <div class="detail-row"><span class="detail-label">Method</span><span class="detail-value"><?php echo htmlspecialchars($booking['Payment_Method']??'N/A'); ?></span></div>
                <div class="detail-row"><span class="detail-label">Amount Paid</span><span class="detail-value" style="color:var(--primary);">₱<?php echo number_format($booking['Amount']??0); ?></span></div>
                <div class="detail-row"><span class="detail-label">Payment Status</span><span class="detail-value"><?php echo htmlspecialchars($booking['Payment_Status']??'N/A'); ?></span></div>
            </div>
        </div>

        <!-- RIGHT: Property + Actions -->
        <div>
            <div class="detail-card">
                <img src="assets/img/<?php echo htmlspecialchars($booking['image_path']??'villa1.png'); ?>" alt="" class="prop-hero">
                <span class="detail-card-title">Property</span>
                <div class="detail-row"><span class="detail-label">Name</span><span class="detail-value"><?php echo htmlspecialchars($booking['Property_Name']); ?></span></div>
                <div class="detail-row"><span class="detail-label">Rate</span><span class="detail-value">₱<?php echo number_format($booking['Property_rate']); ?>/night</span></div>
            </div>

            <div class="detail-card">
                <span class="detail-card-title">Admin Actions</span>
                <?php $cur = strtolower($booking['Reservation_Status']??'pending'); ?>

                <?php if ($cur === 'pending'): ?>
                    <a href="index.php?action=approve_booking&id=<?php echo $booking['Booking_Id']; ?>"
                       class="action-btn-full action-approve"
                       onclick="return confirm('Confirm this booking?');">✓ Approve Booking</a>
                    <a href="index.php?action=update_booking_status&id=<?php echo $booking['Booking_Id']; ?>&status=Rejected&from=manage"
                       class="action-btn-full action-reject"
                       onclick="return confirm('Reject this booking?');">✗ Reject Booking</a>

                <?php elseif ($cur === 'confirmed'): ?>
                    <div style="background:rgba(46,204,113,0.07);color:var(--success);text-align:center;padding:14px;border-radius:10px;font-weight:700;margin-bottom:12px;">
                        ✓ This booking has been confirmed.
                    </div>
                    <a href="index.php?action=update_booking_status&id=<?php echo $booking['Booking_Id']; ?>&status=Rejected&from=manage"
                       class="action-btn-full action-reject"
                       onclick="return confirm('Cancel this confirmed booking?');">✗ Cancel Booking</a>

                <?php else: ?>
                    <div style="background:rgba(231,76,60,0.07);color:var(--danger);text-align:center;padding:14px;border-radius:10px;font-weight:700;margin-bottom:12px;">
                        This booking has been rejected/cancelled.
                    </div>
                    <a href="index.php?action=approve_booking&id=<?php echo $booking['Booking_Id']; ?>"
                       class="action-btn-full action-approve"
                       onclick="return confirm('Re-approve this booking?');">✓ Re-Approve</a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</main>
</body>
</html>
