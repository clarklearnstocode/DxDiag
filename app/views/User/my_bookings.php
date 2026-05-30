<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/user-layout.css?v=1.0">
    <style>
        /* ── Booking Cards ── */
        .booking-card { background:#fff; border:1px solid #e4dccb; border-radius:14px; overflow:hidden; margin-bottom:16px; box-shadow:0 3px 14px rgba(0,31,63,.06); transition:box-shadow .2s; }
        .booking-card:hover { box-shadow:0 6px 22px rgba(0,31,63,.1); }
        .card-top { display:flex; gap:18px; padding:20px 22px; align-items:flex-start; }
        .prop-img { width:88px; height:70px; border-radius:9px; object-fit:cover; flex-shrink:0; border:1.5px solid #e4dccb; }
        .card-info { flex:1; min-width:0; }
        .prop-name { font-weight:700; font-size:.97rem; color:#1e2a3a; margin-bottom:3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .prop-loc  { font-size:.78rem; color:#6b7685; margin-bottom:6px; }
        .date-range { font-size:.8rem; color:#4a5568; line-height:1.6; }
        .date-range em { color:#005f56; font-style:normal; font-weight:600; }
        .card-right { text-align:right; flex-shrink:0; display:flex; flex-direction:column; align-items:flex-end; gap:6px; }
        .amount { font-family:'Playfair Display',serif; font-size:1.12rem; font-weight:700; color:#1e2a3a; }
        .pay-method { font-size:.7rem; color:#6b7685; }
        .badge { display:inline-flex; align-items:center; gap:5px; padding:4px 11px; border-radius:20px; font-size:.73rem; font-weight:700; }
        .badge-pending   { background:#fff8e6; color:#b07800; border:1px solid #f0d080; }
        .badge-confirmed { background:#edfaf5; color:#1a6647; border:1px solid #a8dfcc; }
        .badge-rejected  { background:#fff2f2; color:#852020; border:1px solid #f5b8b8; }
        .badge-cancelled { background:#f5f5f5; color:#6b7685; border:1px solid #d8d8d8; }
        .badge-completed { background:#f0f7ff; color:#1a3d6b; border:1px solid #b3d3f5; }
        .card-actions { padding:11px 22px 13px; border-top:1px solid #f5f0e8; display:flex; align-items:center; gap:10px; flex-wrap:wrap; background:#fdfaf5; }
        .pending-note { font-size:.74rem; color:#9fa8b3; display:flex; align-items:center; gap:5px; flex:1; }
        .btn-action { display:inline-flex; align-items:center; gap:6px; padding:7px 15px; border-radius:8px; font-size:.78rem; font-weight:600; cursor:pointer; text-decoration:none; border:none; font-family:'DM Sans',sans-serif; transition:all .15s; white-space:nowrap; }
        .btn-edit   { background:#f0f7ff; color:#1a3d6b; border:1px solid #b3d3f5; }
        .btn-edit:hover { background:#dbeeff; }
        .btn-cancel { background:#fff2f2; color:#852020; border:1px solid #f5b8b8; }
        .btn-cancel:hover { background:#ffe0e0; }
        .btn-review { background:linear-gradient(135deg,#cdaa56,#b8943e); color:#fff !important; border:none; }
        .btn-review:hover { box-shadow:0 3px 12px rgba(205,170,86,.4); transform:translateY(-1px); }
        .reviewed-tag { display:inline-flex; align-items:center; gap:5px; font-size:.74rem; color:#005f56; font-weight:600; }

        /* ── Past Elite Stays Section ── */
        .past-stays-section {
            margin-top: 44px;
            margin-bottom: 12px;
        }
        .past-stays-header {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid #ede5d5;
        }
        .past-stays-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: #001f3f;
            margin: 0;
        }
        .past-stays-header .psh-sub {
            font-size: .78rem;
            color: #9fa8b3;
        }
        .past-stays-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 320px), 1fr));
            gap: 16px;
        }

        /* Past stay card */
        .past-card {
            background: #fff;
            border: 1px solid #e4dccb;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 3px 14px rgba(0,31,63,.05);
            display: flex;
            flex-direction: column;
            transition: box-shadow .25s, transform .25s;
        }
        .past-card:hover { box-shadow: 0 8px 26px rgba(0,31,63,.10); transform: translateY(-2px); }
        .past-card-img {
            height: 150px;
            overflow: hidden;
            position: relative;
            background: #ece7dc;
        }
        .past-card-img img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .4s ease;
        }
        .past-card:hover .past-card-img img { transform: scale(1.04); }
        .past-card-img .pc-stay-badge {
            position: absolute;
            top: 10px; left: 10px;
            background: rgba(0,31,63,.82);
            color: rgba(255,255,255,.9);
            font-size: .63rem;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
        }

        .past-card-body {
            padding: 16px 18px 18px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .pc-name {
            font-weight: 700;
            font-size: .95rem;
            color: #1e2a3a;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .pc-loc {
            font-size: .75rem;
            color: #6b7685;
            margin-bottom: 10px;
        }
        .pc-dates {
            font-size: .76rem;
            color: #4a5568;
            background: #f5f0e8;
            border-radius: 7px;
            padding: 7px 10px;
            margin-bottom: 14px;
            line-height: 1.65;
        }
        .pc-dates strong { color: #005f56; font-weight: 600; }

        /* Awaiting Review callout */
        .pc-review-callout {
            background: linear-gradient(135deg, #fdf9f0, #fdf5e4);
            border: 1px solid rgba(205,170,86,.4);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .pc-review-callout .rcb-icon {
            font-size: 1.2rem;
            flex-shrink: 0;
            line-height: 1;
        }
        .pc-review-callout .rcb-text {
            flex: 1;
        }
        .pc-review-callout .rcb-title {
            font-size: .74rem;
            font-weight: 700;
            color: #7a5300;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 2px;
        }
        .pc-review-callout .rcb-sub {
            font-size: .72rem;
            color: #9a7030;
            line-height: 1.4;
        }
        .awaiting-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(205,170,86,.18);
            color: #7a5300;
            border: 1px solid rgba(205,170,86,.5);
            border-radius: 20px;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
            padding: 4px 10px;
            margin-bottom: 10px;
        }

        /* Reviewed badge */
        .reviewed-successfully-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #edfaf5;
            color: #1a6647;
            border: 1px solid #a8dfcc;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .4px;
            padding: 6px 14px;
            margin-top: auto;
            align-self: flex-start;
            cursor: default;
        }
        .reviewed-successfully-badge svg { flex-shrink: 0; }

        .pc-write-btn {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 7px !important;
            width: 100% !important;
            padding: 11px 14px !important;
            background: linear-gradient(135deg, #cdaa56, #b8943e) !important;
            color: #fff !important;
            border: none !important;
            border-radius: 9px !important;
            font-family: 'DM Sans', sans-serif !important;
            font-size: .79rem !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            text-decoration: none !important;
            transition: box-shadow .2s, transform .2s !important;
            margin-top: auto !important;
        }
        .pc-write-btn:hover { box-shadow: 0 4px 14px rgba(205,170,86,.45) !important; transform: translateY(-1px) !important; }

        .past-stays-empty {
            text-align: center;
            padding: 36px 20px;
            background: #fff;
            border: 1px dashed #d8d0c0;
            border-radius: 12px;
            color: #9fa8b3;
            font-size: .87rem;
        }
        .empty-state { text-align:center; padding:56px 20px; }
        .empty-state .empty-icon { font-size:3rem; margin-bottom:14px; }
        .empty-state h3 { font-size:1.05rem; font-weight:700; color:#1e2a3a; margin-bottom:8px; }
        .empty-state p  { font-size:.86rem; color:#6b7685; margin-bottom:20px; }
    </style>
</head>
<body>
<?php $activePage = 'my_bookings'; require_once __DIR__ . '/_user_navbar.php'; ?>

<div class="eb-page eb-page-wide">
    <div class="eb-page-header">
        <h1 class="page-title">My Bookings</h1>
        <p class="page-sub">All your estate reservations. <strong>Pending</strong> bookings can be edited or cancelled.</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <?php $msgs = ['cancelled'=>'Booking cancelled successfully.','updated'=>'Booking updated successfully.']; ?>
        <div class="eb-alert success">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <?php echo $msgs[$_GET['success']] ?? 'Action completed.'; ?>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <?php $errs = ['cannot_cancel'=>'Only pending bookings can be cancelled.','cannot_edit'=>'Only pending bookings can be edited.','review_ineligible'=>'This booking is not eligible for a review.']; ?>
        <div class="eb-alert error">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>
            <?php echo $errs[$_GET['error']] ?? 'Something went wrong.'; ?>
        </div>
    <?php elseif (isset($_GET['reviewed'])): ?>
        <div class="eb-alert success">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Thank you! Your review has been published for other travelers to read.
        </div>
    <?php endif; ?>

    <!-- ═══════════════════════════════════════
         ACTIVE BOOKINGS
    ═══════════════════════════════════════ -->
    <?php
        // Separate active vs past
        $activeBookings = [];
        foreach (($bookings ?? []) as $b) {
            $st = strtolower($b['Reservation_Status'] ?? '');
            $co = $b['Check_Out'] ?? null;
            $isPast = in_array($st, ['completed']) ||
                      ($st === 'confirmed' && $co && $co !== '0000-00-00' && new DateTime($co) < new DateTime('today'));
            if (!$isPast) $activeBookings[] = $b;
        }
    ?>
    <?php if (!empty($activeBookings)): ?>
        <?php foreach ($activeBookings as $b):
            $ci      = $b['Check_In']  ?? null;
            $co      = $b['Check_Out'] ?? null;
            $hasDates = ($ci && $ci !== '0000-00-00' && $co && $co !== '0000-00-00');
            $nights  = 0;
            if ($hasDates) { $d1=new DateTime($ci); $d2=new DateTime($co); $nights=$d1->diff($d2)->days; }
            $status  = strtolower($b['Reservation_Status'] ?? 'pending');
            $isPending = $status === 'pending';
            $badgeMap  = ['pending'=>'badge-pending','confirmed'=>'badge-confirmed','rejected'=>'badge-rejected','cancelled'=>'badge-cancelled','completed'=>'badge-completed'];
            $badgeClass = $badgeMap[$status] ?? 'badge-pending';
        ?>
        <div class="booking-card">
            <div class="card-top">
                <img src="assets/img/<?php echo htmlspecialchars($b['image_path'] ?? 'villa1.png'); ?>" alt="" class="prop-img" onerror="this.src='assets/img/villa1.png'">
                <div class="card-info">
                    <div class="prop-name"><?php echo htmlspecialchars($b['Property_Name']); ?></div>
                    <div class="prop-loc">📍 <?php echo htmlspecialchars($b['Property_location']); ?></div>
                    <div class="date-range">
                        <?php if ($hasDates): ?>
                            <em>In:</em> <?php echo date('M d, Y', strtotime($ci)); ?>
                            &nbsp;→&nbsp;
                            <em>Out:</em> <?php echo date('M d, Y', strtotime($co)); ?>
                            &nbsp;·&nbsp; <?php echo $nights; ?> night<?php echo $nights !== 1 ? 's' : ''; ?>
                        <?php else: ?>Dates not set<?php endif; ?>
                    </div>
                </div>
                <div class="card-right">
                    <div class="amount">₱<?php echo number_format($b['Amount'] ?? 0); ?></div>
                    <span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst($b['Reservation_Status'] ?? 'Pending'); ?></span>
                    <?php if (!empty($b['Payment_Method'])): ?>
                        <div class="pay-method"><?php echo htmlspecialchars($b['Payment_Method']); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($isPending): ?>
            <div class="card-actions">
                <span class="pending-note">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>
                    Changes allowed while Pending
                </span>
                <a href="index.php?action=edit_booking&id=<?php echo $b['Booking_Id']; ?>" class="btn-action btn-edit">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </a>
                <a href="index.php?action=cancel_booking&id=<?php echo $b['Booking_Id']; ?>"
                   class="btn-action btn-cancel"
                   onclick="return confirm('Cancel this booking? This cannot be undone.');">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Cancel
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php elseif (empty($bookings)): ?>
        <div class="empty-state eb-card eb-card-padded">
            <div class="empty-icon">🏡</div>
            <h3>No reservations yet</h3>
            <p>Start exploring luxury estates across Bacolod &amp; Negros Occidental.</p>
            <a href="index.php?action=dashboard" class="eb-btn eb-btn-primary">Browse Estates →</a>
        </div>
    <?php endif; ?>

    <!-- ═══════════════════════════════════════
         YOUR PAST ELITE STAYS
    ═══════════════════════════════════════ -->
    <?php
        $pastBookings = [];
        foreach (($bookings ?? []) as $b) {
            $st = strtolower($b['Reservation_Status'] ?? '');
            $co = $b['Check_Out'] ?? null;
            $isPast = in_array($st, ['completed']) ||
                      ($st === 'confirmed' && $co && $co !== '0000-00-00' && new DateTime($co) < new DateTime('today'));
            if ($isPast) $pastBookings[] = $b;
        }
    ?>
    <?php if (!empty($pastBookings)): ?>
    <div class="past-stays-section">
        <div class="past-stays-header">
            <h2>Your Past Elite Stays</h2>
            <span class="psh-sub"><?php echo count($pastBookings); ?> completed stay<?php echo count($pastBookings) !== 1 ? 's' : ''; ?></span>
        </div>

        <div class="past-stays-grid">
            <?php foreach ($pastBookings as $b):
                $ci  = $b['Check_In']  ?? null;
                $co  = $b['Check_Out'] ?? null;
                $nights = 0;
                if ($ci && $ci !== '0000-00-00' && $co && $co !== '0000-00-00') {
                    $nights = (new DateTime($ci))->diff(new DateTime($co))->days;
                }
                $hasReview   = !empty($b['has_review']);
                $canReview   = !$hasReview;
            ?>
            <div class="past-card">
                <div class="past-card-img">
                    <img src="assets/img/<?php echo htmlspecialchars($b['image_path'] ?? 'villa1.png'); ?>"
                         alt="<?php echo htmlspecialchars($b['Property_Name']); ?>"
                         onerror="this.src='assets/img/villa1.png'">
                    <div class="pc-stay-badge">✓ Stay Completed</div>
                </div>
                <div class="past-card-body">
                    <div class="pc-name"><?php echo htmlspecialchars($b['Property_Name']); ?></div>
                    <div class="pc-loc">📍 <?php echo htmlspecialchars($b['Property_location']); ?></div>
                    <div class="pc-dates">
                        <?php if ($ci && $ci !== '0000-00-00'): ?>
                            <strong>Check-in:</strong> <?php echo date('M d, Y', strtotime($ci)); ?><br>
                            <strong>Check-out:</strong> <?php echo date('M d, Y', strtotime($co)); ?>
                            &nbsp;·&nbsp; <?php echo $nights; ?> night<?php echo $nights !== 1 ? 's' : ''; ?>
                        <?php else: ?>Dates not available<?php endif; ?>
                    </div>

                    <?php if ($canReview): ?>
                        <!-- Awaiting Review state -->
                        <div class="awaiting-badge">
                            ⏳ Awaiting Review
                        </div>
                        <div class="pc-review-callout">
                            <div class="rcb-icon">✍️</div>
                            <div class="rcb-text">
                                <div class="rcb-title">Share Your Experience</div>
                                <div class="rcb-sub">Your feedback helps other travelers choose their perfect estate.</div>
                            </div>
                        </div>
                        <a href="index.php?action=review_property&booking_id=<?php echo $b['Booking_Id']; ?>"
                           class="pc-write-btn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            Write a Review
                        </a>
                    <?php else: ?>
                        <!-- Already Reviewed state -->
                        <div class="reviewed-successfully-badge">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            Reviewed Successfully
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div><!-- /eb-page -->

<script src="assets/js/my-bookings.js"></script>
<script>
document.querySelectorAll('.eb-alert').forEach(function(el) {
    setTimeout(function() {
        el.style.transition = 'opacity .4s, max-height .4s, margin .4s, padding .4s';
        el.style.opacity = '0'; el.style.maxHeight = '0';
        el.style.marginBottom = '0'; el.style.paddingTop = '0'; el.style.paddingBottom = '0';
        el.style.overflow = 'hidden';
        setTimeout(function() { el.remove(); }, 450);
    }, 4500);
});
</script>
</body>
</html>
