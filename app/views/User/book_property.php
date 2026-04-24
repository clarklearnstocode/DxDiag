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
            margin: 0 auto 30px auto;
        }

        .back-link { color: #888; text-decoration: none; font-size: 0.9rem; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .back-link:hover { color: white; }

        .user-profile-container { position: relative; }
        .profile-btn { background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; }
        .profile-icon { width: 40px; height: 40px; border-radius: 50%; border: 2px solid transparent; transition: 0.3s; object-fit: cover; }
        .profile-btn:hover .profile-icon { border-color: var(--primary); }
        .dropdown-menu { display: none; position: absolute; right: 0; top: 50px; background: #161616; border: 1px solid #222; border-radius: 12px; min-width: 160px; z-index: 1000; box-shadow: 0 10px 30px rgba(0,0,0,0.5); overflow: hidden; }
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

        .property-preview img { width: 100%; border-radius: 20px; border: 1px solid #2a2a2a; object-fit: cover; height: 430px; }
        .property-info h1 { font-family: 'Playfair Display', serif; font-size: 2.4rem; margin: 22px 0 8px; letter-spacing: -0.5px; }
        .property-info p { color: #888; margin-bottom: 18px; }

        .booking-card {
            background: var(--card);
            padding: 32px;
            border-radius: 22px;
            border: 1px solid #222;
            height: fit-content;
            position: sticky;
            top: 30px;
        }

        .price-tag { font-size: 1.55rem; font-weight: 700; color: var(--primary); margin-bottom: 4px; display: block; }
        .price-tag small { font-size: 0.75rem; color: #555; font-weight: 400; }

        /* Occupied banner */
        .occupied-banner {
            background: rgba(231,76,60,0.1);
            border: 1px solid rgba(231,76,60,0.3);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            color: #e74c3c;
            font-weight: 700;
            margin: 16px 0;
        }

        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 0.68rem; color: var(--primary); margin-bottom: 7px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700; }

        input, select {
            width: 100%;
            padding: 13px 15px;
            background: #111;
            border: 1px solid #2a2a2a;
            border-radius: 11px;
            color: white;
            outline: none;
            transition: 0.3s;
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
        }
        input:focus { border-color: var(--primary); background: #1a1a1a; }
        input[readonly] { color: #555; cursor: not-allowed; border-color: #1e1e1e; }

        /* Date conflict warning */
        .date-warning {
            background: rgba(241,196,15,0.1);
            border: 1px solid rgba(241,196,15,0.3);
            border-radius: 8px;
            padding: 10px 13px;
            font-size: 0.8rem;
            color: #f1c40f;
            margin-top: 8px;
            display: none;
        }

        .total-box { background: #0d0d0d; padding: 14px; border-radius: 11px; margin: 16px 0; border: 1px dashed #252525; display: none; }
        .total-row { display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 5px; color: #666; }
        .total-row.grand { color: var(--primary); font-weight: 700; font-size: 1rem; margin-top: 8px; border-top: 1px solid #222; padding-top: 8px; }

        .btn-confirm {
            width: 100%; padding: 18px; background: var(--primary); border: none; border-radius: 13px;
            font-weight: 800; cursor: pointer; transition: 0.3s; margin-top: 8px; color: #000;
            text-transform: uppercase; letter-spacing: 1px; font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
        }
        .btn-confirm:hover:not(:disabled) { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(201,160,122,0.3); }
        .btn-confirm:disabled { opacity: 0.3; cursor: not-allowed; transform: none; }

        /* Booked-dates info box */
        .booked-dates-box { margin-top: 20px; }
        .booked-dates-box .bd-title { font-size: 0.7rem; color: #444; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
        .bd-item { font-size: 0.78rem; color: #555; padding: 5px 0; border-bottom: 1px solid #1a1a1a; display: flex; justify-content: space-between; }
        .bd-item:last-child { border-bottom: none; }
        .bd-item .bd-range { color: #ff7070; }

        .property-specs { display: flex; gap: 20px; margin-top: 14px; flex-wrap: wrap; color: #777; font-size: 0.875rem; }
        .property-specs span strong { color: white; }
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
                <img src="<?php echo !empty($_SESSION['user_image']) ? 'assets/img/uploads/' . htmlspecialchars($_SESSION['user_image']) : 'assets/img/user.png'; ?>" alt="Profile" class="profile-icon">
            </button>
            <div id="profileDropdown" class="dropdown-menu">
                <a href="index.php?action=profile">My Profile</a>
                <a href="index.php?action=my_bookings">My Bookings</a>
                <hr style="border:0; border-top:1px solid #222; margin:5px 0;">
                <a href="index.php?action=logout" style="color:#ff4444;">Logout</a>
            </div>
        </div>
    </header>

    <div class="booking-container">

        <!-- LEFT: Property Info -->
        <div class="property-preview">
            <img src="assets/img/<?php echo htmlspecialchars($property['image_path']); ?>" alt="Villa Image">
            <div class="property-info">
                <h1><?php echo htmlspecialchars($property['Property_Name']); ?></h1>
                <p>📍 <?php echo htmlspecialchars($property['Property_location']); ?></p>
                <div class="property-specs">
                    <span><strong>Capacity:</strong> <?php echo $property['Property_capacity']; ?> Guests</span>
                    <span><strong>Pool:</strong> <?php echo ($property['Has_pool'] ? 'Yes' : 'No'); ?></span>
                    <span><strong>Size:</strong> <?php echo $property['Property_size'] ?? '—'; ?> m²</span>
                    <span><strong>Bathrooms:</strong> <?php echo $property['Property_bathrooms'] ?? '—'; ?></span>
                </div>
                <hr style="border:0; border-top:1px solid #1e1e1e; margin:24px 0;">
                <h3 style="margin-bottom:10px; font-size:1rem;">About This Property</h3>
                <p style="color:#777; font-size:0.95rem; line-height:1.8;">
                    <?php echo htmlspecialchars($property['Property_Description'] ?? 'This elite property offers a seamless blend of modern architecture and tropical comfort, providing the ultimate sanctuary for those seeking privacy and prestige.'); ?>
                </p>
            </div>
        </div>

        <!-- RIGHT: Booking Card -->
        <div class="booking-card">
            <span class="price-tag">₱<?php echo number_format($property['Property_rate']); ?> <small>/ night</small></span>

            <?php
                // ── Load all confirmed bookings for this property ──
                // Used both to disable form AND to show booked-date hints
                $propId = $property['Property_Id'];
                // $bookedRanges is passed from controller (see PropertyController::book)
                $bookedRanges = $bookedRanges ?? [];
                $isOccupied   = strtolower($property['Status'] ?? '') === 'occupied';
            ?>

            <?php if ($isOccupied && empty($bookedRanges)): ?>
                <div class="occupied-banner">🔴 This property is currently occupied and cannot be booked.</div>
            <?php else: ?>

            <form action="index.php?action=confirm_booking" method="POST" id="bookingForm">
                <input type="hidden" name="property_id" value="<?php echo $property['Property_Id']; ?>">
                <input type="hidden" name="rate" id="propertyRate" value="<?php echo $property['Property_rate']; ?>">

                <div class="form-group">
                    <label>Guest Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" readonly>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:13px;">
                    <div class="form-group">
                        <label>Check-in Date</label>
                        <input type="date" name="check_in" id="checkIn" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Check-out Date</label>
                        <input type="date" name="check_out" id="checkOut" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>
                </div>

                <div class="date-warning" id="dateWarning">
                    ⚠ These dates overlap with an existing booking. Please choose different dates.
                </div>

                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" required>
                        <option value="">Select method...</option>
                        <option value="GCash">GCash</option>
                        <option value="Maya">Maya</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>

                <div class="total-box" id="summaryBox">
                    <div class="total-row"><span>Nightly Rate</span><span>₱<?php echo number_format($property['Property_rate']); ?></span></div>
                    <div class="total-row"><span>Nights</span><span id="numNights">—</span></div>
                    <div class="total-row grand"><span>Total</span><span id="totalPrice">₱0</span></div>
                </div>

                <button type="submit" class="btn-confirm" id="confirmBtn">Confirm Reservation</button>
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
        // All confirmed/pending booked ranges for this property (from PHP)
        var bookedRanges = <?php echo json_encode(array_map(function($r) {
            return ['in' => $r['Check_In'], 'out' => $r['Check_Out']];
        }, $bookedRanges)); ?>;

        var checkInEl   = document.getElementById('checkIn');
        var checkOutEl  = document.getElementById('checkOut');
        var rate        = parseFloat(document.getElementById('propertyRate')?.value || 0);
        var summaryBox  = document.getElementById('summaryBox');
        var warning     = document.getElementById('dateWarning');
        var confirmBtn  = document.getElementById('confirmBtn');

        function parseDateUTC(str) {
            var p = str.split('-');
            return new Date(Date.UTC(+p[0], +p[1]-1, +p[2]));
        }

        function datesOverlap(newIn, newOut) {
            for (var i = 0; i < bookedRanges.length; i++) {
                var r = bookedRanges[i];
                if (!r.in || !r.out) continue;
                var bIn  = parseDateUTC(r.in);
                var bOut = parseDateUTC(r.out);
                // Overlap if: newIn < bOut AND newOut > bIn
                if (newIn < bOut && newOut > bIn) return true;
            }
            return false;
        }

        function validate() {
            if (!checkInEl.value || !checkOutEl.value) return;

            var inDate  = parseDateUTC(checkInEl.value);
            var outDate = parseDateUTC(checkOutEl.value);

            // Ensure check-out is after check-in
            if (outDate <= inDate) {
                checkOutEl.min = checkInEl.value;
                summaryBox.style.display = 'none';
                return;
            }

            var conflict = datesOverlap(inDate, outDate);

            if (conflict) {
                warning.style.display = 'block';
                summaryBox.style.display = 'none';
                confirmBtn.disabled = true;
            } else {
                warning.style.display = 'none';
                confirmBtn.disabled = false;

                // Calculate total
                var diffMs   = outDate - inDate;
                var nights   = Math.round(diffMs / 86400000);
                var total    = nights * rate;
                document.getElementById('numNights').textContent  = nights;
                document.getElementById('totalPrice').textContent = '₱' + total.toLocaleString();
                summaryBox.style.display = 'block';
            }

            // Update check-out min to day after check-in
            var minOut = new Date(inDate);
            minOut.setUTCDate(minOut.getUTCDate() + 1);
            checkOutEl.min = minOut.toISOString().split('T')[0];
        }

        checkInEl  && checkInEl.addEventListener('change', validate);
        checkOutEl && checkOutEl.addEventListener('change', validate);

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
