<?php
// Get the action from the URL
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

// Define the path to your views folder
$viewPath = __DIR__ . '/../app/views/';

switch ($action) {
    case 'home':
    case 'landing': 
        include $viewPath . 'landing.php';
        break;

    case 'explore':
        if (file_exists($viewPath . 'explore.php')) {
            include $viewPath . 'explore.php';
        } else {
            echo "Error: explore.php not found in " . $viewPath;
        }
        break;

    case 'login':
        include $viewPath . 'login.php';
        break;

    case 'register':
        include $viewPath . 'register.php';
        break;

    // --- AUTHENTICATION LOGIC (MOCK) ---
    case 'authenticate':
        // For the demo: Accept any POST and redirect to the user dashboard
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header("Location: index.php?action=dashboard");
            exit();
        }
        break;

    // --- USER SIDE ---
    case 'dashboard':
        if (file_exists($viewPath . 'dashboard.php')) {
            include $viewPath . 'dashboard.php';
        } else {
            echo "Error: dashboard.php not found. Please create it in " . $viewPath;
        }
        break;

        case 'dashboard':
        include '../app/views/dashboard.php';
        break;
    case 'my_bookings':
        include '../app/views/my_bookings.php';
        break;
    case 'profile':
        include '../app/views/profile.php';
        break;

    // --- ADMIN SIDE ---
    case 'admin_dashboard':
        if (file_exists($viewPath . 'admin_dashboard.php')) {
            include $viewPath . 'admin_dashboard.php';
        } else {
            echo "Error: admin_dashboard.php not found in " . $viewPath;
        }
        break;

    case 'add_property':
        if (file_exists($viewPath . 'add_property.php')) {
            include $viewPath . 'add_property.php';
        } else {
            echo "Error: add_property.php not found in " . $viewPath;
        }
        break;

    case 'admin_login':
        if (file_exists($viewPath . 'admin_login.php')) {
            include $viewPath . 'admin_login.php';
        } else {
            echo "Error: admin_login.php not found in " . $viewPath;
        }
        break;

    case 'reservations':
    if (file_exists($viewPath . 'reservations.php')) {
        include $viewPath . 'reservations.php';
    } else {
        echo "Error: reservations.php not found in " . $viewPath;
    }
    break;  

    case 'user_management':  // No spaces, all lowercase
        if (file_exists($viewPath . 'user_management.php')) {
            include $viewPath . 'user_management.php';
        } else {
            echo "Error: user_management.php not found in " . $viewPath;
        }
        break;

    case 'dashboard':
      include $viewPath . 'dashboard.php';
      break;

    case 'book_property':
        // This is the page the user sees after clicking a villa
        include $viewPath . 'book_property.php';
        break;

    default:
        include $viewPath . 'landing.php';
        break;
}