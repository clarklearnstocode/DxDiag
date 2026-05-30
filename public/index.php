<?php
// 1. ABSOLUTE TOP: Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine the routing action early so the output buffer can analyze it
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

// Inject global theme stylesheet into views EXCEPT the clean landing screen
ob_start(function ($buffer) use ($action) {
    // Completely isolate home and landing paths from core theme overrides
    if ($action === 'home' || $action === 'landing') {
        return $buffer;
    }
    
    if (stripos($buffer, '</head>') !== false && stripos($buffer, 'assets/css/luxury-theme.css') === false) {
        $themeLink = '<link rel="stylesheet" href="assets/css/luxury-theme.css?v=1">' . "\n";
        return preg_replace('/<\/head>/i', $themeLink . '</head>', $buffer, 1);
    }
    return $buffer;
});

// Define reliable absolute paths for file uploads
define('BASE_PATH',   __DIR__);
define('IMG_PATH',    __DIR__ . '/assets/img/');
define('UPLOAD_PATH', __DIR__ . '/assets/img/uploads/');

// Ensure upload directory exists and is writable
if (!is_dir(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}

// 2. Load Controllers
require_once __DIR__ . '/../app/controllers/PropertyController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/BookingController.php';

// 3. Auto-release expired bookings on every page load
require_once __DIR__ . '/../app/services/AutoRelease.php';
require_once __DIR__ . '/../config/Database.php';
$_autoDb      = (new Database())->getConnection();
$_autoRelease = new AutoRelease($_autoDb);
$_autoRelease->run();
unset($_autoDb, $_autoRelease);   

// 4. Initialize Controllers
$controller = new PropertyController();
$auth       = new AuthController();
$admin      = new AdminController();
$booking    = new BookingController();

switch ($action) {
    /* --- USER ROUTES --- */
    case 'home':
    case 'landing':
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
            exit();
        } else {
            $controller->index();
        }
        break;

    case 'dashboard':
        $auth->showDashboard();
        break;

    case 'login':
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?action=dashboard");
            exit();
        }
        $auth->showLogin();
        break;

    case 'authenticate':
    case 'handleLogin':
        $auth->handleLogin();
        break;

    case 'register':
    case 'signup':
        $auth->showSignup();   
        break;

    case 'handleSignup':
    case 'doSignup':
        $auth->handleSignup();
        break;

    case 'profile':
        $auth->showProfile();
        break;

    case 'update_profile':
        $auth->updateProfile();
        break;

    case 'my_bookings':
        $auth->showMyBookings();
        break;

    case 'book':
    case 'view_property':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $controller->book($id);
        break;

    case 'confirm_booking':
        $controller->confirmBooking();
        break;

    case 'booking_confirmation':
        $controller->showBookingConfirmation();
        break;

    case 'cancel_booking':
        $controller->cancelBooking();
        break;

    case 'edit_booking':
        $controller->showEditBooking();
        break;

    case 'update_booking':
        $controller->updateBooking();
        break;

    case 'setup_totp':
        $auth->showSetupTOTP();
        break;

    case 'confirm_totp_setup':
        $auth->confirmTOTPSetup();
        break;

    case 'verify_totp':
        $auth->showVerifyTOTP();
        break;

    case 'handle_verify_totp':
        $auth->handleVerifyTOTP();
        break;

    case 'logout':
        $auth->logout();
        break;

    /* --- ADMIN ROUTES --- */
    case 'admin_login':
        $admin->showLogin();
        break;

    case 'do_admin_login':
        $admin->handleLogin();
        break;

    case 'admin_dashboard':
        $admin->showDashboard();
        break;

    case 'add_property':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $admin->handleAddProperty();
        } else {
            $admin->showAddProperty();
        }
        break;

    case 'do_add_property':
        $admin->handleAddProperty();
        break;

    case 'edit_property':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $admin->handleEditProperty();
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $admin->showEditProperty($id);
        }
        break;

    case 'delete_property':
        if (isset($_GET['id'])) {
            $admin->deleteProperty(intval($_GET['id']));
        }
        break;

    case 'reservations':
        $admin->showReservations();
        break;

    case 'manage_booking':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $admin->manageBooking($id);
        break;

    case 'update_booking_status':
        $admin->updateBookingStatus();
        break;

    case 'approve_booking':
        if (isset($_GET['id'])) {
            $admin->approveBooking($_GET['id']);
        }
        break;

    case 'user_management':
        $admin->showUserManagement();
        break;

    case 'delete_user':
        if (isset($_GET['id'])) {
            $admin->deleteUser($_GET['id']);
        }
        break;


    case 'broadcast_announcement':
        $admin->handleBroadcast();
        break;

    case 'get_notifications':
        header('Content-Type: application/json');
        $auth->getNotifications();
        break;

    case 'mark_notifications_read':
        header('Content-Type: application/json');
        $auth->markNotificationsRead();
        break;

    case 'pending_count':
        header('Content-Type: application/json');
        $auth->getPendingCount();
        break;

    case 'review_property':
        $controller->showReview();
        break;

    case 'submit_review':
        $controller->submitReview();
        break;

    /* --- DEFAULT --- */
    default:
        if (isset($_SESSION['user_id'])) {
            $auth->showDashboard();
        } else {
            $controller->index();
        }
        break;
}