<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Booking | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/edit-booking.css">
</head>
<body>
<div id="editBookingData" data-booked-ranges='<?php echo htmlspecialchars(json_encode(array_map(function($r){return ['in'=>$r['Check_In'],'out'=>$r['Check_Out']];}, $bookedRanges ?? [])), ENT_QUOTES, "UTF-8"); ?>'></div>
<div class="container">

    <a href="index.php?action=my_bookings" class="back-btn">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to My Bookings
    </a>

    <h1>Edit Reservation</h1>
    <p class="subtitle">Only <strong class="pending-highlight">Pending</strong> bookings can be modified. Confirmed bookings cannot be changed.</p>

    <?php if (isset($_GET['error'])): ?>
        <?php $err = $_GET['error']; ?>
        <div class="alert alert-error">
            <?php
                if ($err === 'dates_taken') echo '✗ Those dates conflict with an existing booking. Please choose different dates.';
                elseif ($err === 'dates')   echo '✗ Please select valid check-in and check-out dates.';
                elseif ($err === 'invalid_date_range') echo '✗ Check-out date must be after check-in date.';
                else                        echo '✗ Something went wrong. Please try again.';
            ?>
        </div>
    <?php endif; ?>

    <!-- Current property summary -->
    <div class="current-card">
        <img src="assets/img/<?php echo htmlspecialchars($booking['image_path'] ?? 'villa1.png'); ?>" alt="">
        <div>
            <div class="current-name"><?php echo htmlspecialchars($booking['Property_Name']); ?></div>
            <div class="current-loc">📍 <?php echo htmlspecialchars($booking['Property_location']); ?> &nbsp;·&nbsp; ₱<?php echo number_format($booking['Property_rate']); ?>/night</div>
        </div>
    </div>

    <form action="index.php?action=update_booking" method="POST">
        <input type="hidden" name="booking_id" value="<?php echo $booking['Booking_Id']; ?>">
        <input type="hidden" id="rateField" value="<?php echo floatval($booking['Property_rate']); ?>">

        <div class="form-card">
            <span class="section-title">Update Check-In</span>

            <div class="date-time-grid">
                <div class="form-group form-group-no-margin">
                    <label>Date</label>
                    <input type="date" name="check_in" id="checkIn"
                           value="<?php echo htmlspecialchars($booking['Check_In'] ?? ''); ?>"
                           min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group form-group-no-margin">
                    <label>Time</label>
                    <input type="time" name="check_in_time" id="checkInTime"
                           value="<?php echo htmlspecialchars(substr($booking['Check_In_Time'] ?? '14:00:00', 0, 5)); ?>"
                           required>
                </div>
            </div>

            <span class="section-title">Update Check-Out</span>

            <div class="date-time-grid">
                <div class="form-group form-group-no-margin">
                    <label>Date</label>
                    <input type="date" name="check_out" id="checkOut"
                           value="<?php echo htmlspecialchars($booking['Check_Out'] ?? ''); ?>"
                           min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                </div>
                <div class="form-group form-group-no-margin">
                    <label>Time</label>
                    <input type="time" name="check_out_time" id="checkOutTime"
                           value="<?php echo htmlspecialchars(substr($booking['Check_Out_Time'] ?? '12:00:00', 0, 5)); ?>"
                           required>
                </div>
            </div>

            <div class="date-warning" id="dateWarning">
                ⚠ These dates overlap with another booking. Please choose different dates.
            </div>

            <div class="total-box" id="summaryBox">
                <div class="total-row"><span>Rate / Night</span><span>₱<?php echo number_format($booking['Property_rate']); ?></span></div>
                <div class="total-row"><span>Nights</span><span id="numNights">—</span></div>
                <div class="total-row"><span>Check-In</span><span id="summaryIn">—</span></div>
                <div class="total-row"><span>Check-Out</span><span id="summaryOut">—</span></div>
                <div class="total-row grand"><span>New Total</span><span id="totalPrice">₱0</span></div>
            </div>

            <?php if (!empty($bookedRanges)): ?>
            <div class="booked-info">
                <div class="booked-info-title">🔒 Other Booked Periods (unavailable)</div>
                <?php foreach ($bookedRanges as $r): ?>
                    <?php if ($r['Check_In'] && $r['Check_In'] !== '0000-00-00'): ?>
                    <div class="booked-item">
                        <span>Booking #<?php echo $r['Booking_Id']; ?></span>
                        <span><?php echo date('M d', strtotime($r['Check_In'])); ?> → <?php echo date('M d, Y', strtotime($r['Check_Out'])); ?></span>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <span class="section-title section-title-top">Update Payment Method</span>

            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method" required>
                    <?php $cur = $booking['Payment_Method'] ?? ''; ?>
                    <option value="GCash"         <?php echo $cur === 'GCash'         ? 'selected' : ''; ?>>GCash</option>
                    <option value="Maya"          <?php echo $cur === 'Maya'          ? 'selected' : ''; ?>>Maya</option>
                    <option value="Bank Transfer" <?php echo $cur === 'Bank Transfer' ? 'selected' : ''; ?>>Bank Transfer</option>
                </select>
            </div>

        </div>

        <div class="btn-row">
            <button type="submit" class="btn-save" id="saveBtn">Save Changes</button>
            <a href="index.php?action=my_bookings" class="btn-cancel-link">Discard</a>
        </div>
    </form>

</div>

<script src="assets/js/edit-booking.js"></script>
</body>
</html>
