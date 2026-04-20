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

    // Handle the Profile Page
    public function showProfile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        require_once __DIR__ . '/../views/User/profile.php';
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
                    b.Reservation_Status, 
                    p.Property_Name, 
                    p.Property_location, 
                    pay.Amount 
                  FROM Booking b 
                  JOIN Property p ON b.Property_Id = p.Property_Id 
                  JOIN Payment pay ON b.Payment_Id = pay.Payment_Id
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
                // Map session keys to your actual DB column names (User_Id, Name)
                $_SESSION['user_id'] = $loggedUser['User_Id'];
                $_SESSION['user_name'] = $loggedUser['Name'];
                
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