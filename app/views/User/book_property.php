<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook | Reserve <?php echo htmlspecialchars($property['Property_Name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/book-property.css">
</head>
<body>
    <div id="bookPropertyData"
         data-booked-ranges='<?php echo htmlspecialchars(json_encode(array_map(function($r){return ['in'=>$r['Check_In'],'out'=>$r['Check_Out']];}, $bookedRanges ?? [])), ENT_QUOTES, "UTF-8"); ?>'></div>

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
                <hr class="dropdown-divider">
                <a href="index.php?action=logout" class="logout-link-danger">Logout</a>
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
                <div class="date-warning date-warning-visible">
                    ⚠ Those dates are already booked. Please choose different dates.
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_date_range'): ?>
                <div class="date-warning date-warning-visible">
                    ⚠ Check-out date must be after check-in date.
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
                <div class="date-time-row date-time-row-mb">
                    <div class="form-group form-group-no-margin">
                        <label class="field-label">Date</label>
                        <input type="date" name="check_in" id="checkIn"
                               required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group form-group-no-margin">
                        <label class="field-label">Time</label>
                        <input type="time" name="check_in_time" id="checkInTime"
                               value="14:00" required>
                    </div>
                </div>

                <!-- Check-Out section -->
                <span class="form-section-label">Check-Out</span>
                <div class="date-time-row date-time-row-mb">
                    <div class="form-group form-group-no-margin">
                        <label class="field-label">Date</label>
                        <input type="date" name="check_out" id="checkOut"
                               required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>
                    <div class="form-group form-group-no-margin">
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

    <script src="assets/js/book-property.js"></script>
</body>
</html>
