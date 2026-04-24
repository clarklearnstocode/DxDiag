<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EstateBook Admin | User Management</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<?php $activePage = 'user_management'; require __DIR__ . '/_sidebar.php'; ?>

<main class="main-admin">

    <div class="page-header">
        <div class="page-header-left">
            <h1>User Management</h1>
            <p>View and manage registered client accounts.</p>
        </div>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
        <div class="alert alert-success">✓ User account deleted successfully.</div>
    <?php endif; ?>

    <div class="section-header">
        <h2>Registered Users</h2>
        <span class="section-meta"><?php echo count($users ?? []); ?> user<?php echo count($users ?? []) !== 1 ? 's' : ''; ?> total</span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email Address</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $u): ?>
                <?php
                    $parts    = explode(' ', trim($u['Name']));
                    $initials = strtoupper(substr($parts[0],0,1) . (isset($parts[1]) ? substr($parts[1],0,1) : ''));
                ?>
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar"><?php echo htmlspecialchars($initials); ?></div>
                            <div>
                                <div class="user-cell-name"><?php echo htmlspecialchars($u['Name']); ?></div>
                                <div class="user-cell-sub">@<?php echo htmlspecialchars($u['Username']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($u['Email']); ?></td>
                    <td style="color:var(--text-muted);"><?php echo htmlspecialchars($u['Phone'] ?? '—'); ?></td>
                    <td><span class="badge badge-client">Client</span></td>
                    <td>
                        <a href="index.php?action=delete_user&id=<?php echo $u['User_Id']; ?>"
                           class="btn btn-ghost-danger"
                           onclick="return confirm('Delete user <?php echo htmlspecialchars(addslashes($u['Name'])); ?>? This cannot be undone.');">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                            Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="empty-row"><td colspan="5">No registered users yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</main>
</body>
</html>
