<?php
// 1. ABSOLUTE TOP: Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/controllers/PropertyController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$controller = new PropertyController();
$auth = new AuthController();

// 2. Determine Action
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

switch ($action) {
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
    case 'handleLogin': // Matches your controller method
        $auth->handleLogin();
        break;

    case 'register':
    case 'signup':
        $auth->showSignup();
        break;

    case 'handleSignup': // Matches the action in your register.php form
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
    case 'view_property': // Add this line here!
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $controller->book($id); 
        break;

    case 'confirm_booking':
        $controller->confirmBooking();
        break;

    case 'logout':
        $auth->logout(); // Using the method we added to AuthController
        break;

    default:
        if (isset($_SESSION['user_id'])) {
            $auth->showDashboard();
        } else {
            $controller->index();
        }
        break;
}