<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook Admin | Reservations</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #070707; --card: #111; --success: #2ecc71; --pending: #f1c40f; --danger: #e74c3c; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--dark); color: white; font-family: 'Inter', sans-serif; display: flex; }

        .admin-sidebar { width: 280px; height: 100vh; background: #0f0f0f; border-right: 1px solid #222; padding: 40px 20px; position: fixed; overflow-y: auto; }
        .admin-logo { font-size: 1.5rem; font-weight: 800; margin-bottom: 50px; color: white; text-decoration: none; display: block; }
        .admin-logo span { color: var(--primary); }
        .nav-group { margin-bottom: 30px; }
        .nav-label { font-size: 0.7rem; color: #444; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; display: block; }
        .nav-link { display: flex; align-items: center; gap: 12px; color: #888; text-decoration: none; padding: 12px; border-radius: 8px; transition: 0.3s; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: #1a1a1a; color: var(--primary); }

        .main-admin { margin-left: 280px; width: 100%; padding: 50px; }

        .alert { padding: 12px 18px; border-radius: 10px; margin-bottom: 25px; font-size: 0.85rem; font-weight: 600; }
        .alert-success { background: rgba(46,204,113,0.1); color: var(--success); border: 1px solid rgba(46,204,113,0.25); }

        .reservation-table { width: 100%; border-collapse: collapse; margin-top: 30px; background: var(--card); border-radius: 15px; overflow: hidden; border: 1px solid #222; }
        .reservation-table th { text-align: left; padding: 18px 20px; background: #1a1a1a; color: #666; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.8px; }
        .reservation-table td { padding: 18px 20px; border-bottom: 1px solid #1a1a1a; font-size: 0.875rem; vertical-align: middle; }
        .reservation-table tr:last-child td { border-bottom: none; }
        .reservation-table tr:hover td { background: #131313; }

        .client-name { font-weight: 600; color: white; }

        .date-cell { font-size: 0.82rem; line-height: 1.8; }
        .date-cell .label { color: var(--primary); font-weight: 700; font-size: 0.72rem; }

        .amount-cell { font-weight: 700; color: white; }

        .status-badge { padding: 5px 12px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-pending   { background: rgba(241,196,15,0.1); color: var(--pending); }
        .status-confirmed { background: rgba(46,204,113,0.1); color: var(--success); }
        .status-rejected  { background: rgba(231,76,60,0.1);  color: var(--danger);  }

        .action-btn {
            display: inline-block;
            text-decoration: none;
            background: var(--primary);
            color: black;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 0.78rem;
            font-weight: 800;
        }
        .action-btn:hover { transform: translateY(-2px); opacity: 0.85; }

        .empty-row td { text-align: center; color: #444; padding: 50px 20px; }
    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <a href="index.php?action=admin_dashboard" class="admin-logo">Admin<span>Portal</span></a>
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
        <header style="margin-bottom: 10px;">
            <h1 style="font-size: 2rem;">Booking Requests</h1>
            <p style="color: #666; margin-top: 6px;">Manage tour schedules and property reservations from clients.</p>
        </header>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">✓ Booking status updated successfully.</div>
        <?php endif; ?>

        <table class="reservation-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Property</th>
                    <th>Check-In / Check-Out</th>
                    <th>Total Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reservations)): ?>
                    <?php foreach ($reservations as $res): ?>
                    <?php
                        $ci = $res['Check_In'] ?? null;
                        $co = $res['Check_Out'] ?? null;
                        $status = $res['Reservation_Status'] ?? 'Pending';
                        $statusClass = strtolower($status) === 'confirmed' ? 'status-confirmed' : (strtolower($status) === 'rejected' ? 'status-rejected' : 'status-pending');
                    ?>
                    <tr>
                        <td style="color:#444; font-size:0.8rem;">#<?php echo $res['Booking_Id']; ?></td>
                        <td>
                            <span class="client-name"><?php echo htmlspecialchars($res['Name'] ?? 'Unknown'); ?></span>
                        </td>
                        <td><strong><?php echo htmlspecialchars($res['Property_Name'] ?? 'N/A'); ?></strong></td>
                        <td class="date-cell">
                            <span class="label">IN</span>&nbsp;
                            <?php echo ($ci && $ci !== '0000-00-00') ? date('M d, Y', strtotime($ci)) : '<span style="color:#e74c3c;">Not set</span>'; ?>
                            <br>
                            <span class="label">OUT</span>&nbsp;
                            <?php echo ($co && $co !== '0000-00-00') ? date('M d, Y', strtotime($co)) : '<span style="color:#e74c3c;">Not set</span>'; ?>
                        </td>
                        <td class="amount-cell">
                            ₱<?php echo number_format($res['Amount'] ?? 0); ?>
                        </td>
                        <td style="color:#666; font-size:0.82rem;">
                            <?php echo htmlspecialchars($res['Payment_Method'] ?? 'N/A'); ?>
                        </td>
                        <td>
                            <span class="status-badge <?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </span>
                        </td>
                        <td>
                            <a href="index.php?action=manage_booking&id=<?php echo $res['Booking_Id']; ?>" class="action-btn">Manage</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="empty-row">
                        <td colspan="8">No booking records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
