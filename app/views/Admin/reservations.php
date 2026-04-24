<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook Admin | Reservations</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<?php $activePage = 'reservations'; require __DIR__ . '/_sidebar.php'; ?>

<main class="main-admin">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Booking Requests</h1>
            <p>Manage property reservations from clients.</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">✓ Booking status updated successfully.</div>
    <?php endif; ?>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Client</th>
                <th>Property</th>
                <th>Check-In / Check-Out</th>
                <th>Amount</th>
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
                    $st = $res['Reservation_Status'] ?? 'Pending';
                    $badgeClass = strtolower($st) === 'confirmed' ? 'badge-confirmed' : (strtolower($st) === 'rejected' ? 'badge-rejected' : 'badge-pending');
                ?>
                <tr>
                    <td style="color:var(--text-faint);font-size:0.8rem;">#<?php echo $res['Booking_Id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($res['Name'] ?? 'Unknown'); ?></strong></td>
                    <td><strong><?php echo htmlspecialchars($res['Property_Name'] ?? 'N/A'); ?></strong></td>
                    <td style="font-size:0.82rem;line-height:1.9;">
                        <span style="color:var(--primary);font-weight:700;font-size:0.7rem;">IN</span>
                        <?php echo ($ci && $ci !== '0000-00-00') ? date('M d, Y', strtotime($ci)) : '<span style="color:var(--danger);">Not set</span>'; ?>
                        <br>
                        <span style="color:var(--primary);font-weight:700;font-size:0.7rem;">OUT</span>
                        <?php echo ($co && $co !== '0000-00-00') ? date('M d, Y', strtotime($co)) : '<span style="color:var(--danger);">Not set</span>'; ?>
                    </td>
                    <td><strong>₱<?php echo number_format($res['Amount'] ?? 0); ?></strong></td>
                    <td style="color:var(--text-muted);font-size:0.82rem;"><?php echo htmlspecialchars($res['Payment_Method'] ?? 'N/A'); ?></td>
                    <td><span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($st); ?></span></td>
                    <td>
                        <a href="index.php?action=manage_booking&id=<?php echo $res['Booking_Id']; ?>" class="btn btn-primary">Manage</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="empty-row"><td colspan="8">No booking records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</main>
</body>
</html>
