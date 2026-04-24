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
                    b.Check_Out,
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
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Updated key to match login.php: login_identity
            $identity = $_POST['login_identity'] ?? '';
            $password = $_POST['password'] ?? '';

            // This calls the login method in Users.php (ensure it accepts 1 argument)
            $loggedUser = $this->user->login($identity);

            if ($loggedUser && password_verify($password, $loggedUser['Password'])) {
                $_SESSION['user_id']    = $loggedUser['User_Id'];
                $_SESSION['user_name']  = $loggedUser['Name'];
                $_SESSION['user_email'] = $loggedUser['Email'];
                $_SESSION['user_phone'] = $loggedUser['Phone'] ?? '';
                $_SESSION['user_image'] = $loggedUser['profile_image'] ?? '';
                
                ob_end_clean();
                // Redirect to your user dashboard
                header("Location: index.php?action=dashboard");
                exit(); 
            } else {
                ob_end_clean();
                header("Location: index.php?action=login&error=invalid");
                exit();
            }
        }
    }

    // Handle Logout
    public function logout() {
        session_destroy();
        header("Location: index.php?action=landing");
        exit();
    }
}