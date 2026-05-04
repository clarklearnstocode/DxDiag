<?php
// Using absolute paths to ensure the files are always found
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/Property.php'; // Needed for dashboard data

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        // Updated to match your model filename 'Users.php'
        $this->user = new User($this->db);
    }

    public function showLogin() {
        require_once __DIR__ . '/../views/login.php';
    }

    public function showSignup() {
        require_once __DIR__ . '/../views/register.php';
    }

    /**
     * Display the User Dashboard
     * Pointing to the new views/User/ folder structure
     */
    public function showDashboard() {
        // Security check: Redirect to login if session isn't set
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login&error=unauthorized");
            exit();
        }

        // Fetch properties to display on the dashboard grid
        $propertyModel = new Property($this->db);
        $stmt = $propertyModel->readAll();
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Path updated to your new subfolder: app/views/User/dashboard.php
        require_once __DIR__ . '/../views/User/dashboard.php';
    }

    // Handle the Profile Page — load fresh data from DB
    public function showProfile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        // Refresh session with latest DB data
        $stmt = $this->db->prepare("SELECT * FROM User WHERE User_Id = ? LIMIT 1");
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

    // Handle Profile Update (name, email, phone, password, photo)
    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $userId   = $_SESSION['user_id'];
        $name     = trim($_POST['full_name']   ?? '');
        $email    = trim($_POST['email']       ?? '');
        $phone    = trim($_POST['phone']       ?? '');
        $curPw    = $_POST['current_password'] ?? '';
        $newPw    = $_POST['new_password']     ?? '';
        $conPw    = $_POST['confirm_password'] ?? '';

        // ── 1. Check email not taken by another user ──
        $stmt = $this->db->prepare("SELECT User_Id FROM User WHERE Email = ? AND User_Id != ?");
        $stmt->execute([$email, $userId]);
        if ($stmt->fetch()) {
            header("Location: index.php?action=profile&error=email_taken");
            exit();
        }

        // ── 2. Handle profile photo upload ──
        $newImage = null;
        if (!empty($_FILES['profile_photo']['name']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg','image/png','image/webp'];
            $mime    = mime_content_type($_FILES['profile_photo']['tmp_name']);
            if (!in_array($mime, $allowed) || $_FILES['profile_photo']['size'] > 2 * 1024 * 1024) {
                header("Location: index.php?action=profile&error=upload_failed");
                exit();
            }
            $ext      = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
            $safeName = 'user_' . $userId . '_' . time() . '.' . strtolower($ext);
            // Use UPLOAD_PATH constant (defined in index.php → public/assets/img/uploads/)
            if (!is_dir(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0755, true);
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], UPLOAD_PATH . $safeName)) {
                $newImage = $safeName;
            }
        }

        // ── 3. Handle password change ──
        $newHashedPw = null;
        if (!empty($newPw)) {
            // Verify current password
            $stmt = $this->db->prepare("SELECT Password FROM User WHERE User_Id = ?");
            $stmt->execute([$userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row || !password_verify($curPw, $row['Password'])) {
                header("Location: index.php?action=profile&error=wrong_password");
                exit();
            }
            if ($newPw === $conPw && strlen($newPw) >= 6) {
                $newHashedPw = password_hash($newPw, PASSWORD_DEFAULT);
            }
        }

        // ── 4. Build UPDATE query dynamically ──
        $fields = "Name = ?, Email = ?, Phone = ?";
        $params = [$name, $email, $phone];

        if ($newHashedPw) {
            $fields .= ", Password = ?";
            $params[] = $newHashedPw;
        }
        if ($newImage) {
            $fields .= ", profile_image = ?";
            $params[] = $newImage;
        }
        $params[] = $userId;

        $stmt = $this->db->prepare("UPDATE User SET {$fields} WHERE User_Id = ?");
        $stmt->execute($params);

        // ── 5. Refresh session ──
        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_phone'] = $phone;
        if ($newImage) $_SESSION['user_image'] = $newImage;

        header("Location: index.php?action=profile&success=1");
        exit();
    }

    // Handle the My Bookings Page
    public function showMyBookings() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $user_id = $_SESSION['user_id'];

        $query = "SELECT
                    b.Booking_Id,
                    b.Booking_Date,
                    b.Check_In,
                    b.Check_In_Time,
                    b.Check_Out,
                    b.Check_Out_Time,
                    b.Reservation_Status,
                    p.Property_Name,
                    p.Property_location,
                    p.image_path,
                    pay.Amount,
                    pay.Payment_Method
                  FROM Booking b
                  JOIN Property p ON b.Property_Id = p.Property_Id
                  LEFT JOIN Payment pay ON b.Payment_Id = pay.Payment_Id
                  WHERE b.User_Id = ?
                  ORDER BY b.Booking_Id DESC";
                  
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id]);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/User/my_bookings.php';
    }

    // Handle Signup Logic
    public function handleSignup() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User($this->db);
            
            $user->Name     = $_POST['full_name'] ?? ''; 
            $user->Phone    = $_POST['phone'] ?? ''; 
            $user->Email    = $_POST['email'] ?? ''; 
            $user->Username = $_POST['username'] ?? ''; 
            $user->Password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);

            if ($user->register()) {
                header("Location: index.php?action=login&success=registered");
                exit();
            } else {
                header("Location: index.php?action=register&error=failed");
                exit();
            }
        }
    }

    // Handle Login Logic - UPDATED TO ALIGN WITH YOUR NEW LOGIN PAGE
    public function handleLogin() {
        ob_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ob_end_clean();
            header("Location: index.php?action=login");
            exit();
        }

        $identity = $_POST['login_identity'] ?? '';
        $password = $_POST['password']       ?? '';

        $loggedUser = $this->user->login($identity);

        if ($loggedUser && password_verify($password, $loggedUser['Password'])) {
            ob_end_clean();

            // ── 2FA: generate OTP and send email ──
            require_once __DIR__ . '/../../app/services/OTPService.php';
            require_once __DIR__ . '/../../app/services/Mailer.php';

            $otp = OTPService::generate('user');

            // Store user data in session temporarily (not fully logged in yet)
            $_SESSION['2fa_pending_user'] = [
                'id'    => $loggedUser['User_Id'],
                'name'  => $loggedUser['Name'],
                'email' => $loggedUser['Email'],
                'phone' => $loggedUser['Phone']          ?? '',
                'image' => $loggedUser['profile_image']  ?? '',
            ];

            $result = Mailer::sendOTP($loggedUser['Email'], $loggedUser['Name'], $otp, 'User');

            if ($result === true) {
                header("Location: index.php?action=verify_otp&role=user");
            } else {
                // Email failed — log the error and bypass 2FA so login still works
                error_log("EstateBook Mailer error (user): $result");
                header("Location: index.php?action=verify_otp&role=user&mail_error=1");
            }
            exit();
        } else {
            ob_end_clean();
            header("Location: index.php?action=login&error=invalid");
            exit();
        }
    }

    // Show OTP verification page
    public function showVerifyOTP() {
        require_once __DIR__ . '/../../app/services/OTPService.php';
        $role       = $_GET['role']       ?? 'user';
        $mailError  = $_GET['mail_error'] ?? '0';
        $secondsLeft = OTPService::secondsLeft();
        require_once __DIR__ . '/../views/verify_otp.php';
    }

    // Handle OTP form submission for user
    public function handleVerifyOTP() {
        require_once __DIR__ . '/../../app/services/OTPService.php';

        $otp  = trim($_POST['otp_code'] ?? '');
        $role = trim($_POST['role']     ?? 'user');

        if (!OTPService::verify($otp, $role)) {
            header("Location: index.php?action=verify_otp&role={$role}&error=invalid");
            exit();
        }

        if ($role === 'user') {
            $pending = $_SESSION['2fa_pending_user'] ?? null;
            if (!$pending) {
                header("Location: index.php?action=login");
                exit();
            }
            // OTP passed — fully log user in
            $_SESSION['user_id']    = $pending['id'];
            $_SESSION['user_name']  = $pending['name'];
            $_SESSION['user_email'] = $pending['email'];
            $_SESSION['user_phone'] = $pending['phone'];
            $_SESSION['user_image'] = $pending['image'];
            unset($_SESSION['2fa_pending_user']);
            header("Location: index.php?action=dashboard");
            exit();

        } elseif ($role === 'admin') {
            $pending = $_SESSION['2fa_pending_admin'] ?? null;
            if (!$pending) {
                header("Location: index.php?action=admin_login");
                exit();
            }
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id']        = $pending['id'];
            $_SESSION['admin_name']      = $pending['name'];
            unset($_SESSION['2fa_pending_admin']);
            header("Location: index.php?action=admin_dashboard");
            exit();
        }

        header("Location: index.php?action=login");
        exit();
    }

    // Handle Logout
    public function logout() {
        session_destroy();
        header("Location: index.php?action=landing");
        exit();
    }
}