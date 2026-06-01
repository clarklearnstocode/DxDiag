<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/Users.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database   = new Database();
        $this->db   = $database->getConnection();
        $this->user = new User($this->db);
    }

    // ── Login pages ──────────────────────────────────────────

    public function showLogin() {
        require_once __DIR__ . '/../views/login.php';
    }

    public function showSignup() {
        require_once __DIR__ . '/../views/register.php';
    }

    // ── Dashboard ────────────────────────────────────────────

    public function showDashboard() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login"); exit();
        }

        $userId = (int)$_SESSION['user_id'];

        // All properties — always shown regardless of Status
        $stmt       = $this->db->query("SELECT * FROM property ORDER BY Property_Id DESC");
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ── Feature 2: Upcoming booking countdown ──
        $countdownWidget = null;
        $upcomingStmt = $this->db->prepare(
            "SELECT b.Booking_Id, b.Check_In, b.Check_Out,
                    p.Property_Name, p.image_path
             FROM booking b
             JOIN property p ON b.Property_Id = p.Property_Id
             WHERE b.User_Id = ?
               AND b.Reservation_Status = 'Confirmed'
               AND b.Check_In >= CURDATE()
             ORDER BY b.Check_In ASC
             LIMIT 1"
        );
        $upcomingStmt->execute([$userId]);
        $upcomingBooking = $upcomingStmt->fetch(PDO::FETCH_ASSOC);

        if ($upcomingBooking) {
            $today    = new DateTime('today');
            $checkIn  = new DateTime($upcomingBooking['Check_In']);
            $checkOut = new DateTime($upcomingBooking['Check_Out']);
            $daysLeft = (int)$today->diff($checkIn)->days;

            $nights = (int)$checkIn->diff($checkOut)->days;

            if ($daysLeft === 0) {
                $countdownText = "Your stay at {$upcomingBooking['Property_Name']} begins today!";
            } elseif ($daysLeft === 1) {
                $countdownText = "Your stay at {$upcomingBooking['Property_Name']} begins tomorrow!";
            } else {
                $countdownText = "Your stay at {$upcomingBooking['Property_Name']} begins in {$daysLeft} days!";
            }

            $countdownWidget = [
                'property_name' => $upcomingBooking['Property_Name'],
                'image_path'    => $upcomingBooking['image_path'],
                'check_in'      => date('F j, Y', strtotime($upcomingBooking['Check_In'])),
                'check_out'     => date('F j, Y', strtotime($upcomingBooking['Check_Out'])),
                'nights'        => $nights,
                'days_left'     => $daysLeft,
                'countdown_text'=> $countdownText,
                'booking_id'    => $upcomingBooking['Booking_Id'],
            ];
        }

        // ── Feature 3: Latest announcement ──
        $announcement = null;
        try {
            $annStmt = $this->db->query(
                "SELECT id, message, created_at FROM announcements ORDER BY id DESC LIMIT 1"
            );
            $announcement = $annStmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            $announcement = null;
        }

        // ── Feature 4: Unread notification count ──
        $unreadCount = 0;
        try {
            $nStmt = $this->db->prepare(
                "SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0"
            );
            $nStmt->execute([$userId]);
            $unreadCount = (int)$nStmt->fetchColumn();
        } catch (Exception $e) {
            $unreadCount = 0;
        }

        // ── Feature: Past stays for review widget ──
        $pastStays = [];
        try {
            $psStmt = $this->db->prepare(
                "SELECT b.Booking_Id, b.Check_In, b.Check_Out,
                        b.Reservation_Status,
                        p.Property_Name, p.Property_location, p.image_path,
                        pay.Amount,
                        IF(r.id IS NOT NULL, 1, 0) AS has_review
                 FROM booking b
                 JOIN property p ON b.Property_Id = p.Property_Id
                 LEFT JOIN payment pay ON b.Payment_Id = pay.Payment_Id
                 LEFT JOIN reviews r   ON r.booking_id = b.Booking_Id
                 WHERE b.User_Id = ?
                   AND b.Reservation_Status IN ('Confirmed', 'Completed')
                   AND b.Check_Out < CURDATE()
                 ORDER BY b.Check_Out DESC
                 LIMIT 6"
            );
            $psStmt->execute([$userId]);
            $pastStays = $psStmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $pastStays = [];
        }

        require_once __DIR__ . '/../views/User/dashboard.php';
    }

    // ── AJAX: Fetch notifications ────────────────────────────

    public function getNotifications() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['notifications' => []]);
            exit();
        }
        $userId = (int)$_SESSION['user_id'];
        try {
            $stmt = $this->db->prepare(
                "SELECT id, message, is_read, created_at
                 FROM notifications
                 WHERE user_id = ?
                 ORDER BY created_at DESC
                 LIMIT 20"
            );
            $stmt->execute([$userId]);
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['notifications' => $notifications]);
        } catch (Exception $e) {
            echo json_encode(['notifications' => []]);
        }
        exit();
    }

    // ── AJAX: Mark all notifications as read ─────────────────

    public function markNotificationsRead() {
        if (!isset($_SESSION['user_id'])) { exit(); }
        $userId = (int)$_SESSION['user_id'];
        try {
            $stmt = $this->db->prepare(
                "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0"
            );
            $stmt->execute([$userId]);
        } catch (Exception $e) { /* silent */ }
        echo json_encode(['ok' => true]);
        exit();
    }

    // ── AJAX: Admin pending count (for sidebar live refresh) ─

    public function getPendingCount() {
        if (empty($_SESSION['admin_logged_in'])) {
            echo json_encode(['count' => 0]); exit();
        }
        try {
            $count = (int)$this->db->query(
                "SELECT COUNT(*) FROM booking WHERE Reservation_Status = 'Pending'"
            )->fetchColumn();
            echo json_encode(['count' => $count]);
        } catch (Exception $e) {
            echo json_encode(['count' => 0]);
        }
        exit();
    }

    // ── Profile ──────────────────────────────────────────────

    public function showProfile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login"); exit();
        }
        $stmt = $this->db->prepare("SELECT * FROM user WHERE User_Id = ? LIMIT 1");
        $stmt->execute([$_SESSION['user_id']]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($userData) {
            $_SESSION['user_name']  = $userData['Name'];
            $_SESSION['user_email'] = $userData['Email'];
            $_SESSION['user_phone'] = $userData['Phone'] ?? '';
            $_SESSION['user_image'] = $userData['profile_image'] ?? '';
        }
        require_once __DIR__ . '/../views/User/profile.php';
    }

    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login"); exit();
        }

        $userId = $_SESSION['user_id'];
        $name   = trim($_POST['full_name'] ?? '');
        $email  = trim($_POST['email']     ?? '');
        $phone  = trim($_POST['phone']     ?? '');
        $curPw  = $_POST['current_password'] ?? '';
        $newPw  = $_POST['new_password']     ?? '';
        $conPw  = $_POST['confirm_password'] ?? '';

        $stmt = $this->db->prepare("SELECT User_Id FROM user WHERE Email = ? AND User_Id != ?");
        $stmt->execute([$email, $userId]);
        if ($stmt->fetch()) {
            header("Location: index.php?action=profile&error=email_taken"); exit();
        }

        $newImage = null;
        if (!empty($_FILES['profile_photo']['name']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg','image/png','image/webp'];
            $mime    = mime_content_type($_FILES['profile_photo']['tmp_name']);
            if (!in_array($mime, $allowed) || $_FILES['profile_photo']['size'] > 2 * 1024 * 1024) {
                header("Location: index.php?action=profile&error=upload_failed"); exit();
            }
            $ext      = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
            $safeName = 'user_' . $userId . '_' . time() . '.' . strtolower($ext);
            if (!is_dir(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0755, true);
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], UPLOAD_PATH . $safeName)) {
                $newImage = $safeName;
            }
        }

        $newHashedPw = null;
        if (!empty($newPw)) {
            $stmt = $this->db->prepare("SELECT Password FROM user WHERE User_Id = ?");
            $stmt->execute([$userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row || !password_verify($curPw, $row['Password'])) {
                header("Location: index.php?action=profile&error=wrong_password"); exit();
            }
            if ($newPw === $conPw && strlen($newPw) >= 6) {
                $newHashedPw = password_hash($newPw, PASSWORD_DEFAULT);
            }
        }

        $fields = "Name = ?, Email = ?, Phone = ?";
        $params = [$name, $email, $phone];
        if ($newHashedPw)  { $fields .= ", Password = ?";      $params[] = $newHashedPw; }
        if ($newImage)     { $fields .= ", profile_image = ?"; $params[] = $newImage; }
        $params[] = $userId;

        $this->db->prepare("UPDATE user SET {$fields} WHERE User_Id = ?")->execute($params);

        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_phone'] = $phone;
        if ($newImage) $_SESSION['user_image'] = $newImage;

        header("Location: index.php?action=profile&success=1"); exit();
    }

    // ── My Bookings ──────────────────────────────────────────

    public function showMyBookings() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login"); exit();
        }
        $query = "SELECT
                    b.Booking_Id, b.Booking_Date, b.Check_In, b.Check_In_Time,
                    b.Check_Out, b.Check_Out_Time, b.Reservation_Status,
                    p.Property_Name, p.Property_location, p.image_path,
                    pay.Amount, pay.Payment_Method,
                    IF(r.id IS NOT NULL, 1, 0) AS has_review
                  FROM booking b
                  JOIN property p    ON b.Property_Id  = p.Property_Id
                  LEFT JOIN payment pay ON b.Payment_Id = pay.Payment_Id
                  LEFT JOIN reviews r   ON r.booking_id = b.Booking_Id
                  WHERE b.User_Id = ?
                  ORDER BY b.Booking_Id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$_SESSION['user_id']]);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/User/my_bookings.php';
    }

    // ── Signup / Register ────────────────────────────────────

    public function handleSignup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=signup"); exit();
        }
        $name     = trim($_POST['name'] ?? $_POST['full_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $phone    = trim($_POST['phone']    ?? '');
        $password = $_POST['password']      ?? '';

        if ($name === '' || $username === '' || $email === '' || $password === '') {
            header("Location: index.php?action=signup&error=missing_fields"); exit();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: index.php?action=signup&error=invalid_email"); exit();
        }
        if (strlen($password) < 6) {
            header("Location: index.php?action=signup&error=weak_password"); exit();
        }

        $check = $this->db->prepare("SELECT User_Id FROM user WHERE Email = ? OR Username = ?");
        $check->execute([$email, $username]);
        if ($check->fetch()) {
            header("Location: index.php?action=signup&error=taken"); exit();
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->db->prepare(
            "INSERT INTO user (Name, Username, Email, Phone, Password) VALUES (?,?,?,?,?)"
        )->execute([$name, $username, $email, $phone, $hash]);

        header("Location: index.php?action=login&registered=1"); exit();
    }

    // ── LOGIN with TOTP ──────────────────────────────────────

    public function handleLogin() {
        ob_start();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ob_end_clean();
            header("Location: index.php?action=login"); exit();
        }

        $identity = $_POST['login_identity'] ?? '';
        $password = $_POST['password']       ?? '';
        $loggedUser = $this->user->login($identity);

        if ($loggedUser && password_verify($password, $loggedUser['Password'])) {
            ob_end_clean();

            $_SESSION['2fa_pending_user'] = [
                'id'       => $loggedUser['User_Id'],
                'name'     => $loggedUser['Name'],
                'email'    => $loggedUser['Email'],
                'phone'    => $loggedUser['Phone']         ?? '',
                'image'    => $loggedUser['profile_image'] ?? '',
                'totp_enabled' => (int)($loggedUser['totp_enabled'] ?? 0),
                'totp_secret'  => $loggedUser['totp_secret'] ?? '',
            ];

            if (!empty($loggedUser['totp_enabled']) && !empty($loggedUser['totp_secret'])) {
                header("Location: index.php?action=verify_totp&role=user");
            } else {
                header("Location: index.php?action=setup_totp&role=user");
            }
            exit();
        } else {
            ob_end_clean();
            header("Location: index.php?action=login&error=invalid"); exit();
        }
    }

    // ── TOTP Setup page ──────────────────────────────────────

    public function showSetupTOTP() {
        require_once __DIR__ . '/../../app/services/TOTPService.php';

        $role = $_GET['role'] ?? 'user';

        $pendingKey = $role === 'admin' ? '2fa_pending_admin' : '2fa_pending_user';
        if (empty($_SESSION[$pendingKey])) {
            header("Location: index.php?action=" . ($role === 'admin' ? 'admin_login' : 'login'));
            exit();
        }

        $pending = $_SESSION[$pendingKey];
        $label   = $role === 'admin' ? ($pending['name'] . ' (Admin)') : $pending['email'];

        if (empty($_SESSION['totp_setup_secret'])) {
            $_SESSION['totp_setup_secret'] = TOTPService::generateSecret();
        }
        $secret     = $_SESSION['totp_setup_secret'];
        $otpauthUri = TOTPService::getOtpauthUri($secret, $label);

        require_once __DIR__ . '/../views/setup_totp.php';
    }

    // ── Confirm TOTP setup ───────────────────────────────────

    public function confirmTOTPSetup() {
        require_once __DIR__ . '/../../app/services/TOTPService.php';

        $role      = $_POST['role']      ?? 'user';
        $secret    = $_POST['secret']    ?? '';
        $totpCode  = trim($_POST['totp_code'] ?? '');

        $pendingKey = $role === 'admin' ? '2fa_pending_admin' : '2fa_pending_user';
        if (empty($_SESSION[$pendingKey])) {
            header("Location: index.php?action=" . ($role === 'admin' ? 'admin_login' : 'login'));
            exit();
        }

        if (!TOTPService::verify($secret, $totpCode)) {
            header("Location: index.php?action=setup_totp&role={$role}&error=invalid");
            exit();
        }

        $pending = $_SESSION[$pendingKey];
        if ($role === 'admin') {
            $this->db->prepare(
                "UPDATE admin SET totp_secret = ?, totp_enabled = 1 WHERE Admin_Id = ?"
            )->execute([$secret, $pending['id']]);
        } else {
            $this->db->prepare(
                "UPDATE user SET totp_secret = ?, totp_enabled = 1 WHERE User_Id = ?"
            )->execute([$secret, $pending['id']]);
        }

        unset($_SESSION['totp_setup_secret']);
        $this->completeLogin($role, $pending);
    }

    // ── TOTP Verify page ─────────────────────────────────────

    public function showVerifyTOTP() {
        $role = $_GET['role'] ?? 'user';
        require_once __DIR__ . '/../views/verify_totp.php';
    }

    public function handleVerifyTOTP() {
        require_once __DIR__ . '/../../app/services/TOTPService.php';

        $role     = $_POST['role']      ?? 'user';
        $totpCode = trim($_POST['totp_code'] ?? '');

        $pendingKey = $role === 'admin' ? '2fa_pending_admin' : '2fa_pending_user';
        if (empty($_SESSION[$pendingKey])) {
            header("Location: index.php?action=" . ($role === 'admin' ? 'admin_login' : 'login'));
            exit();
        }

        $pending = $_SESSION[$pendingKey];
        $secret  = $pending['totp_secret'] ?? '';

        if (!TOTPService::verify($secret, $totpCode)) {
            header("Location: index.php?action=verify_totp&role={$role}&error=invalid");
            exit();
        }

        $this->completeLogin($role, $pending);
    }

    // ── Shared: finalize login after 2FA passes ───────────────

    private function completeLogin(string $role, array $pending): void
    {
        if ($role === 'admin') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id']        = $pending['id'];
            $_SESSION['admin_name']      = $pending['name'];
            unset($_SESSION['2fa_pending_admin']);
            session_regenerate_id(true);
            header("Location: index.php?action=admin_dashboard");
        } else {
            $_SESSION['user_id']    = $pending['id'];
            $_SESSION['user_name']  = $pending['name'];
            $_SESSION['user_email'] = $pending['email'];
            $_SESSION['user_phone'] = $pending['phone'];
            $_SESSION['user_image'] = $pending['image'];
            unset($_SESSION['2fa_pending_user']);
            header("Location: index.php?action=dashboard");
        }
        exit();
    }

    // ── Logout ───────────────────────────────────────────────

    public function logout() {
        session_destroy();
        header("Location: index.php?action=home"); exit();
    }
}