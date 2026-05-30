<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook | Reserve <?php echo htmlspecialchars($property['Property_Name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/book-property.css">
<style>
/* ── Availability Calendar ── */
.avail-calendar-section {
    margin-top: 22px;
    border-top: 1px solid #ede5d5;
    padding-top: 18px;
}
.avail-calendar-title {
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: #005f56;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.cal-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}
.cal-nav-btn {
    background: none;
    border: 1px solid #e0d8c8;
    border-radius: 6px;
    width: 28px; height: 28px;
    cursor: pointer;
    font-size: 0.85rem;
    color: #5f6b7a;
    transition: background 0.15s, color 0.15s;
    display: flex; align-items: center; justify-content: center;
}
.cal-nav-btn:hover { background: #005f56; color: #fff; border-color: #005f56; }
.cal-month-label {
    font-weight: 700;
    font-size: 0.82rem;
    color: #1e2a3a;
}
.cal-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 3px;
}
.cal-day-header {
    text-align: center;
    font-size: 0.62rem;
    font-weight: 700;
    color: #9fa8b3;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding-bottom: 4px;
}
.cal-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    color: #1e2a3a;
    cursor: default;
    transition: background 0.15s;
    position: relative;
}
.cal-day.empty { background: none; }
.cal-day.today {
    background: #005f56;
    color: #fff;
    font-weight: 700;
}
.cal-day.booked {
    background: #fde8e8;
    color: #c0392b;
    font-weight: 600;
    position: relative;
}
.cal-day.booked::after {
    content: '';
    position: absolute;
    bottom: 3px; left: 50%; transform: translateX(-50%);
    width: 4px; height: 4px;
    border-radius: 50%;
    background: #e74c3c;
}
.cal-day.past {
    color: #c8cfd8;
    font-size: 0.7rem;
}
.cal-day.available:hover {
    background: rgba(0,95,86,0.1);
}
.cal-legend {
    display: flex;
    gap: 14px;
    margin-top: 10px;
    flex-wrap: wrap;
}
.cal-legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.7rem;
    color: #7a8593;
}
.cal-legend-dot {
    width: 10px; height: 10px;
    border-radius: 3px;
}
.cal-legend-dot.dot-booked { background: #fde8e8; border: 1px solid #e74c3c; }
.cal-legend-dot.dot-today  { background: #005f56; }
.cal-legend-dot.dot-avail  { background: #f0ebe0; border: 1px solid #d0c8b8; }
</style>
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

            <?php // Non-blocking model: form always shown regardless of Status ?>

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


            <!-- ── Availability Calendar ── -->
            <div class="avail-calendar-section" id="availCalendarSection">
                <div class="avail-calendar-title">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Booking Availability
                </div>
                <div class="cal-nav">
                    <button class="cal-nav-btn" id="calPrev" onclick="calShift(-1)">&#8249;</button>
                    <span class="cal-month-label" id="calMonthLabel"></span>
                    <button class="cal-nav-btn" id="calNext" onclick="calShift(1)">&#8250;</button>
                </div>
                <div class="cal-grid" id="calGrid"></div>
                <div class="cal-legend">
                    <div class="cal-legend-item"><div class="cal-legend-dot dot-booked"></div> Booked</div>
                    <div class="cal-legend-item"><div class="cal-legend-dot dot-today"></div> Today</div>
                    <div class="cal-legend-item"><div class="cal-legend-dot dot-avail"></div> Available</div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/book-property.js"></script>
<script>
/* ── Availability Calendar Logic ── */
(function(){
    const rawData = document.getElementById('bookPropertyData')
        ?.getAttribute('data-booked-ranges') || '[]';
    let bookedRanges;
    try { bookedRanges = JSON.parse(rawData); } catch(e) { bookedRanges = []; }

    const today = new Date(); today.setHours(0,0,0,0);
    let calYear  = today.getFullYear();
    let calMonth = today.getMonth();
    const MONTHS = ['January','February','March','April','May','June',
                    'July','August','September','October','November','December'];
    const DAYS   = ['Su','Mo','Tu','We','Th','Fr','Sa'];

    function isBooked(date) {
        const d = date.getTime();
        return bookedRanges.some(r => {
            const inD  = new Date(r.in  + 'T00:00:00').getTime();
            const outD = new Date(r.out + 'T00:00:00').getTime();
            return d >= inD && d < outD;
        });
    }

    function renderCalendar() {
        const grid  = document.getElementById('calGrid');
        const label = document.getElementById('calMonthLabel');
        if (!grid || !label) return;

        label.textContent = MONTHS[calMonth] + ' ' + calYear;
        let html = DAYS.map(d => `<div class="cal-day-header">${d}</div>`).join('');

        const firstDay = new Date(calYear, calMonth, 1).getDay();
        const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) html += '<div class="cal-day empty"></div>';

        for (let d = 1; d <= daysInMonth; d++) {
            const date = new Date(calYear, calMonth, d);
            const isPast   = date < today;
            const isToday  = date.getTime() === today.getTime();
            const booked   = !isPast && isBooked(date);
            let cls = 'cal-day';
            if (isPast)    cls += ' past';
            else if (isToday)  cls += ' today';
            else if (booked)   cls += ' booked';
            else               cls += ' available';
            const title = booked ? 'Booked' : (isPast ? '' : 'Available');
            html += `<div class="${cls}" title="${title}">${d}</div>`;
        }
        grid.innerHTML = html;
    }

    window.calShift = function(dir) {
        calMonth += dir;
        if (calMonth > 11) { calMonth = 0; calYear++; }
        if (calMonth < 0)  { calMonth = 11; calYear--; }
        renderCalendar();
    };

    // Initial render
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', renderCalendar);
    } else {
        renderCalendar();
    }
})();
</script>

<style>
/* ══════════════════════════════════════════════════
   Guest Perspectives & Experiences
   Palette: Navy #001f3f | Teal #005f56 | Gold #cdaa56
   BG: Off-White #f8f6f1
   ══════════════════════════════════════════════════ */

.gp-section {
    background: #f8f6f1;
    border-top: 1px solid #e8e0d0;
    padding: 52px 36px 56px;
}

/* Section heading */
.gp-heading {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 36px;
    flex-wrap: wrap;
    gap: 12px;
}
.gp-heading-left {}
.gp-eyebrow {
    display: block;
    font-size: 0.63rem;
    font-weight: 700;
    letter-spacing: 1.8px;
    text-transform: uppercase;
    color: #cdaa56;
    margin-bottom: 8px;
}
.gp-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: #001f3f;
    letter-spacing: -0.02em;
    line-height: 1.18;
    margin: 0;
}
.gp-count-pill {
    background: #001f3f;
    color: #ffffff;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 5px 14px;
    border-radius: 20px;
    white-space: nowrap;
    align-self: flex-end;
}

/* ── Aggregate score block ── */
.gp-aggregate {
    display: flex;
    gap: 32px;
    align-items: flex-start;
    flex-wrap: wrap;
    padding: 28px 30px;
    background: #ffffff;
    border: 1px solid #e4dccb;
    border-radius: 14px;
    margin-bottom: 32px;
    box-shadow: 0 3px 16px rgba(0,31,63,0.06);
}

/* Big score bubble */
.gp-score-bubble {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: linear-gradient(145deg, #001f3f 0%, #003060 100%);
    box-shadow: 0 8px 28px rgba(0,31,63,0.28);
    flex-shrink: 0;
}
.gp-score-num {
    font-family: 'Playfair Display', serif;
    font-size: 2.4rem;
    font-weight: 700;
    color: #ffffff;
    line-height: 1;
}
.gp-score-outof {
    font-size: 0.62rem;
    color: rgba(255,255,255,0.6);
    letter-spacing: 0.5px;
    margin-top: 3px;
    text-transform: uppercase;
}

/* Stars display */
.gp-score-right {
    flex: 1;
    min-width: 180px;
}
.gp-stars-row {
    display: flex;
    align-items: center;
    gap: 3px;
    margin-bottom: 8px;
}
.gp-star {
    font-size: 1.3rem;
    line-height: 1;
}
.gp-star.filled { color: #cdaa56; }
.gp-star.empty  { color: #d8d0c0; }
.gp-verdict {
    font-size: 0.82rem;
    font-weight: 600;
    color: #001f3f;
    margin-bottom: 4px;
}
.gp-total-label {
    font-size: 0.74rem;
    color: #6b7685;
}

/* Category bars */
.gp-cat-bars {
    flex: 1;
    min-width: 200px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.gp-bar-row {
    display: flex;
    align-items: center;
    gap: 12px;
}
.gp-bar-label {
    font-size: 0.74rem;
    font-weight: 500;
    color: #4a5568;
    width: 86px;
    flex-shrink: 0;
}
.gp-bar-track {
    flex: 1;
    height: 6px;
    background: #ede5d5;
    border-radius: 3px;
    overflow: hidden;
}
.gp-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #cdaa56 0%, #b8943e 100%);
    border-radius: 3px;
    transition: width 0.8s cubic-bezier(0.165,0.84,0.44,1);
}
.gp-bar-val {
    font-size: 0.72rem;
    font-weight: 700;
    color: #005f56;
    width: 24px;
    text-align: right;
    flex-shrink: 0;
}

/* ── Review cards grid ── */
.gp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr));
    gap: 16px;
}

.gp-review-card {
    background: #ffffff;
    border: 1px solid #e4dccb;
    border-radius: 12px;
    padding: 20px 22px;
    box-shadow: 0 2px 12px rgba(0,31,63,0.05);
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.2s, transform 0.2s;
}
.gp-review-card:hover {
    box-shadow: 0 6px 22px rgba(0,31,63,0.10);
    transform: translateY(-2px);
}

.gp-rc-top {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}
.gp-rc-avatar {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #001f3f 0%, #003060 100%);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem;
    font-weight: 700;
    color: #ffffff;
    flex-shrink: 0;
    border: 2px solid #e4dccb;
}
.gp-rc-meta {}
.gp-rc-name {
    font-size: 0.86rem;
    font-weight: 700;
    color: #001f3f;
    line-height: 1.2;
}
.gp-rc-date {
    font-size: 0.69rem;
    color: #9fa8b3;
    margin-top: 2px;
}

/* Gold stars on card */
.gp-rc-stars {
    display: flex;
    align-items: center;
    gap: 2px;
    margin-bottom: 10px;
}
.gp-rc-stars .gs { font-size: 0.9rem; line-height: 1; }
.gp-rc-stars .gs.f { color: #cdaa56; }
.gp-rc-stars .gs.e { color: #d8d0c0; }
.gp-rc-rating-num {
    font-size: 0.72rem;
    font-weight: 700;
    color: #001f3f;
    margin-left: 5px;
}

.gp-rc-comment {
    font-size: 0.83rem;
    color: #4a5568;
    line-height: 1.65;
    flex: 1;
}
.gp-rc-comment.no-comment {
    color: #b0bbc6;
    font-style: italic;
}

/* ── Empty / zero-reviews state ── */
.gp-empty {
    text-align: center;
    padding: 48px 28px;
    background: #ffffff;
    border: 1px dashed #d8d0c0;
    border-radius: 14px;
}
.gp-empty-icon {
    font-size: 2.5rem;
    margin-bottom: 16px;
    opacity: 0.6;
}
.gp-empty-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #001f3f;
    margin-bottom: 8px;
}
.gp-empty-sub {
    font-size: 0.83rem;
    color: #9fa8b3;
    line-height: 1.6;
    max-width: 340px;
    margin: 0 auto;
}

@media (max-width: 640px) {
    .gp-section { padding: 36px 18px 44px; }
    .gp-aggregate { flex-direction: column; align-items: center; text-align: center; gap: 20px; }
    .gp-score-right { display: flex; flex-direction: column; align-items: center; }
    .gp-cat-bars { width: 100%; }
    .gp-grid { grid-template-columns: 1fr; }
}
</style>

<?php
/* ── Helper: relative date string ── */
function gpRelDate(string $ts): string {
    $diff = (int)floor((time() - strtotime($ts)) / 86400);
    if ($diff === 0)   return 'Today';
    if ($diff === 1)   return 'Yesterday';
    if ($diff < 30)    return $diff . ' days ago';
    if ($diff < 365)   return floor($diff / 30) . (floor($diff/30) > 1 ? ' months ago' : ' month ago');
    return date('M Y', strtotime($ts));
}

/* ── Helper: render N gold stars ── */
function gpStars(int $n, string $size = 'normal'): string {
    $cls = $size === 'sm' ? 'gs' : 'gp-star';
    $filled = $size === 'sm' ? 'f' : 'filled';
    $empty  = $size === 'sm' ? 'e' : 'empty';
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $html .= '<span class="' . $cls . ' ' . ($i <= $n ? $filled : $empty) . '">★</span>';
    }
    return $html;
}

/* ── Helper: verdict label from avg score ── */
function gpVerdict(float $avg): string {
    if ($avg >= 4.5) return 'Exceptional';
    if ($avg >= 4.0) return 'Very Good';
    if ($avg >= 3.0) return 'Good';
    if ($avg >= 2.0) return 'Fair';
    return 'Poor';
}
?>

<div class="gp-section">
    <div class="gp-heading">
        <div class="gp-heading-left">
            <span class="gp-eyebrow">Verified Guest Feedback</span>
            <h2 class="gp-title">Guest Perspectives<br>&amp; Experiences</h2>
        </div>
        <?php if ($reviewStats['count'] > 0): ?>
            <div class="gp-count-pill">
                <?php echo $reviewStats['count']; ?> Review<?php echo $reviewStats['count'] !== 1 ? 's' : ''; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($reviewStats['count'] > 0): ?>

        <!-- Aggregate Score Panel -->
        <div class="gp-aggregate">
            <div class="gp-score-bubble">
                <div class="gp-score-num"><?php echo number_format($reviewStats['avg'], 1); ?></div>
                <div class="gp-score-outof">out of 5</div>
            </div>

            <div class="gp-score-right">
                <div class="gp-stars-row">
                    <?php
                        $avgFull = (int)floor($reviewStats['avg']);
                        $avgHalf = ($reviewStats['avg'] - $avgFull) >= 0.5;
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $avgFull) {
                                echo '<span class="gp-star filled">★</span>';
                            } elseif ($i === $avgFull + 1 && $avgHalf) {
                                echo '<span class="gp-star filled" style="opacity:.6">★</span>';
                            } else {
                                echo '<span class="gp-star empty">★</span>';
                            }
                        }
                    ?>
                </div>
                <div class="gp-verdict"><?php echo gpVerdict($reviewStats['avg']); ?></div>
                <div class="gp-total-label">
                    Based on <?php echo $reviewStats['count']; ?> verified stay<?php echo $reviewStats['count'] !== 1 ? 's' : ''; ?>
                </div>
            </div>

            <?php if (!empty(array_filter($reviewStats['cats']))): ?>
            <div class="gp-cat-bars">
                <?php foreach ($reviewStats['cats'] as $label => $val):
                    if (!$val) continue;
                    $pct = round(($val / 5) * 100);
                ?>
                <div class="gp-bar-row">
                    <span class="gp-bar-label"><?php echo htmlspecialchars($label); ?></span>
                    <div class="gp-bar-track">
                        <div class="gp-bar-fill" style="width:<?php echo $pct; ?>%"></div>
                    </div>
                    <span class="gp-bar-val"><?php echo number_format((float)$val, 1); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Review Cards Grid -->
        <div class="gp-grid">
            <?php foreach ($propertyReviews as $rev):
                $initial = strtoupper(mb_substr($rev['reviewer_name'] ?? 'G', 0, 1));
                $rating  = (int)($rev['rating'] ?? 0);
                $comment = trim($rev['comment'] ?? '');
                $relDate = gpRelDate($rev['created_at'] ?? date('Y-m-d'));
            ?>
            <div class="gp-review-card">
                <div class="gp-rc-top">
                    <div class="gp-rc-avatar"><?php echo htmlspecialchars($initial); ?></div>
                    <div class="gp-rc-meta">
                        <div class="gp-rc-name"><?php echo htmlspecialchars($rev['reviewer_name'] ?? 'Guest'); ?></div>
                        <div class="gp-rc-date"><?php echo $relDate; ?></div>
                    </div>
                </div>
                <div class="gp-rc-stars">
                    <?php echo gpStars($rating, 'sm'); ?>
                    <span class="gp-rc-rating-num"><?php echo $rating; ?>.0</span>
                </div>
                <div class="gp-rc-comment <?php echo $comment === '' ? 'no-comment' : ''; ?>">
                    <?php echo $comment !== '' ? htmlspecialchars($comment) : 'No written comment provided.'; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <!-- Zero reviews state -->
        <div class="gp-empty">
            <div class="gp-empty-icon">✦</div>
            <div class="gp-empty-title">No Reviews Yet</div>
            <p class="gp-empty-sub">
                Be the first to share your experience at this estate.<br>
                Reviews become available after a confirmed stay is completed.
            </p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
