<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Booking | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #080808; --card: #141414; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--dark); color: white; font-family: 'DM Sans', sans-serif; padding: 50px; }

        .container { max-width: 700px; margin: 0 auto; }

        .back-btn { display: inline-flex; align-items: center; gap: 7px; color: #555; text-decoration: none; font-size: 0.875rem; margin-bottom: 28px; transition: 0.2s; }
        .back-btn:hover { color: var(--primary); }

        h1 { font-family: 'Playfair Display', serif; font-size: 2rem; margin-bottom: 6px; }
        .subtitle { color: #444; font-size: 0.875rem; margin-bottom: 30px; }

        .alert { padding: 12px 18px; border-radius: 10px; margin-bottom: 22px; font-size: 0.85rem; font-weight: 600; }
        .alert-error   { background: rgba(231,76,60,0.09); color: #e74c3c; border: 1px solid rgba(231,76,60,0.25); }
        .alert-warning { background: rgba(241,196,15,0.09); color: #f1c40f; border: 1px solid rgba(241,196,15,0.25); }

        /* Current booking summary */
        .current-card {
            background: var(--card);
            border: 1px solid #222;
            border-radius: 16px;
            padding: 20px 22px;
            margin-bottom: 22px;
            display: grid;
            grid-template-columns: 64px 1fr;
            gap: 16px;
            align-items: center;
        }
        .current-card img { width: 64px; height: 54px; border-radius: 10px; object-fit: cover; border: 1px solid #2a2a2a; }
        .current-name { font-weight: 700; font-size: 1rem; margin-bottom: 3px; }
        .current-loc  { font-size: 0.78rem; color: #555; }

        /* Form card */
        .form-card { background: var(--card); border: 1px solid #222; border-radius: 20px; padding: 28px; }
        .section-title { font-size: 0.65rem; color: var(--primary); text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700; margin-bottom: 18px; padding-bottom: 10px; border-bottom: 1px solid #1e1e1e; display: block; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 7px; margin-bottom: 16px; }
        .form-group:last-child { margin-bottom: 0; }
        label { font-size: 0.72rem; color: #555; font-weight: 600; text-transform: uppercase; letter-spacing: 0.7px; }

        input[type="date"], input[type="time"], select {
            background: #111;
            border: 1px solid #252525;
            padding: 13px 14px;
            border-radius: 10px;
            color: white;
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: 0.2s;
            width: 100%;
        }
        input[type="date"]:focus, input[type="time"]:focus, select:focus { border-color: var(--primary); background: #161616; }
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator { filter: invert(0.5); cursor: pointer; }

        /* 2-col grid for date+time pairs */
        .date-time-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px; }

        .date-warning { background: rgba(241,196,15,0.09); border: 1px solid rgba(241,196,15,0.25); border-radius: 8px; padding: 10px 13px; font-size: 0.8rem; color: #f1c40f; margin-top: 8px; display: none; }

        /* Total summary */
        .total-box { background: #0d0d0d; border: 1px dashed #252525; border-radius: 11px; padding: 14px 16px; margin: 18px 0; display: none; }
        .total-row { display: flex; justify-content: space-between; font-size: 0.85rem; color: #555; margin-bottom: 5px; }
        .total-row.grand { color: var(--primary); font-weight: 700; font-size: 1rem; margin-top: 8px; border-top: 1px solid #1e1e1e; padding-top: 8px; margin-bottom: 0; }

        /* Booked dates */
        .booked-info { margin-top: 18px; padding-top: 18px; border-top: 1px solid #1e1e1e; }
        .booked-info-title { font-size: 0.68rem; color: #333; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
        .booked-item { display: flex; justify-content: space-between; font-size: 0.78rem; color: #444; padding: 5px 0; border-bottom: 1px solid #161616; }
        .booked-item:last-child { border-bottom: none; }
        .booked-item span { color: #e74c3c; }

        /* Buttons */
        .btn-row { display: flex; gap: 12px; margin-top: 22px; }
        .btn-save { flex: 1; padding: 15px; background: var(--primary); border: none; border-radius: 11px; color: #000; font-weight: 800; font-size: 0.9rem; cursor: pointer; transition: 0.25s; font-family: 'DM Sans', sans-serif; }
        .btn-save:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(201,160,122,0.25); }
        .btn-save:disabled { opacity: 0.3; cursor: not-allowed; }
        .btn-cancel-link { flex: 0 0 auto; padding: 15px 24px; background: #141414; border: 1px solid #222; border-radius: 11px; color: #666; font-size: 0.9rem; text-decoration: none; display: flex; align-items: center; transition: 0.2s; font-weight: 600; }
        .btn-cancel-link:hover { color: #ccc; border-color: #444; }
    </style>
</head>
<body>
<div class="container">

    <a href="index.php?action=my_bookings" class="back-btn">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to My Bookings
    </a>

    <h1>Edit Reservation</h1>
    <p class="subtitle">Only <strong style="color:#f1c40f;">Pending</strong> bookings can be modified. Confirmed bookings cannot be changed.</p>

    <?php if (isset($_GET['error'])): ?>
        <?php $err = $_GET['error']; ?>
        <div class="alert alert-error">
            <?php
                if ($err === 'dates_taken') echo '✗ Those dates conflict with an existing booking. Please choose different dates.';
                elseif ($err === 'dates')   echo '✗ Please select valid check-in and check-out dates.';
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
                <div class="form-group" style="margin-bottom:0;">
                    <label>Date</label>
                    <input type="date" name="check_in" id="checkIn"
                           value="<?php echo htmlspecialchars($booking['Check_In'] ?? ''); ?>"
                           min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Time</label>
                    <input type="time" name="check_in_time" id="checkInTime"
                           value="<?php echo htmlspecialchars(substr($booking['Check_In_Time'] ?? '14:00:00', 0, 5)); ?>"
                           required>
                </div>
            </div>

            <span class="section-title">Update Check-Out</span>

            <div class="date-time-grid">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Date</label>
                    <input type="date" name="check_out" id="checkOut"
                           value="<?php echo htmlspecialchars($booking['Check_Out'] ?? ''); ?>"
                           min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
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

            <span class="section-title" style="margin-top:24px;">Update Payment Method</span>

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

<script>
var bookedRanges = <?php echo json_encode(array_map(function($r){
    return ['in'=>$r['Check_In'],'out'=>$r['Check_Out']];
}, $bookedRanges)); ?>;

var rate        = parseFloat(document.getElementById('rateField').value) || 0;
var checkInEl   = document.getElementById('checkIn');
var checkOutEl  = document.getElementById('checkOut');
var inTimeEl    = document.getElementById('checkInTime');
var outTimeEl   = document.getElementById('checkOutTime');
var warning     = document.getElementById('dateWarning');
var summary     = document.getElementById('summaryBox');
var saveBtn     = document.getElementById('saveBtn');

function parseUTC(s) { var p=s.split('-'); return new Date(Date.UTC(+p[0],+p[1]-1,+p[2])); }

function hasOverlap(ni, no) {
    for (var i=0;i<bookedRanges.length;i++){
        var r=bookedRanges[i];
        if(!r.in||!r.out) continue;
        if(ni < parseUTC(r.out) && no > parseUTC(r.in)) return true;
    }
    return false;
}

function fmtTime(t) {
    if (!t) return '';
    var p = t.split(':'), h = parseInt(p[0]), m = p[1];
    var ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12 || 12;
    return h + ':' + m + ' ' + ampm;
}

function fmtDate(d) {
    if (!d) return '—';
    var dt = new Date(d + 'T00:00:00');
    return dt.toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'});
}

function recalc() {
    if (!checkInEl.value || !checkOutEl.value) return;
    var ni = parseUTC(checkInEl.value);
    var no = parseUTC(checkOutEl.value);

    if (no <= ni) {
        summary.style.display = 'none';
        var minOut = new Date(ni); minOut.setUTCDate(minOut.getUTCDate()+1);
        checkOutEl.min = minOut.toISOString().split('T')[0];
        return;
    }

    // keep checkout min in sync
    var minOut = new Date(ni); minOut.setUTCDate(minOut.getUTCDate()+1);
    checkOutEl.min = minOut.toISOString().split('T')[0];

    var conflict = hasOverlap(ni, no);
    warning.style.display  = conflict ? 'block' : 'none';
    saveBtn.disabled        = conflict;

    if (!conflict) {
        var nights = Math.round((no - ni) / 86400000);
        document.getElementById('numNights').textContent  = nights + ' night' + (nights !== 1 ? 's' : '');
        document.getElementById('totalPrice').textContent = '₱' + (nights * rate).toLocaleString();
        document.getElementById('summaryIn').textContent  = fmtDate(checkInEl.value)  + (inTimeEl.value  ? ' · ' + fmtTime(inTimeEl.value)  : '');
        document.getElementById('summaryOut').textContent = fmtDate(checkOutEl.value) + (outTimeEl.value ? ' · ' + fmtTime(outTimeEl.value) : '');
        summary.style.display = 'block';
    } else {
        summary.style.display = 'none';
    }
}

checkInEl.addEventListener('change', recalc);
checkOutEl.addEventListener('change', recalc);
inTimeEl.addEventListener('change', recalc);
outTimeEl.addEventListener('change', recalc);
window.addEventListener('DOMContentLoaded', recalc);
</script>
</body>
</html>
