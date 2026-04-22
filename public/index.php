<?php
// 1. ABSOLUTE TOP: Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Load Controllers
require_once __DIR__ . '/../app/controllers/PropertyController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/BookingController.php';

// 3. Initialize Controllers
$controller = new PropertyController();
$auth = new AuthController();
$admin = new AdminController();
$booking = new BookingController();

// 4. Determine Action
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

switch ($action) {
    /* --- USER ROUTES --- */
    case 'home':
    case 'landing':
        // If logged in, go to dashboard. Otherwise, show landing page.
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

    case 'my_bookings':
        $auth->showMyBookings();
        break;

    case 'book':
    case 'view_property':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $controller->book($id); 
        break;

    // FIX: Only one confirm_booking case — handles POST from user booking form
    case 'confirm_booking':
        $controller->confirmBooking();
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

    case 'reservations':
        $admin->showReservations();
        break;

    // FIX: manage_booking — shows booking detail page with approve/reject actions
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

    // FIX: user_management — now loads real data from DB
    case 'user_management':
        $admin->showUserManagement();
        break;

    case 'delete_user':
        if (isset($_GET['id'])) {
            $admin->deleteUser($_GET['id']);
        }
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