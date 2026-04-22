<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook Admin | Manage Booking #<?php echo $booking['Booking_Id']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #070707; --card: #111; --success: #2ecc71; --danger: #e74c3c; --pending: #f1c40f; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--dark); color: white; font-family: 'Inter', sans-serif; display: flex; }

        .admin-sidebar { width: 280px; height: 100vh; background: #0f0f0f; border-right: 1px solid #222; padding: 40px 20px; position: fixed; }
        .admin-logo { font-size: 1.5rem; font-weight: 800; margin-bottom: 50px; color: white; text-decoration: none; display: block; }
        .admin-logo span { color: var(--primary); }
        .nav-group { margin-bottom: 30px; }
        .nav-label { font-size: 0.7rem; color: #444; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; display: block; }
        .nav-link { display: flex; align-items: center; gap: 12px; color: #888; text-decoration: none; padding: 12px; border-radius: 8px; transition: 0.3s; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: #1a1a1a; color: var(--primary); }

        .main-admin { margin-left: 280px; width: 100%; padding: 50px; }

        .back-link { color: #888; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 30px; transition: 0.2s; }
        .back-link:hover { color: var(--primary); }

        .booking-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 30px; }

        .detail-card { background: var(--card); border: 1px solid #222; border-radius: 16px; padding: 30px; }
        .detail-card h2 { font-size: 1.1rem; color: var(--primary); margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; font-size: 0.75rem; }

        .detail-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #1a1a1a; font-size: 0.9rem; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #666; }
        .detail-value { color: white; font-weight: 600; text-align: right; }

        .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .status-pending { background: rgba(241, 196, 15, 0.1); color: var(--pending); }
        .status-confirmed { background: rgba(46, 204, 113, 0.1); color: var(--success); }
        .status-rejected { background: rgba(231, 76, 60, 0.1); color: var(--danger); }

        .action-section { margin-top: 25px; }
        .action-section h2 { font-size: 0.75rem; color: var(--primary); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; }

        .btn { display: block; width: 100%; padding: 15px; border: none; border-radius: 10px; font-weight: 800; font-size: 0.9rem; cursor: pointer; text-align: center; text-decoration: none; transition: 0.3s; margin-bottom: 10px; }
        .btn-approve { background: var(--success); color: #000; }
        .btn-approve:hover { opacity: 0.85; transform: translateY(-2px); }
        .btn-reject  { background: rgba(231, 76, 60, 0.15); color: var(--danger); border: 1px solid var(--danger); }
        .btn-reject:hover  { background: var(--danger); color: white; }

        .property-img { width: 100%; height: 200px; object-fit: cover; border-radius: 12px; margin-bottom: 20px; border: 1px solid #222; }

        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; }
        .alert-success { background: rgba(46,204,113,0.1); color: var(--success); border: 1px solid rgba(46,204,113,0.3); }
        .alert-error   { background: rgba(231,76,60,0.1); color: var(--danger); border: 1px solid rgba(231,76,60,0.3); }

        .disabled-note { color: #555; font-size: 0.8rem; text-align: center; margin-top: 8px; }
    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <a href="#" class="admin-logo">Admin<span>Portal</span></a>
        <div class="nav-group">
            <span class="nav-label">Main Menu</span>
            <a href="index.php?action=admin_dashboard" class="nav-link">Dashboard</a>
            <a href="index.php?action=add_property" class="nav-link">Add Property</a>
            <a href="index.php?action=reservations" class="nav-link active">Reservations</a>
            <a href="index.php?action=user_management" class="nav-link">User Management</a>
        </div>
        <div class="nav-group">
            <span class="nav-label">Settings</span>
            <a href="index.php?action=home" class="nav-link" style="color:#ff4444;">Exit Admin</a>
        </div>
    </aside>

    <main class="main-admin">
        <a href="index.php?action=reservations" class="back-link">← Back to Reservations</a>
        <h1 style="font-size:2rem; margin-bottom:8px;">Booking #<?php echo $booking['Booking_Id']; ?></h1>
        <p style="color:#666; margin-bottom:30px;">Review reservation details and take action.</p>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">✓ Booking status updated successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">✗ An error occurred. Please try again.</div>
        <?php endif; ?>

        <div class="booking-grid">

            <!-- LEFT: Booking Details -->
            <div>
                <div class="detail-card" style="margin-bottom:20px;">
                    <h2>Client Information</h2>
                    <div class="detail-row">
                        <span class="detail-label">Full Name</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['Name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['Email']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Phone</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['Phone'] ?? 'N/A'); ?></span>
                    </div>
                </div>

                <div class="detail-card" style="margin-bottom:20px;">
                    <h2>Reservation Details</h2>
                    <div class="detail-row">
                        <span class="detail-label">Property</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['Property_Name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Location</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['Property_location']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Check-In</span>
                        <span class="detail-value">
                            <?php
                                $ci = $booking['Check_In'];
                                echo ($ci && $ci !== '0000-00-00') ? date('M d, Y', strtotime($ci)) : '<span style="color:#e74c3c;">Not set</span>';
                            ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Check-Out</span>
                        <span class="detail-value">
                            <?php
                                $co = $booking['Check_Out'];
                                echo ($co && $co !== '0000-00-00') ? date('M d, Y', strtotime($co)) : '<span style="color:#e74c3c;">Not set</span>';
                            ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Nights</span>
                        <span class="detail-value">
                            <?php
                                if ($ci && $co && $ci !== '0000-00-00' && $co !== '0000-00-00') {
                                    $d1 = new DateTime($ci); $d2 = new DateTime($co);
                                    echo $d1->diff($d2)->days . ' night(s)';
                                } else { echo 'N/A'; }
                            ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Booking Date</span>
                        <span class="detail-value">
                            <?php
                                $bd = $booking['Booking_Date'];
                                echo ($bd && $bd !== '0000-00-00') ? date('M d, Y', strtotime($bd)) : 'N/A';
                            ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="detail-value">
                            <?php
                                $s = $booking['Reservation_Status'] ?? 'Pending';
                                $cls = strtolower($s) === 'confirmed' ? 'status-confirmed' : (strtolower($s) === 'rejected' ? 'status-rejected' : 'status-pending');
                            ?>
                            <span class="status-badge <?php echo $cls; ?>"><?php echo htmlspecialchars($s); ?></span>
                        </span>
                    </div>
                </div>

                <div class="detail-card">
                    <h2>Payment Information</h2>
                    <div class="detail-row">
                        <span class="detail-label">Method</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['Payment_Method'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Amount Paid</span>
                        <span class="detail-value" style="color:var(--primary);">₱<?php echo number_format($booking['Amount'] ?? 0); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Payment Status</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['Payment_Status'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Property Preview + Actions -->
            <div>
                <div class="detail-card" style="margin-bottom:20px;">
                    <img src="assets/img/<?php echo htmlspecialchars($booking['image_path'] ?? 'villa1.png'); ?>" 
                         alt="Property" class="property-img">
                    <h2>Property</h2>
                    <div class="detail-row">
                        <span class="detail-label">Name</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['Property_Name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Rate</span>
                        <span class="detail-value">₱<?php echo number_format($booking['Property_rate']); ?>/night</span>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="action-section">
                        <h2>Admin Actions</h2>
                        <?php $currentStatus = strtolower($booking['Reservation_Status'] ?? 'pending'); ?>

                        <?php if ($currentStatus === 'pending'): ?>
                            <a href="index.php?action=approve_booking&id=<?php echo $booking['Booking_Id']; ?>" 
                               class="btn btn-approve"
                               onclick="return confirm('Confirm this booking?');">
                                ✓ Approve Booking
                            </a>
                            <a href="index.php?action=update_booking_status&id=<?php echo $booking['Booking_Id']; ?>&status=Rejected&from=manage" 
                               class="btn btn-reject"
                               onclick="return confirm('Reject this booking?');">
                                ✗ Reject Booking
                            </a>
                        <?php elseif ($currentStatus === 'confirmed'): ?>
                            <p style="color:var(--success); text-align:center; padding:15px; background:rgba(46,204,113,0.07); border-radius:10px; font-weight:700;">
                                ✓ This booking has been confirmed.
                            </p>
                            <a href="index.php?action=update_booking_status&id=<?php echo $booking['Booking_Id']; ?>&status=Rejected&from=manage" 
                               class="btn btn-reject" style="margin-top:12px;"
                               onclick="return confirm('Cancel this confirmed booking?');">
                                ✗ Cancel Booking
                            </a>
                        <?php else: ?>
                            <p style="color:#e74c3c; text-align:center; padding:15px; background:rgba(231,76,60,0.07); border-radius:10px; font-weight:700;">
                                This booking has been rejected/cancelled.
                            </p>
                            <a href="index.php?action=approve_booking&id=<?php echo $booking['Booking_Id']; ?>" 
                               class="btn btn-approve" style="margin-top:12px;"
                               onclick="return confirm('Re-approve this booking?');">
                                ✓ Re-Approve
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
</html>
