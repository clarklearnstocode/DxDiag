<?php
// Ensure session is active to pull user data
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Settings | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #0a0a0a; --card: #161616; }
        body { background: var(--dark); color: white; font-family: 'Inter', sans-serif; padding: 50px; }
        .container { max-width: 600px; margin: 0 auto; }
        .back-btn { color: #888; text-decoration: none; font-size: 0.9rem; display: inline-block; margin-bottom: 30px; transition: 0.3s; }
        .back-btn:hover { color: var(--primary); }
        h1 { font-size: 2.5rem; margin-bottom: 30px; font-weight: 800; }
        .profile-card { background: var(--card); padding: 40px; border-radius: 24px; border: 1px solid #222; }
        .form-group { margin-bottom: 25px; }
        label { display: block; font-size: 0.75rem; color: #666; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 10px; }
        input { width: 100%; padding: 15px; background: #0a0a0a; border: 1px solid #333; border-radius: 12px; color: white; outline: none; transition: 0.3s; }
        input:focus { border-color: var(--primary); }
        .save-btn { width: 100%; padding: 18px; background: var(--primary); border: none; border-radius: 12px; font-weight: 800; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .save-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(201, 160, 122, 0.2); }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php?action=dashboard" class="back-btn">← Back to Dashboard</a>
        <h1>Account Settings</h1>

        <div class="profile-card">
            <div style="text-align: center; margin-bottom: 40px;">
                <div style="position: relative; display: inline-block;">
                    <img src="assets/img/user.png" style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid var(--primary); object-fit: cover;">
                    <div style="position: absolute; bottom: 0; right: 0; background: var(--primary); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid var(--card);">
                        <span style="font-size: 12px; color: black;">✎</span>
                    </div>
                </div>
                <p style="margin: 15px 0 0 0; font-weight: 700; font-size: 1.2rem;">
                    <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User Name'); ?>
                </p>
            </div>

            <form action="index.php?action=update_profile" method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? 'clark@example.com'); ?>">
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" placeholder="Leave blank to keep current">
                </div>

                <button type="submit" class="save-btn">Save Changes</button>
            </form>
        </div>
    </div>
</body>
</html>