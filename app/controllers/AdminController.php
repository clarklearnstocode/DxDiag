<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/Property.php';

class AdminController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function showLogin() {
        require_once __DIR__ . '/../views/Admin/admin_login.php';
    }

    public function handleLogin() {
    $username = $_POST['username'] ?? '';
    $passcode = $_POST['passcode'] ?? '';

    // authentication 
    $query = "SELECT * FROM Admin WHERE Username = ? AND Password = ? LIMIT 1";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$username, $passcode]);
    $admin_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin_user) {
        // Success! Store admin info in session
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin_user['Admin_Id'];
        $_SESSION['admin_name'] = $admin_user['Admin_Name'];
        
        header("Location: index.php?action=admin_dashboard");
        exit();
    } else {
        header("Location: index.php?action=admin_login&error=invalid");
        exit();
    }
}

    public function showDashboard() {
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: index.php?action=admin_login");
        exit();
    }

    // QUERIES for dashboard stats 
    $propCount = $this->db->query("SELECT COUNT(*) FROM Property")->fetchColumn();
    $bookCount = $this->db->query("SELECT COUNT(*) FROM Booking")->fetchColumn();
    $revenue = $this->db->query("SELECT SUM(Amount) FROM Payment WHERE Status = 'Paid'")->fetchColumn();
    $stmt = $this->db->query("SELECT * FROM Property ORDER BY Property_Id DESC LIMIT 5");
    $recent_properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

    require_once __DIR__ . '/../views/Admin/admin_dashboard.php';
  }

  // AdminController class
public function showAddProperty() {
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: index.php?action=admin_login");
        exit();
    }
    require_once __DIR__ . '/../views/Admin/add_property.php';
}

// Handle the form submission for adding a new property
public function handleAddProperty() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle Image Upload
        $image_name = $_FILES['image_path']['name'];
        $target_dir = __DIR__ . "/../../public/assets/img/";
        $target_file = $target_dir . basename($image_name);
        
        // Move the file to your assets folder
        move_uploaded_file($_FILES['image_path']['tmp_name'], $target_file);

        $name = $_POST['property_name'];
        $location = $_POST['location'];
        $rate = $_POST['rate'];
        $size = $_POST['size'];
        $bathrooms = $_POST['bathrooms'];
        $bedrooms = $_POST['bedrooms'];
        $description = $_POST['description']; // New variable!
        $status = 'Available';

        $sql = "INSERT INTO Property (Property_Name, Property_location, Property_rate, Property_size, Property_bathrooms, Property_Description, Status, image_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        // Ensure the array matches the order of the '?' in your SQL
        $stmt->execute([
            $name, 
            $location, 
            $rate, 
            $size, 
            $bathrooms, 
            $description, 
            $status, 
            $image_name
        ]);

        header("Location: index.php?action=admin_dashboard&success=added");
        exit();
    }
}

// Show reservations page with joined data from Booking, User, and Property tables
// Show reservations page with joined data
public function showReservations() {
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: index.php?action=admin_login");
        exit();
    }

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
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: index.php?action=admin_login");
        exit();
    }

    $bookingId = intval($_GET['id'] ?? 0);
    $newStatus  = $_GET['status'] ?? '';

    $allowed = ['Confirmed', 'Rejected', 'Pending'];
    if (!in_array($newStatus, $allowed) || !$bookingId) {
        header("Location: index.php?action=reservations&error=invalid");
        exit();
    }

    $stmt = $this->db->prepare("UPDATE Booking SET Reservation_Status = ? WHERE Booking_Id = ?");
    $stmt->execute([$newStatus, $bookingId]);

    // Keep property status in sync
    if ($newStatus === 'Confirmed') {
        $stmt = $this->db->prepare("UPDATE Property SET Status = 'Occupied' WHERE Property_Id = (SELECT Property_Id FROM Booking WHERE Booking_Id = ?)");
        $stmt->execute([$bookingId]);
    } elseif ($newStatus === 'Rejected') {
        $stmt = $this->db->prepare("UPDATE Property SET Status = 'Available' WHERE Property_Id = (SELECT Property_Id FROM Booking WHERE Booking_Id = ?)");
        $stmt->execute([$bookingId]);
    }

    // Redirect back to manage page if coming from there
    $from = $_GET['from'] ?? '';
    if ($from === 'manage') {
        header("Location: index.php?action=manage_booking&id={$bookingId}&success=1");
    } else {
        header("Location: index.php?action=reservations&success=updated");
    }
    exit();
}

public function approveBooking($id) {
    $stmt = $this->db->prepare("UPDATE Booking SET Reservation_Status = 'Confirmed' WHERE Booking_Id = ?");
    $stmt->execute([$id]);
    $stmt = $this->db->prepare("UPDATE Property SET Status = 'Occupied' WHERE Property_Id = (SELECT Property_Id FROM Booking WHERE Booking_Id = ?)");
    $stmt->execute([$id]);
    header("Location: index.php?action=reservations&success=confirmed");
    exit();
}

// Show manage_booking detail page for a single booking
public function manageBooking($id) {
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: index.php?action=admin_login");
        exit();
    }
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
        header("Location: index.php?action=reservations&error=notfound");
        exit();
    }
    require_once __DIR__ . '/../views/Admin/manage_booking.php';
}

// Show User Management with live DB data
public function showUserManagement() {
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: index.php?action=admin_login");
        exit();
    }
    $users = $this->db->query("SELECT * FROM User ORDER BY User_Id DESC")->fetchAll(PDO::FETCH_ASSOC);
    require_once __DIR__ . '/../views/Admin/user_management.php';
}

// Delete a user account
public function deleteUser($id) {
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: index.php?action=admin_login");
        exit();
    }
    $stmt = $this->db->prepare("DELETE FROM User WHERE User_Id = ?");
    $stmt->execute([$id]);
    header("Location: index.php?action=user_management&success=deleted");
    exit();
}

}