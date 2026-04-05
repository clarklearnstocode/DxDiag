<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Settings | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/user_pages.css">
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
                <p style="margin: 15px 0 0 0; font-weight: 700; font-size: 1.2rem;">Lance Eduard Libuna</p>
            </div>

            <form action="index.php?action=dashboard" method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" value="Lance Eduard Libuna">
                </div>
                
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" value="lance@example.com">
                </div>

                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" placeholder="••••••••">
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" placeholder="Leave blank to keep current">
                </div>

                <button type="submit" class="save-btn" onclick="alert('Settings updated successfully!')">Save Changes</button>
            </form>
        </div>
    </div>
</body>
</html>