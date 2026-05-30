<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/Property.php';

class AdminController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // ── Auth guard helper ──
    private function requireAdmin() {
        if (empty($_SESSION['admin_logged_in']) || empty($_SESSION['admin_id'])) {
            header("Location: index.php?action=admin_login");
            exit();
        }
    }

    // ── Insert a user notification ──
    private function insertNotification(int $userId, string $message): void {
        $stmt = $this->db->prepare(
            "INSERT INTO notifications (user_id, message) VALUES (?, ?)"
        );
        $stmt->execute([$userId, $message]);
    }

    public function showLogin() {
        if (!empty($_SESSION['admin_logged_in']) && !empty($_SESSION['admin_id'])) {
            header("Location: index.php?action=admin_dashboard");
            exit();
        }
        require_once __DIR__ . '/../views/Admin/admin_login.php';
    }

    public function handleLogin() {
        $username = $_POST['username'] ?? '';
        $passcode = $_POST['passcode'] ?? '';

        $query = "SELECT * FROM Admin WHERE Username = ? LIMIT 1";
        $stmt  = $this->db->prepare($query);
        $stmt->execute([$username]);
        $admin_user = $stmt->fetch(PDO::FETCH_ASSOC);

        $validPassword = false;
        if ($admin_user) {
            $stored = (string)($admin_user['Password'] ?? '');
            $validPassword = password_verify($passcode, $stored) || hash_equals($stored, $passcode);
        }

        if ($admin_user && $validPassword) {
            session_regenerate_id(true);

            $_SESSION['admin_id']   = (int)$admin_user['Admin_Id'];
            $_SESSION['admin_name'] = $admin_user['Admin_Name'];

            $totpEnabled = !empty($admin_user['totp_enabled']);
            $totpSecret  = trim((string)($admin_user['totp_secret'] ?? ''));

            if ($totpEnabled && $totpSecret !== '') {
                $_SESSION['admin_logged_in'] = false;
                $_SESSION['2fa_pending_admin'] = [
                    'id'           => (int)$admin_user['Admin_Id'],
                    'name'         => $admin_user['Admin_Name'],
                    'totp_enabled' => 1,
                    'totp_secret'  => $totpSecret,
                ];
                header("Location: index.php?action=verify_totp&role=admin");
            } else {
                $_SESSION['admin_logged_in'] = true;
                unset($_SESSION['2fa_pending_admin']);
                header("Location: index.php?action=admin_dashboard");
            }
            exit();
        } else {
            header("Location: index.php?action=admin_login&error=invalid");
            exit();
        }
    }

    public function showDashboard() {
        $this->requireAdmin();

        $propCount         = $this->db->query("SELECT COUNT(*) FROM Property")->fetchColumn();
        $bookCount         = $this->db->query("SELECT COUNT(*) FROM Booking")->fetchColumn();
        $revenue           = $this->db->query("SELECT SUM(Amount) FROM Payment WHERE Status = 'Paid'")->fetchColumn();
        $stmt              = $this->db->query("SELECT * FROM Property ORDER BY Property_Id DESC");
        $recent_properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/Admin/admin_dashboard.php';
    }

    // ── ANNOUNCEMENT BROADCAST ──
    public function handleBroadcast() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=admin_dashboard"); exit();
        }
        $message = trim($_POST['broadcast_message'] ?? '');
        if ($message !== '') {
            $stmt = $this->db->prepare("INSERT INTO announcements (message) VALUES (?)");
            $stmt->execute([$message]);
        }
        header("Location: index.php?action=admin_dashboard&success=broadcast");
        exit();
    }

    // ── ADD PROPERTY ──
    public function showAddProperty() {
        $this->requireAdmin();
        require_once __DIR__ . '/../views/Admin/add_property.php';
    }

    public function handleAddProperty() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=add_property"); exit();
        }

        $image_name = 'villa1.png';
        if (!empty($_FILES['property_image']['name']) && $_FILES['property_image']['error'] === UPLOAD_ERR_OK) {
            $original    = basename($_FILES['property_image']['name']);
            $safe_name   = preg_replace('/[^a-zA-Z0-9.\-_]/', '_', $original);
            $target_file = IMG_PATH . $safe_name;
            if (move_uploaded_file($_FILES['property_image']['tmp_name'], $target_file)) {
                $image_name = $safe_name;
            }
        }

        $name        = trim($_POST['property_name'] ?? '');
        $location    = trim($_POST['location']      ?? '');
        $rate        = floatval($_POST['rate']       ?? 0);
        $size        = intval($_POST['size']         ?? 0);
        $bathrooms   = intval($_POST['bathrooms']    ?? 0);
        $capacity    = intval($_POST['capacity']     ?? 0);
        $has_pool    = isset($_POST['has_pool'])     ? 1 : 0;
        $description = trim($_POST['description']   ?? '');

        $sql  = "INSERT INTO Property
                     (Property_Name, Property_location, Property_rate, Property_size,
                      Property_bathrooms, Property_capacity, Has_pool,
                      Property_Description, Status, image_path)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Available', ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $name, $location, $rate, $size,
            $bathrooms, $capacity, $has_pool,
            $description, $image_name
        ]);

        header("Location: index.php?action=admin_dashboard&success=added");
        exit();
    }

    // ── EDIT PROPERTY ──
    public function showEditProperty($id) {
        $this->requireAdmin();
        $stmt = $this->db->prepare("SELECT * FROM Property WHERE Property_Id = ? LIMIT 1");
        $stmt->execute([$id]);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$property) {
            header("Location: index.php?action=admin_dashboard&error=notfound");
            exit();
        }
        require_once __DIR__ . '/../views/Admin/edit_property.php';
    }

    public function handleEditProperty() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=admin_dashboard"); exit();
        }

        $id          = intval($_POST['property_id'] ?? 0);
        $name        = trim($_POST['property_name'] ?? '');
        $location    = trim($_POST['location']      ?? '');
        $rate        = floatval($_POST['rate']       ?? 0);
        $size        = intval($_POST['size']         ?? 0);
        $bathrooms   = intval($_POST['bathrooms']    ?? 0);
        $capacity    = intval($_POST['capacity']     ?? 0);
        $has_pool    = isset($_POST['has_pool'])     ? 1 : 0;
        $description = trim($_POST['description']   ?? '');
        $status      = trim($_POST['status']         ?? 'Available');

        $image_name = trim($_POST['current_image']  ?? 'villa1.png');
        if (!empty($_FILES['property_image']['name']) && $_FILES['property_image']['error'] === UPLOAD_ERR_OK) {
            $original    = basename($_FILES['property_image']['name']);
            $safe_name   = preg_replace('/[^a-zA-Z0-9.\-_]/', '_', $original);
            $target_file = IMG_PATH . $safe_name;
            if (move_uploaded_file($_FILES['property_image']['tmp_name'], $target_file)) {
                $image_name = $safe_name;
            }
        }

        $sql  = "UPDATE Property SET
                     Property_Name        = ?,
                     Property_location    = ?,
                     Property_rate        = ?,
                     Property_size        = ?,
                     Property_bathrooms   = ?,
                     Property_capacity    = ?,
                     Has_pool             = ?,
                     Property_Description = ?,
                     Status               = ?,
                     image_path           = ?
                 WHERE Property_Id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $name, $location, $rate, $size,
            $bathrooms, $capacity, $has_pool,
            $description, $status, $image_name, $id
        ]);

        header("Location: index.php?action=admin_dashboard&success=updated");
        exit();
    }

    // ── DELETE PROPERTY ──
    public function deleteProperty($id) {
        $this->requireAdmin();
        $stmt = $this->db->prepare("DELETE FROM Property WHERE Property_Id = ?");
        $stmt->execute([$id]);
        header("Location: index.php?action=admin_dashboard&success=deleted");
        exit();
    }

    // ── RESERVATIONS ──
    public function showReservations() {
        $this->requireAdmin();

        $query = "SELECT b.*, u.Name, p.Property_Name, p.Property_rate, p.image_path,
                         pay.Amount, pay.Payment_Method
                  FROM Booking b
                  JOIN User u ON b.User_Id = u.User_Id
                  JOIN Property p ON b.Property_Id = p.Property_Id
                  LEFT JOIN Payment pay ON b.Payment_Id = pay.Payment_Id
                  ORDER BY b.Booking_Id DESC";
        $stmt = $this->db->query($query);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/Admin/reservations.php';
    }

    public function updateBookingStatus() {
        $this->requireAdmin();

        $bookingId = intval($_GET['id']    ?? 0);
        $newStatus = $_GET['status']       ?? '';
        $allowed   = ['Confirmed', 'Rejected', 'Pending'];

        if (!in_array($newStatus, $allowed) || !$bookingId) {
            header("Location: index.php?action=reservations&error=invalid"); exit();
        }

        // Fetch booking details for notification message
        $infoStmt = $this->db->prepare(
            "SELECT b.User_Id, p.Property_Name
             FROM Booking b
             JOIN Property p ON b.Property_Id = p.Property_Id
             WHERE b.Booking_Id = ?"
        );
        $infoStmt->execute([$bookingId]);
        $bookingInfo = $infoStmt->fetch(PDO::FETCH_ASSOC);

        // Update booking status — properties stay Available (non-blocking model)
        $stmt = $this->db->prepare("UPDATE Booking SET Reservation_Status = ? WHERE Booking_Id = ?");
        $stmt->execute([$newStatus, $bookingId]);

        // Fire notification to the user
        if ($bookingInfo) {
            $propName = $bookingInfo['Property_Name'];
            $userId   = (int)$bookingInfo['User_Id'];
            if ($newStatus === 'Confirmed') {
                $this->insertNotification($userId, "Your reservation for {$propName} has been Approved!");
            } elseif ($newStatus === 'Rejected') {
                $this->insertNotification($userId, "Your reservation for {$propName} has been Rejected.");
            }
        }

        $from = $_GET['from'] ?? '';
        header($from === 'manage'
            ? "Location: index.php?action=manage_booking&id={$bookingId}&success=1"
            : "Location: index.php?action=reservations&success=updated");
        exit();
    }

    public function approveBooking($id) {
        $this->requireAdmin();

        // Fetch booking info for notification
        $infoStmt = $this->db->prepare(
            "SELECT b.User_Id, p.Property_Name
             FROM Booking b
             JOIN Property p ON b.Property_Id = p.Property_Id
             WHERE b.Booking_Id = ?"
        );
        $infoStmt->execute([$id]);
        $bookingInfo = $infoStmt->fetch(PDO::FETCH_ASSOC);

        $this->db->prepare("UPDATE Booking SET Reservation_Status = 'Confirmed' WHERE Booking_Id = ?")
                 ->execute([$id]);

        // Notify user — property stays Available (non-blocking model)
        if ($bookingInfo) {
            $this->insertNotification(
                (int)$bookingInfo['User_Id'],
                "Your reservation for {$bookingInfo['Property_Name']} has been Approved!"
            );
        }

        header("Location: index.php?action=reservations&success=confirmed");
        exit();
    }

    public function manageBooking($id) {
        $this->requireAdmin();

        $query = "SELECT b.*, u.Name, u.Email, u.Phone,
                         p.Property_Name, p.Property_location, p.Property_rate, p.image_path,
                         pay.Payment_Method, pay.Amount, pay.Status AS Payment_Status
                  FROM Booking b
                  JOIN User u ON b.User_Id = u.User_Id
                  JOIN Property p ON b.Property_Id = p.Property_Id
                  LEFT JOIN Payment pay ON b.Payment_Id = pay.Payment_Id
                  WHERE b.Booking_Id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            header("Location: index.php?action=reservations&error=notfound"); exit();
        }
        require_once __DIR__ . '/../views/Admin/manage_booking.php';
    }

    // ── USER MANAGEMENT ──
    public function showUserManagement() {
        $this->requireAdmin();
        $users = $this->db->query("SELECT * FROM User ORDER BY User_Id DESC")->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/Admin/user_management.php';
    }

    public function deleteUser($id) {
        $this->requireAdmin();
        $this->db->prepare("DELETE FROM User WHERE User_Id = ?")->execute([$id]);
        header("Location: index.php?action=user_management&success=deleted");
        exit();
    }
}
