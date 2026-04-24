<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook Admin | Dashboard</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<?php $activePage = 'dashboard'; require __DIR__ . '/_sidebar.php'; ?>

<main class="main-admin">

    <div class="page-header">
        <div class="page-header-left">
            <h1>Management Overview</h1>
            <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator'); ?>.</p>
        </div>
        <a href="index.php?action=add_property" class="btn btn-primary btn-lg">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Property
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <?php $msgs = ['added'=>'New property added.','updated'=>'Property updated.','deleted'=>'Property deleted.']; ?>
        <div class="alert alert-success">✓ <?php echo $msgs[$_GET['success']] ?? 'Action completed.'; ?></div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger">✗ Something went wrong. Please try again.</div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-icon">🏡</span>
            <h2><?php echo $propCount; ?></h2>
            <p>Total Properties</p>
        </div>
        <div class="stat-card">
            <span class="stat-icon">📋</span>
            <h2><?php echo $bookCount; ?></h2>
            <p>Total Bookings</p>
        </div>
        <div class="stat-card stat-accent">
            <span class="stat-icon">💰</span>
            <h2>₱<?php echo number_format($revenue ?? 0); ?></h2>
            <p>Total Revenue</p>
        </div>
        <div class="stat-card">
            <span class="stat-icon">🟢</span>
            <h2>Online</h2>
            <p>System Status</p>
        </div>
    </div>

    <!-- Properties table -->
    <div class="section-header">
        <h2>All Property Listings</h2>
        <span class="section-meta"><?php echo count($recent_properties); ?> propert<?php echo count($recent_properties) === 1 ? 'y' : 'ies'; ?></span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Property</th>
                <th>Rate / Night</th>
                <th>Capacity</th>
                <th>Bathrooms</th>
                <th>Pool</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($recent_properties)): ?>
                <?php foreach ($recent_properties as $row): ?>
                <?php $occupied = strtolower($row['Status'] ?? '') === 'occupied'; ?>
                <tr>
                    <td>
                        <div class="prop-cell">
                            <img src="assets/img/<?php echo htmlspecialchars($row['image_path'] ?? 'villa1.png'); ?>" class="prop-thumb" alt="">
                            <div>
                                <div class="prop-cell-name"><?php echo htmlspecialchars($row['Property_Name']); ?></div>
                                <div class="prop-cell-loc">📍 <?php echo htmlspecialchars($row['Property_location']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><strong>₱<?php echo number_format($row['Property_rate']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['Property_capacity'] ?? '—'); ?> guests</td>
                    <td><?php echo htmlspecialchars($row['Property_bathrooms'] ?? '—'); ?></td>
                    <td><?php echo (!empty($row['Has_pool']) && $row['Has_pool'] != '0') ? '🏊 Yes' : '—'; ?></td>
                    <td><span class="badge <?php echo $occupied ? 'badge-occupied' : 'badge-available'; ?>"><?php echo htmlspecialchars($row['Status'] ?? 'Available'); ?></span></td>
                    <td>
                        <div style="display:flex;gap:8px;">
                            <a href="index.php?action=edit_property&id=<?php echo $row['Property_Id']; ?>" class="btn btn-ghost-primary">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </a>
                            <a href="index.php?action=delete_property&id=<?php echo $row['Property_Id']; ?>"
                               class="btn btn-ghost-danger"
                               onclick="return confirm('Delete \'<?php echo htmlspecialchars(addslashes($row['Property_Name'])); ?>\'?\n\nThis cannot be undone.');">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                Delete
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="empty-row"><td colspan="7">No properties yet. <a href="index.php?action=add_property" style="color:var(--primary);">Add your first →</a></td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</main>
</body>
</html>
