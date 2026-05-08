<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EstateBook | Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin-login-page.css">
</head>
<body>

    <div class="login-card">
        <div class="logo">Estate<span>Book</span></div>
        <span class="badge">Administrative Access</span>
        
        <form action="index.php?action=do_admin_login" method="POST">
            <input type="text" name="username" placeholder="Admin Username" required>
            <input type="password" name="passcode" placeholder="Passcode" required>
            <button type="submit" class="btn-admin-login">Access Dashboard</button>
        </form>

        <a href="index.php?action=login" class="back-link">← Back to User Login</a>
    </div>

</body>
</html>