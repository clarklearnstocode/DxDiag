<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Reservation | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/user-layout.css?v=1.0">
    <style>
        .prop-summary { display: flex; gap: 16px; align-items: center; padding: 16px 20px; background: #fff; border: 1px solid #e4dccb; border-radius: 12px; margin-bottom: 24px; }
        .prop-summary img { width: 72px; height: 58px; border-radius: 8px; object-fit: cover; flex-shrink: 0; border: 1.5px solid #e4dccb; }
        .ps-name { font-weight: 700; font-size: 0.95rem; color: #1e2a3a; }
        .ps-meta { font-size: 0.78rem; color: #6b7685; margin-top: 3px; }
        .ps-rate { font-size: 0.82rem; color: #005f56; font-weight: 600; }

        .date-time-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 20px; }
        @media(max-width:480px){ .date-time-grid { grid-template-columns: 1fr; } }

        .section-sep { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.3px; color: #005f56; margin: 22px 0 14px; display: block; }

        .date-warning-box { display: none; background: #fff8e6; border: 1px solid #f0d080; color: #7a5300; border-radius: 9px; padding: 11px 14px; font-size: 0.83rem; margin-bottom: 14px; }
        .date-warning-box.visible { display: block; }

        .total-preview { background: #f5faf9; border: 1px solid #a8dfcc; border-radius: 10px; padding: 14px 18px; margin-top: 16px; }
        .total-row { display: flex; justify-content: space-between; font-size: 0.84rem; padding: 5px 0; color: #4a5568; }
        .total-row.grand { font-weight: 700; font-size: 0.96rem; color: #1e2a3a; border-top: 1px solid #c5f0dd; margin-top: 6px; padding-top: 10px; }

        .booked-info { margin-top: 14px; background: #fff8f8; border: 1px solid #f5b8b8; border-radius: 9px; padding: 12px 16px; }
        .booked-info-title { font-size: 0.75rem; font-weight: 700; color: #852020; margin-bottom: 8px; letter-spacing: 0.3px; }
        .booked-item { display: flex; justify-content: space-between; font-size: 0.8rem; color: #7a3030; padding: 4px 0; border-bottom: 1px solid #fde8e8; }
        .booked-item:last-child { border-bottom: none; }

        .btn-row-form { display: flex; gap: 12px; margin-top: 24px; align-items: center; }
    </style>
</head>
<body>

<div id="editBookingData" data-booked-ranges='<?php echo htmlspecialchars(json_encode(array_map(fn($r)=>['in'=>$r['Check_In'],'out'=>$r['Check_Out']], $bookedRanges ?? [])), ENT_QUOTES, 'UTF-8'); ?>'></div>

<?php $activePage = 'my_bookings'; require_once __DIR__ . '/_user_navbar.php'; ?>

<div class="eb-page eb-page-narrow">
    <div class="eb-page-header">
        <h1 class="page-title">Edit Reservation</h1>
        <p class="page-sub">Only <strong>Pending</strong> bookings can be modified.</p>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="eb-alert error">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>
            <?php
                echo match($_GET['error']) {
                    'dates_taken'         => 'Those dates conflict with an existing booking.',
                    'dates'               => 'Please select valid check-in and check-out dates.',
                    'invalid_date_range'  => 'Check-out date must be after check-in date.',
                    default               => 'Something went wrong. Please try again.',
                };
            ?>
        </div>
    <?php endif; ?>

    <!-- Property summary -->
    <div class="prop-summary">
        <img src="assets/img/<?php echo htmlspecialchars($booking['image_path'] ?? 'villa1.png'); ?>" alt="" onerror="this.src='assets/img/villa1.png'">
        <div>
            <div class="ps-name"><?php echo htmlspecialchars($booking['Property_Name']); ?></div>
            <div class="ps-meta">📍 <?php echo htmlspecialchars($booking['Property_location']); ?></div>
            <div class="ps-rate">₱<?php echo number_format($booking['Property_rate']); ?> / night</div>
        </div>
    </div>

    <form action="index.php?action=update_booking" method="POST">
        <input type="hidden" name="booking_id" value="<?php echo $booking['Booking_Id']; ?>">
        <input type="hidden" id="rateField" value="<?php echo floatval($booking['Property_rate']); ?>">

        <div class="eb-card eb-card-padded">
            <span class="section-sep">Check-In</span>
            <div class="date-time-grid">
                <div class="eb-form-group" style="margin-bottom:0">
                    <label class="eb-label">Date</label>
                    <input type="date" name="check_in" id="checkIn" class="eb-input"
                           value="<?php echo htmlspecialchars($booking['Check_In'] ?? ''); ?>"
                           min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="eb-form-group" style="margin-bottom:0">
                    <label class="eb-label">Time</label>
                    <input type="time" name="check_in_time" id="checkInTime" class="eb-input"
                           value="<?php echo htmlspecialchars(substr($booking['Check_In_Time'] ?? '14:00:00', 0, 5)); ?>" required>
                </div>
            </div>

            <span class="section-sep">Check-Out</span>
            <div class="date-time-grid">
                <div class="eb-form-group" style="margin-bottom:0">
                    <label class="eb-label">Date</label>
                    <input type="date" name="check_out" id="checkOut" class="eb-input"
                           value="<?php echo htmlspecialchars($booking['Check_Out'] ?? ''); ?>"
                           min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                </div>
                <div class="eb-form-group" style="margin-bottom:0">
                    <label class="eb-label">Time</label>
                    <input type="time" name="check_out_time" id="checkOutTime" class="eb-input"
                           value="<?php echo htmlspecialchars(substr($booking['Check_Out_Time'] ?? '12:00:00', 0, 5)); ?>" required>
                </div>
            </div>

            <div class="date-warning-box" id="dateWarning">⚠ These dates overlap with another booking. Please choose different dates.</div>

            <div class="total-preview" id="summaryBox" style="display:none">
                <div class="total-row"><span>Rate / Night</span><span>₱<?php echo number_format($booking['Property_rate']); ?></span></div>
                <div class="total-row"><span>Nights</span><span id="numNights">—</span></div>
                <div class="total-row"><span>Check-In</span><span id="summaryIn">—</span></div>
                <div class="total-row"><span>Check-Out</span><span id="summaryOut">—</span></div>
                <div class="total-row grand"><span>New Total</span><span id="totalPrice">₱0</span></div>
            </div>

            <?php if (!empty($bookedRanges)): ?>
            <div class="booked-info">
                <div class="booked-info-title">🔒 Unavailable Periods</div>
                <?php foreach ($bookedRanges as $r): ?>
                    <?php if (!empty($r['Check_In']) && $r['Check_In'] !== '0000-00-00'): ?>
                    <div class="booked-item">
                        <span>Booking #<?php echo $r['Booking_Id']; ?></span>
                        <span><?php echo date('M d', strtotime($r['Check_In'])); ?> → <?php echo date('M d, Y', strtotime($r['Check_Out'])); ?></span>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <span class="section-sep" style="margin-top:24px">Payment Method</span>
            <div class="eb-form-group" style="margin-bottom:0">
                <label class="eb-label">Select Method</label>
                <select name="payment_method" class="eb-select" required>
                    <?php $cur = $booking['Payment_Method'] ?? ''; ?>
                    <option value="GCash"         <?php echo $cur === 'GCash'         ? 'selected' : ''; ?>>GCash</option>
                    <option value="Maya"          <?php echo $cur === 'Maya'          ? 'selected' : ''; ?>>Maya</option>
                    <option value="Bank Transfer" <?php echo $cur === 'Bank Transfer' ? 'selected' : ''; ?>>Bank Transfer</option>
                </select>
            </div>
        </div>

        <div class="btn-row-form">
            <button type="submit" class="eb-btn eb-btn-primary" id="saveBtn">Save Changes</button>
            <a href="index.php?action=my_bookings" class="eb-btn eb-btn-ghost">Discard</a>
        </div>
    </form>
</div>

<script src="assets/js/edit-booking.js"></script>
</body>
</html>
