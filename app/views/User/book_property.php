<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook | Reserve <?php echo htmlspecialchars($property['Property_Name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root { --primary: #c9a07a; --dark: #0a0a0a; --card: #161616; }

        body {
            background: var(--dark);
            color: white;
            font-family: 'DM Sans', sans-serif;
            padding: 40px 50px;
            line-height: 1.6;
        }

        .header-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1100px;
            margin: 0 auto 30px;
        }
        .back-link { color: #888; text-decoration: none; font-size: 0.9rem; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .back-link:hover { color: white; }

        .user-profile-container { position: relative; }
        .profile-btn { background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; }
        .profile-icon { width: 40px; height: 40px; border-radius: 50%; border: 2px solid transparent; transition: 0.3s; object-fit: cover; }
        .profile-btn:hover .profile-icon { border-color: var(--primary); }
        .dropdown-menu { display: none; position: absolute; right: 0; top: 52px; background: #161616; border: 1px solid #222; border-radius: 12px; min-width: 160px; z-index: 1000; box-shadow: 0 10px 30px rgba(0,0,0,0.5); overflow: hidden; }
        .dropdown-menu a { color: #ccc; padding: 12px 20px; text-decoration: none; display: block; font-size: 0.85rem; transition: 0.2s; }
        .dropdown-menu a:hover { background: #1f1f1f; color: var(--primary); }
        .show { display: block; }

        .booking-container {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 50px;
        }

        /* Property image with guaranteed height */
        .prop-img-wrap {
            position: relative;
            height: 430px;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #2a2a2a;
            background: #1a1a1a;
        }
        .prop-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover; display: block;
        }

        .property-info h1 { font-family: 'Playfair Display', serif; font-size: 2.2rem; margin: 22px 0 8px; letter-spacing: -0.5px; }
        .property-info > p { color: #888; margin-bottom: 18px; }
        .property-specs { display: flex; gap: 20px; flex-wrap: wrap; color: #777; font-size: 0.875rem; }
        .property-specs span strong { color: white; }

        .booking-card {
            background: var(--card);
            padding: 28px;
            border-radius: 22px;
            border: 1px solid #222;
            height: fit-content;
            position: sticky;
            top: 30px;
        }

        .price-tag { font-size: 1.5rem; font-weight: 700; color: var(--primary); margin-bottom: 18px; display: block; }
        .price-tag small { font-size: 0.75rem; color: #555; font-weight: 400; }

        .occupied-banner {
            background: rgba(231,76,60,0.1); border: 1px solid rgba(231,76,60,0.3);
            border-radius: 12px; padding: 16px; text-align: center;
            color: #e74c3c; font-weight: 700; margin: 16px 0;
        }

        /* Form structure */
        .form-group { margin-bottom: 14px; }
        .field-label {
            display: block;
            font-size: 0.65rem;
            color: var(--primary);
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
        }
        .date-time-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 11px 13px;
            background: #111;
            border: 1px solid #2a2a2a;
            border-radius: 10px;
            color: white;
            outline: none;
            transition: 0.25s;
            font-size: 0.875rem;
            font-family: 'DM Sans', sans-serif;
        }
        input[type="date"]:focus,
        input[type="time"]:focus,
        select:focus { border-color: var(--primary); background: #1a1a1a; }
        input[readonly] { color: #555; cursor: not-allowed; border-color: #1e1e1e; }
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator { filter: invert(0.5); cursor: pointer; }

        /* Section divider inside form */
        .form-section-label {
            font-size: 0.62rem;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
            margin: 18px 0 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #1e1e1e;
            display: block;
        }

        /* Date conflict warning */
        .date-warning {
            background: rgba(241,196,15,0.09);
            border: 1px solid rgba(241,196,15,0.25);
            border-radius: 8px;
            padding: 9px 13px;
            font-size: 0.8rem;
            color: #f1c40f;
            margin-top: 8px;
            display: none;
        }

        /* Total summary box */
        .total-box {
            background: #0d0d0d;
            padding: 14px 16px;
            border-radius: 11px;
            margin: 16px 0 14px;
            border: 1px dashed #252525;
            display: none;
        }
        .total-row { display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 5px; color: #666; }
        .total-row.grand { color: var(--primary); font-weight: 700; font-size: 1rem; margin-top: 8px; border-top: 1px solid #222; padding-top: 8px; margin-bottom: 0; }

        .btn-confirm {
            width: 100%; padding: 16px; background: var(--primary); border: none;
            border-radius: 12px; font-weight: 800; cursor: pointer; transition: 0.3s;
            color: #000; text-transform: uppercase; letter-spacing: 0.8px;
            font-family: 'DM Sans', sans-serif; font-size: 0.875rem;
        }
        .btn-confirm:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(201,160,122,0.28); }
        .btn-confirm:disabled { opacity: 0.3; cursor: not-allowed; transform: none !important; }

        /* Booked dates hint list */
        .booked-dates-box { margin-top: 18px; padding-top: 16px; border-top: 1px solid #1e1e1e; }
        .bd-title { font-size: 0.65rem; color: #333; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 9px; font-weight: 700; }
        .bd-item { font-size: 0.78rem; color: #444; padding: 5px 0; border-bottom: 1px solid #161616; display: flex; justify-content: space-between; }
        .bd-item:last-child { border-bottom: none; }
        .bd-item .bd-range { color: #e74c3c; }

        hr.divider { border: none; border-top: 1px solid #1e1e1e; margin: 20px 0; }

        .about-section h3 { font-size: 1rem; margin-bottom: 10px; }
        .about-section p { color: #777; font-size: 0.9rem; line-height: 1.75; }
    </style>
</head>
<body>

    <header class="header-nav">
        <a href="index.php?action=dashboard" class="back-link">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to Browse
        </a>
        <div class="user-profile-container">
            <button class="profile-btn" onclick="toggleDropdown()">
                <img src="<?php echo !empty($_SESSION['user_image']) ? 'assets/img/uploads/' . htmlspecialchars($_SESSION['user_image']) : 'assets/img/user.png'; ?>"
                     alt="Profile" class="profile-icon"
                     onerror="this.onerror=null;this.src='assets/img/user.png'">
            </button>
            <div id="profileDropdown" class="dropdown-menu">
                <a href="index.php?action=profile">My Profile</a>
                <a href="index.php?action=my_bookings">My Bookings</a>
                <hr style="border:0;border-top:1px solid #222;margin:4px 0;">
                <a href="index.php?action=logout" style="color:#ff4444;">Logout</a>
            </div>
        </div>
    </header>

    <div class="booking-container">

        <!-- LEFT: Property Info -->
        <div>
            <div class="prop-img-wrap">
                <img src="assets/img/<?php echo htmlspecialchars($property['image_path']); ?>"
                     alt="<?php echo htmlspecialchars($property['Property_Name']); ?>"
                     onerror="this.onerror=null;this.src='assets/img/villa1.png'">
            </div>

            <div class="property-info">
                <h1><?php echo htmlspecialchars($property['Property_Name']); ?></h1>
                <p>📍 <?php echo htmlspecialchars($property['Property_location']); ?></p>
                <div class="property-specs">
                    <span><strong><?php echo $property['Property_capacity']; ?></strong> Guests</span>
                    <span><strong><?php echo ($property['Has_pool'] ? 'Yes' : 'No'); ?></strong> Pool</span>
                    <span><strong><?php echo $property['Property_size'] ?? '—'; ?></strong> m²</span>
                    <span><strong><?php echo $property['Property_bathrooms'] ?? '—'; ?></strong> Baths</span>
                </div>

                <hr class="divider">

                <div class="about-section">
                    <h3>About This Property</h3>
                    <p><?php echo nl2br(htmlspecialchars($property['Property_Description'] ?? 'This elite property offers a seamless blend of modern architecture and tropical comfort, providing the ultimate sanctuary for those seeking privacy and prestige.')); ?></p>
                </div>
            </div>
        </div>

        <!-- RIGHT: Booking Form -->
        <div class="booking-card">
            <span class="price-tag">₱<?php echo number_format($property['Property_rate']); ?> <small>/ night</small></span>

            <?php
                $bookedRanges = $bookedRanges ?? [];
                $isOccupied   = strtolower($property['Status'] ?? '') === 'occupied';
            ?>

            <?php if ($isOccupied && empty($bookedRanges)): ?>
                <div class="occupied-banner">🔴 This property is currently occupied.</div>
            <?php else: ?>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'dates_taken'): ?>
                <div class="date-warning" style="display:block;">
                    ⚠ Those dates are already booked. Please choose different dates.
                </div>
            <?php endif; ?>

            <form action="index.php?action=confirm_booking" method="POST" id="bookingForm">
                <input type="hidden" name="property_id" value="<?php echo $property['Property_Id']; ?>">
                <input type="hidden" name="rate" id="propertyRate" value="<?php echo $property['Property_rate']; ?>">

                <div class="form-group">
                    <label class="field-label">Guest Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" readonly>
                </div>

                <!-- Check-In section -->
                <span class="form-section-label">Check-In</span>
                <div class="date-time-row" style="margin-bottom:14px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="field-label">Date</label>
                        <input type="date" name="check_in" id="checkIn"
                               required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="field-label">Time</label>
                        <input type="time" name="check_in_time" id="checkInTime"
                               value="14:00" required>
                    </div>
                </div>

                <!-- Check-Out section -->
                <span class="form-section-label">Check-Out</span>
                <div class="date-time-row" style="margin-bottom:14px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="field-label">Date</label>
                        <input type="date" name="check_out" id="checkOut"
                               required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="field-label">Time</label>
                        <input type="time" name="check_out_time" id="checkOutTime"
                               value="12:00" required>
                    </div>
                </div>

                <div class="date-warning" id="dateWarning">
                    ⚠ These dates overlap with an existing booking. Please choose different dates.
                </div>

                <div class="form-group">
                    <label class="field-label">Payment Method</label>
                    <select name="payment_method" required>
                        <option value="">Select method...</option>
                        <option value="GCash">GCash</option>
                        <option value="Maya">Maya</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>

                <!-- Summary -->
                <div class="total-box" id="summaryBox">
                    <div class="total-row"><span>Rate / Night</span><span>₱<?php echo number_format($property['Property_rate']); ?></span></div>
                    <div class="total-row"><span>Nights</span><span id="numNights">—</span></div>
                    <div class="total-row"><span>Check-in</span><span id="summaryIn">—</span></div>
                    <div class="total-row"><span>Check-out</span><span id="summaryOut">—</span></div>
                    <div class="total-row grand"><span>Total</span><span id="totalPrice">₱0</span></div>
                </div>

                <button type="submit" class="btn-confirm" id="confirmBtn" disabled>
                    Select Dates to Continue
                </button>
            </form>

            <?php if (!empty($bookedRanges)): ?>
            <div class="booked-dates-box">
                <div class="bd-title">🔒 Already Booked Periods</div>
                <?php foreach ($bookedRanges as $range): ?>
                    <?php if ($range['Check_In'] && $range['Check_In'] !== '0000-00-00'): ?>
                    <div class="bd-item">
                        <span>Booking #<?php echo $range['Booking_Id']; ?></span>
                        <span class="bd-range">
                            <?php echo date('M d', strtotime($range['Check_In'])); ?>
                            →
                            <?php echo date('M d, Y', strtotime($range['Check_Out'])); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>

    <script>
        var bookedRanges = <?php echo json_encode(array_map(function($r) {
            return ['in' => $r['Check_In'], 'out' => $r['Check_Out']];
        }, $bookedRanges)); ?>;

        var rate       = parseFloat(document.getElementById('propertyRate').value || 0);
        var checkInEl  = document.getElementById('checkIn');
        var checkOutEl = document.getElementById('checkOut');
        var inTimeEl   = document.getElementById('checkInTime');
        var outTimeEl  = document.getElementById('checkOutTime');
        var warning    = document.getElementById('dateWarning');
        var summary    = document.getElementById('summaryBox');
        var confirmBtn = document.getElementById('confirmBtn');

        function parseUTC(s) {
            var p = s.split('-');
            return new Date(Date.UTC(+p[0], +p[1]-1, +p[2]));
        }

        function datesOverlap(ni, no) {
            for (var i = 0; i < bookedRanges.length; i++) {
                var r = bookedRanges[i];
                if (!r.in || !r.out) continue;
                if (ni < parseUTC(r.out) && no > parseUTC(r.in)) return true;
            }
            return false;
        }

        function fmt(dateStr, timeStr) {
            if (!dateStr) return '—';
            var d = new Date(dateStr + 'T00:00:00');
            var opts = { month: 'short', day: 'numeric', year: 'numeric' };
            var datePart = d.toLocaleDateString('en-US', opts);
            if (!timeStr) return datePart;
            // Format time nicely e.g. "2:00 PM"
            var parts = timeStr.split(':');
            var h = parseInt(parts[0]), m = parts[1];
            var ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12 || 12;
            return datePart + ' · ' + h + ':' + m + ' ' + ampm;
        }

        function validate() {
            if (!checkInEl.value || !checkOutEl.value) {
                summary.style.display = 'none';
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Select Dates to Continue';
                return;
            }

            var inDate  = parseUTC(checkInEl.value);
            var outDate = parseUTC(checkOutEl.value);

            if (outDate <= inDate) {
                warning.style.display = 'none';
                summary.style.display = 'none';
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Check-out must be after Check-in';
                // Update check-out min
                var minOut = new Date(inDate);
                minOut.setUTCDate(minOut.getUTCDate() + 1);
                checkOutEl.min = minOut.toISOString().split('T')[0];
                return;
            }

            // Update check-out min
            var minOut = new Date(inDate);
            minOut.setUTCDate(minOut.getUTCDate() + 1);
            checkOutEl.min = minOut.toISOString().split('T')[0];

            var conflict = datesOverlap(inDate, outDate);

            if (conflict) {
                warning.style.display = 'block';
                summary.style.display = 'none';
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Dates Unavailable';
            } else {
                warning.style.display = 'none';
                var nights = Math.round((outDate - inDate) / 86400000);
                var total  = nights * rate;

                document.getElementById('numNights').textContent  = nights + ' night' + (nights !== 1 ? 's' : '');
                document.getElementById('totalPrice').textContent = '₱' + total.toLocaleString();
                document.getElementById('summaryIn').textContent  = fmt(checkInEl.value, inTimeEl.value);
                document.getElementById('summaryOut').textContent = fmt(checkOutEl.value, outTimeEl.value);

                summary.style.display = 'block';
                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Confirm Reservation';
            }
        }

        checkInEl.addEventListener('change', validate);
        checkOutEl.addEventListener('change', validate);
        inTimeEl.addEventListener('change', validate);
        outTimeEl.addEventListener('change', validate);

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
