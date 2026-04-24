<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #c9a07a; --dark: #0a0a0a; --card: #141414; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--dark); color: white; font-family: 'DM Sans', sans-serif; padding: 50px; min-height: 100vh; }

        .container { max-width: 680px; margin: 0 auto; }

        .header-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .back-btn { color: #666; text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 7px; transition: 0.2s; }
        .back-btn:hover { color: var(--primary); }

        .user-profile-container { position: relative; }
        .profile-btn { background: none; border: none; cursor: pointer; padding: 0; display: flex; align-items: center; }
        .profile-icon-sm { width: 36px; height: 36px; border-radius: 50%; border: 2px solid #222; object-fit: cover; transition: 0.2s; }
        .profile-btn:hover .profile-icon-sm { border-color: var(--primary); }
        .dropdown-menu { display: none; position: absolute; right: 0; top: 48px; background: #141414; border: 1px solid #222; border-radius: 12px; min-width: 160px; z-index: 1000; box-shadow: 0 15px 40px rgba(0,0,0,0.6); overflow: hidden; }
        .dropdown-menu a { color: #888; padding: 11px 18px; text-decoration: none; display: block; font-size: 0.85rem; transition: 0.2s; }
        .dropdown-menu a:hover { background: #1c1c1c; color: var(--primary); }
        .show { display: block; }

        /* Page title */
        h1 { font-family: 'Playfair Display', serif; font-size: 2.2rem; margin-bottom: 6px; }
        .subtitle { color: #444; font-size: 0.875rem; margin-bottom: 35px; }

        /* Alerts */
        .alert { padding: 13px 18px; border-radius: 10px; margin-bottom: 24px; font-size: 0.85rem; font-weight: 600; }
        .alert-success { background: rgba(46,204,113,0.1); color: #2ecc71; border: 1px solid rgba(46,204,113,0.25); }
        .alert-error   { background: rgba(231,76,60,0.1);  color: #e74c3c; border: 1px solid rgba(231,76,60,0.25); }

        /* Avatar section */
        .avatar-section {
            display: flex;
            align-items: center;
            gap: 24px;
            background: var(--card);
            border: 1px solid #1e1e1e;
            border-radius: 18px;
            padding: 24px 28px;
            margin-bottom: 20px;
        }
        .avatar-wrap { position: relative; flex-shrink: 0; }
        .avatar-img {
            width: 90px; height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary);
            display: block;
        }
        .avatar-edit-btn {
            position: absolute; bottom: 2px; right: 2px;
            width: 28px; height: 28px;
            background: var(--primary);
            border: 3px solid var(--dark);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 11px;
            color: black;
            transition: 0.2s;
        }
        .avatar-edit-btn:hover { transform: scale(1.1); }
        .avatar-info h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 3px; }
        .avatar-info p  { font-size: 0.82rem; color: #555; margin-bottom: 10px; }
        .avatar-hint { font-size: 0.75rem; color: #333; }

        /* Form card */
        .form-card {
            background: var(--card);
            border: 1px solid #1e1e1e;
            border-radius: 18px;
            padding: 30px 28px;
            margin-bottom: 20px;
        }
        .section-label {
            font-size: 0.65rem;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid #1e1e1e;
            display: block;
        }

        .form-group { margin-bottom: 17px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 17px; }

        label.field-label {
            display: block;
            font-size: 0.7rem;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 13px 15px;
            background: #0d0d0d;
            border: 1px solid #222;
            border-radius: 10px;
            color: white;
            font-size: 0.9rem;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: 0.25s;
        }
        input:focus { border-color: var(--primary); background: #111; }

        .password-wrap { position: relative; }
        .toggle-pw {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #444; cursor: pointer; font-size: 0.8rem;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: #aaa; }

        .password-hint { font-size: 0.75rem; color: #333; margin-top: 6px; }

        .btn-save {
            width: 100%;
            padding: 15px;
            background: var(--primary);
            border: none;
            border-radius: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.25s;
            color: #000;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(201,160,122,0.25); }

        /* Danger zone */
        .danger-zone {
            background: rgba(231,76,60,0.05);
            border: 1px solid rgba(231,76,60,0.15);
            border-radius: 18px;
            padding: 22px 28px;
        }
        .danger-zone .section-label { color: #e74c3c; border-color: rgba(231,76,60,0.15); }
        .danger-desc { font-size: 0.85rem; color: #555; margin-bottom: 14px; }
        .btn-danger {
            padding: 11px 22px;
            background: transparent;
            border: 1px solid rgba(231,76,60,0.4);
            border-radius: 9px;
            color: #e74c3c;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.25s;
            font-family: 'DM Sans', sans-serif;
        }
        .btn-danger:hover { background: rgba(231,76,60,0.1); border-color: #e74c3c; }
    </style>
</head>
<body>
    <div class="container">

        <div class="header-nav">
            <a href="index.php?action=dashboard" class="back-btn">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to Dashboard
            </a>
            <div class="user-profile-container">
                <button class="profile-btn" onclick="toggleDropdown()">
                    <img src="<?php echo !empty($_SESSION['user_image']) ? 'assets/img/uploads/' . htmlspecialchars($_SESSION['user_image']) : 'assets/img/user.png'; ?>"
                         alt="Profile" class="profile-icon-sm">
                </button>
                <div id="profileDropdown" class="dropdown-menu">
                    <a href="index.php?action=my_bookings">My Bookings</a>
                    <hr style="border:0; border-top:1px solid #1e1e1e; margin:4px 0;">
                    <a href="index.php?action=logout" style="color:#ff4c4c;">Logout</a>
                </div>
            </div>
        </div>

        <h1>Account Settings</h1>
        <p class="subtitle">Manage your personal information and security.</p>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">✓ Your profile has been updated successfully.</div>
        <?php elseif (isset($_GET['error'])): ?>
            <?php $err = $_GET['error']; ?>
            <div class="alert alert-error">
                <?php
                    if ($err === 'email_taken')         echo '✗ That email address is already in use.';
                    elseif ($err === 'wrong_password')  echo '✗ Current password is incorrect.';
                    elseif ($err === 'upload_failed')   echo '✗ Image upload failed. Please try a JPG or PNG under 2MB.';
                    else                                echo '✗ Something went wrong. Please try again.';
                ?>
            </div>
        <?php endif; ?>

        <!-- Avatar / Photo Upload -->
        <form action="index.php?action=update_profile" method="POST" enctype="multipart/form-data" id="profileForm">

            <div class="avatar-section">
                <div class="avatar-wrap">
                    <img id="avatarPreview"
                         src="<?php echo !empty($_SESSION['user_image']) ? 'assets/img/uploads/' . htmlspecialchars($_SESSION['user_image']) : 'assets/img/user.png'; ?>"
                         alt="Profile Photo"
                         class="avatar-img">
                    <div class="avatar-edit-btn" onclick="document.getElementById('photoInput').click()" title="Change photo">✎</div>
                    <!-- name="profile_photo" → $_FILES['profile_photo'] in controller -->
                    <input type="file" name="profile_photo" id="photoInput" accept="image/jpeg,image/png,image/webp"
                           style="display:none;" onchange="previewPhoto(this)">
                </div>
                <div class="avatar-info">
                    <h3><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Your Name'); ?></h3>
                    <p><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></p>
                    <span class="avatar-hint">Click the pencil icon to change your photo (JPG/PNG, max 2MB)</span>
                </div>
            </div>

            <!-- Personal Info -->
            <div class="form-card">
                <span class="section-label">Personal Information</span>

                <div class="form-row">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="field-label">Full Name</label>
                        <input type="text" name="full_name"
                               value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>"
                               placeholder="Your full name" required>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="field-label">Phone Number</label>
                        <input type="tel" name="phone"
                               value="<?php echo htmlspecialchars($_SESSION['user_phone'] ?? ''); ?>"
                               placeholder="+63 9XX XXX XXXX">
                    </div>
                </div>

                <div class="form-group">
                    <label class="field-label">Email Address</label>
                    <input type="email" name="email"
                           value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>"
                           placeholder="your@email.com" required>
                </div>
            </div>

            <!-- Change Password -->
            <div class="form-card">
                <span class="section-label">Change Password</span>

                <div class="form-group">
                    <label class="field-label">Current Password</label>
                    <div class="password-wrap">
                        <input type="password" name="current_password" id="curPw" placeholder="Enter current password">
                        <button type="button" class="toggle-pw" onclick="togglePw('curPw', this)">Show</button>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="field-label">New Password</label>
                        <div class="password-wrap">
                            <input type="password" name="new_password" id="newPw" placeholder="Min. 8 characters">
                            <button type="button" class="toggle-pw" onclick="togglePw('newPw', this)">Show</button>
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="field-label">Confirm New Password</label>
                        <div class="password-wrap">
                            <input type="password" name="confirm_password" id="conPw" placeholder="Repeat new password">
                            <button type="button" class="toggle-pw" onclick="togglePw('conPw', this)">Show</button>
                        </div>
                    </div>
                </div>
                <p class="password-hint">Leave all three fields blank to keep your current password unchanged.</p>
            </div>

            <button type="submit" class="btn-save">Save Changes</button>
        </form>

        <!-- Danger Zone -->
        <div class="danger-zone" style="margin-top:20px;">
            <span class="section-label">Danger Zone</span>
            <p class="danger-desc">Permanently delete your account and all associated data. This action cannot be undone.</p>
            <button class="btn-danger" onclick="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
                Delete My Account
            </button>
        </div>

    </div>

    <script>
        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("show");
        }
        window.onclick = function(e) {
            if (!e.target.closest('.user-profile-container')) {
                var d = document.getElementById("profileDropdown");
                if (d && d.classList.contains('show')) d.classList.remove('show');
            }
        }

        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function togglePw(id, btn) {
            var el = document.getElementById(id);
            if (el.type === 'password') {
                el.type = 'text';
                btn.textContent = 'Hide';
            } else {
                el.type = 'password';
                btn.textContent = 'Show';
            }
        }

        // Client-side: confirm new passwords match before submitting
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            var np = document.getElementById('newPw').value;
            var cp = document.getElementById('conPw').value;
            if (np && np !== cp) {
                e.preventDefault();
                alert('New passwords do not match. Please re-enter.');
            }
        });
    </script>
</body>
</html>
