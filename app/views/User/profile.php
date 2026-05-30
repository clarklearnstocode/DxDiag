<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/user-layout.css?v=1.0">
    <style>
        .avatar-section { display: flex; align-items: center; gap: 22px; margin-bottom: 28px; }
        .avatar-wrap { position: relative; flex-shrink: 0; }
        .avatar-img { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid #e4dccb; display: block; }
        .avatar-edit {
            position: absolute; bottom: 2px; right: 2px;
            width: 28px; height: 28px; border-radius: 50%;
            background: #005f56; color: #fff;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 0.85rem;
            border: 2px solid #f8f6f1;
            transition: background 0.15s;
        }
        .avatar-edit:hover { background: #004d46; }
        .avatar-info h3 { font-size: 1.05rem; font-weight: 700; color: #1e2a3a; margin-bottom: 3px; }
        .avatar-info p  { font-size: 0.82rem; color: #6b7685; margin-bottom: 6px; }
        .avatar-hint    { font-size: 0.72rem; color: #9fa8b3; }
        .hidden-file    { display: none; }

        .eb-card-padded + .eb-card-padded { margin-top: 16px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media(max-width:580px){ .form-row { grid-template-columns: 1fr; } }
        .password-wrap { position: relative; }
        .password-wrap .eb-input { padding-right: 68px; }
        .toggle-pw {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #005f56;
            font-size: 0.75rem; font-weight: 600; cursor: pointer;
        }
        .pw-hint { font-size: 0.75rem; color: #9fa8b3; margin-top: 10px; }
        .danger-zone { margin-top: 32px; padding: 22px 24px; background: #fff8f8; border: 1px solid #f5c0c0; border-radius: 12px; }
        .danger-zone .eb-section-label { color: #c0392b; }
        .danger-zone p { font-size: 0.84rem; color: #7a3030; margin-bottom: 16px; }
        .btn-save-wrap { margin-top: 24px; display: flex; gap: 12px; align-items: center; }
    </style>
</head>
<body>
<?php $activePage = 'profile'; require_once __DIR__ . '/_user_navbar.php'; ?>

<div class="eb-page eb-page-narrow">
    <div class="eb-page-header">
        <h1 class="page-title">Account Settings</h1>
        <p class="page-sub">Manage your personal information and security preferences.</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="eb-alert success">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Profile updated successfully.
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="eb-alert error">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>
            <?php
                $e = $_GET['error'];
                echo match($e) {
                    'email_taken'   => 'That email address is already in use.',
                    'wrong_password'=> 'Current password is incorrect.',
                    'upload_failed' => 'Image upload failed. Please use JPG or PNG under 2MB.',
                    default         => 'Something went wrong. Please try again.',
                };
            ?>
        </div>
    <?php endif; ?>

    <form action="index.php?action=update_profile" method="POST" enctype="multipart/form-data">

        <!-- Avatar -->
        <div class="avatar-section">
            <div class="avatar-wrap">
                <img id="avatarPreview"
                     src="<?php echo !empty($_SESSION['user_image']) ? 'assets/img/uploads/'.htmlspecialchars($_SESSION['user_image']) : 'assets/img/user.png'; ?>"
                     alt="Profile Photo" class="avatar-img">
                <div class="avatar-edit" onclick="document.getElementById('photoInput').click()" title="Change photo">✎</div>
                <input type="file" name="profile_photo" id="photoInput" accept="image/jpeg,image/png,image/webp" class="hidden-file" onchange="previewPhoto(this)">
            </div>
            <div class="avatar-info">
                <h3><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Your Name'); ?></h3>
                <p><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></p>
                <span class="avatar-hint">Click the pencil to change photo (JPG/PNG, max 2MB)</span>
            </div>
        </div>

        <!-- Personal Info -->
        <div class="eb-card eb-card-padded">
            <span class="eb-section-label">Personal Information</span>
            <div class="form-row">
                <div class="eb-form-group">
                    <label class="eb-label">Full Name</label>
                    <input type="text" name="full_name" class="eb-input" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>" placeholder="Your full name" required>
                </div>
                <div class="eb-form-group">
                    <label class="eb-label">Phone Number</label>
                    <input type="tel" name="phone" class="eb-input" value="<?php echo htmlspecialchars($_SESSION['user_phone'] ?? ''); ?>" placeholder="+63 9XX XXX XXXX">
                </div>
            </div>
            <div class="eb-form-group" style="margin-bottom:0">
                <label class="eb-label">Email Address</label>
                <input type="email" name="email" class="eb-input" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" placeholder="your@email.com" required>
            </div>
        </div>

        <!-- Change Password -->
        <div class="eb-card eb-card-padded" style="margin-top:16px">
            <span class="eb-section-label">Change Password</span>
            <div class="eb-form-group">
                <label class="eb-label">Current Password</label>
                <div class="password-wrap">
                    <input type="password" name="current_password" id="curPw" class="eb-input" placeholder="Enter current password">
                    <button type="button" class="toggle-pw" onclick="togglePw('curPw',this)">Show</button>
                </div>
            </div>
            <div class="form-row">
                <div class="eb-form-group">
                    <label class="eb-label">New Password</label>
                    <div class="password-wrap">
                        <input type="password" name="new_password" id="newPw" class="eb-input" placeholder="Min. 6 characters">
                        <button type="button" class="toggle-pw" onclick="togglePw('newPw',this)">Show</button>
                    </div>
                </div>
                <div class="eb-form-group">
                    <label class="eb-label">Confirm New Password</label>
                    <div class="password-wrap">
                        <input type="password" name="confirm_password" id="conPw" class="eb-input" placeholder="Repeat new password">
                        <button type="button" class="toggle-pw" onclick="togglePw('conPw',this)">Show</button>
                    </div>
                </div>
            </div>
            <p class="pw-hint">Leave all three fields blank to keep your current password unchanged.</p>
        </div>

        <div class="btn-save-wrap">
            <button type="submit" class="eb-btn eb-btn-primary">Save Changes</button>
            <a href="index.php?action=dashboard" class="eb-btn eb-btn-ghost">Cancel</a>
        </div>
    </form>

    <!-- Danger Zone -->
    <div class="danger-zone">
        <span class="eb-section-label" style="color:#c0392b">Danger Zone</span>
        <p>Permanently delete your account and all associated data. This action cannot be undone.</p>
        <button class="eb-btn eb-btn-danger" onclick="return confirm('Are you sure? This permanently deletes your account and cannot be undone.');">
            Delete My Account
        </button>
    </div>
</div>

<script src="assets/js/profile-page.js"></script>
</body>
</html>
