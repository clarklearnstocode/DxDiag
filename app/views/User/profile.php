<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | EstateBook</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/profile-page.css">
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
                    <hr class="dropdown-divider">
                    <a href="index.php?action=logout" class="logout-link-danger">Logout</a>
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
                           class="hidden-file-input" onchange="previewPhoto(this)">
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
                    <div class="form-group form-group-no-margin">
                        <label class="field-label">Full Name</label>
                        <input type="text" name="full_name"
                               value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>"
                               placeholder="Your full name" required>
                    </div>
                    <div class="form-group form-group-no-margin">
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
                    <div class="form-group form-group-no-margin">
                        <label class="field-label">New Password</label>
                        <div class="password-wrap">
                            <input type="password" name="new_password" id="newPw" placeholder="Min. 8 characters">
                            <button type="button" class="toggle-pw" onclick="togglePw('newPw', this)">Show</button>
                        </div>
                    </div>
                    <div class="form-group form-group-no-margin">
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
        <div class="danger-zone danger-zone-mt">
            <span class="section-label">Danger Zone</span>
            <p class="danger-desc">Permanently delete your account and all associated data. This action cannot be undone.</p>
            <button class="btn-danger" onclick="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
                Delete My Account
            </button>
        </div>

    </div>

    <script src="assets/js/profile-page.js"></script>
</body>
</html>
