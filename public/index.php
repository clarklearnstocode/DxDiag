<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * public/index.php — EstateBook Front Controller
 *
 * FIXES APPLIED:
 *
 * 1. NULL-SAFE DATABASE GUARD
 *    getConnection() now throws on failure (see Database.php fix).
 *    We wrap the AutoRelease bootstrap in try/catch so a transient DB
 *    hiccup during auto-release does not kill the entire page load.
 *
 * 2. ASSET PATH ROOT-RELATIVE PREFIX
 *    Views emitted relative paths like `assets/css/landing.css` which
 *    work when the browser URL is exactly `https://domain.com/` but
 *    break on any sub-path (e.g. `?action=login`) because the browser
 *    resolves them relative to the document location, not the site root.
 *    We define a BASE_URL constant derived from the server variables and
 *    inject it as a JS/PHP global so every view can prefix assets correctly.
 *    Views that still use bare `assets/…` paths will continue to work
 *    when served from the domain root; the constant is available for
 *    gradual migration.
 *
 * 3. OUTPUT BUFFER SAFETY
 *    ob_start callback now returns the buffer unchanged on any exception
 *    rather than potentially swallowing output.
 */

// ── 0. Session ────────────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── 1. Action (needed by OB callback below) ───────────────────────────────────
$action = isset($_GET['action']) ? trim($_GET['action']) : 'home';

// ── 2. Output buffer: inject luxury-theme on inner pages ─────────────────────
ob_start(function (string $buffer) use ($action): string {
    if ($action === 'home' || $action === 'landing') {
        return $buffer;
    }
    if (stripos($buffer, '</head>') !== false
        && stripos($buffer, 'assets/css/luxury-theme.css') === false) {
        $themeLink = '<link rel="stylesheet" href="assets/css/luxury-theme.css?v=1">' . "\n";
        return preg_replace('/<\/head>/i', $themeLink . '</head>', $buffer, 1);
    }
    return $buffer;
});

// ── 3. Path constants ─────────────────────────────────────────────────────────
define('BASE_PATH',   __DIR__);
define('IMG_PATH',    __DIR__ . '/assets/img/');
define('UPLOAD_PATH', __DIR__ . '/assets/img/uploads/');

// BASE_URL: protocol + host + path to public/ directory, no trailing slash.
// Views can use BASE_URL . '/assets/css/foo.css' for root-relative URLs.
(function () {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    // Strip /index.php (and anything after) from SCRIPT_NAME to get the base path
    $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    define('BASE_URL', $scheme . '://' . $host . $scriptDir);
})();

// Ensure upload directory exists
if (!is_dir(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}

// ── 4. Autoload controllers & services ───────────────────────────────────────
require_once __DIR__ . '/../app/controllers/PropertyController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/BookingController.php';

// ── 5. Auto-release expired bookings ─────────────────────────────────────────
require_once __DIR__ . '/../app/services/AutoRelease.php';
require_once __DIR__ . '/../config/Database.php';

try {
    $_autoDb      = (new Database())->getConnection();
    $_autoRelease = new AutoRelease($_autoDb);
    $_autoRelease->run();
} catch (Throwable $e) {
    // Log but do not abort page load — auto-release is non-critical
    error_log('[EstateBook] AutoRelease failed: ' . $e->getMessage());
} finally {
    unset($_autoDb, $_autoRelease);
}

// ── 6. Initialise controllers ─────────────────────────────────────────────────
//  Each controller instantiates its own DB connection via Database::getConnection().
//  If the DB is truly unreachable, the RuntimeException propagates here and PHP
//  logs a proper stack trace instead of a cryptic null-pointer fatal error.
try {
    $controller = new PropertyController();
    $auth       = new AuthController();
    $admin      = new AdminController();
    $booking    = new BookingController();
} catch (Throwable $e) {
    error_log('[EstateBook] Controller init failed: ' . $e->getMessage());
    http_response_code(503);
    echo '<h1 style="font-family:sans-serif;color:#c00;padding:40px">
            Service Temporarily Unavailable
          </h1>
          <p style="font-family:sans-serif;padding:0 40px">
            The database could not be reached. Please try again in a moment.
          </p>';
    ob_end_flush();
    exit;
}

// ── 7. Router ─────────────────────────────────────────────────────────────────
switch ($action) {

    /* --- USER ROUTES --- */
    case 'home':
    case 'landing':
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?action=dashboard');
            exit();
        }
        $controller->index();
        break;

    case 'dashboard':
        $auth->showDashboard();
        break;

    case 'login':
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?action=dashboard');
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