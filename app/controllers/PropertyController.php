<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../models/Property.php';

class PropertyController {
    private $db;
    private $propertyModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->propertyModel = new Property($this->db);
    }

    // Displays the landing page
    public function index() {
        $stmt = $this->propertyModel->readAll();
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/landing.php';
    }

    // Displays the booking/reservation page for a specific villa
    public function book($id) {
        $property = $this->propertyModel->getOne($id);

        if (!$property) {
            die("Property not found.");
        }

        // Fetch all active (Pending or Confirmed) bookings for this property
        // so the front-end can block those date ranges
        $sql = "SELECT Booking_Id, Check_In, Check_Out
                FROM Booking
                WHERE Property_Id = ?
                  AND Reservation_Status IN ('Pending', 'Confirmed')
                  AND Check_Out >= CURDATE()
                ORDER BY Check_In ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $bookedRanges = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/User/book_property.php';
    }

    /**
     * Handles the reservation process
     */
    public function confirmBooking() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $property_id = $_POST['property_id'];
        $user_id = $_SESSION['user_id']; 
        // Fixed: Use lowercase to match form field names
        $check_in = $_POST['check_in'] ?? '';
        $check_out = $_POST['check_out'] ?? '';
        $payment_method = $_POST['payment_method'];
        
        if (empty($check_in) || empty($check_out)) {
            die("Please select check-in and check-out dates.");
        }

        // Server-side: check for date overlap with existing bookings
        $overlapSql = "SELECT COUNT(*) FROM Booking
                       WHERE Property_Id = ?
                         AND Reservation_Status IN ('Pending', 'Confirmed')
                         AND Check_In < ? AND Check_Out > ?";
        $overlapStmt = $this->db->prepare($overlapSql);
        $overlapStmt->execute([$property_id, $check_out, $check_in]);
        if ($overlapStmt->fetchColumn() > 0) {
            header("Location: index.php?action=view_property&id={$property_id}&error=dates_taken");
            exit();
        }

        $property = $this->propertyModel->getOne($property_id);
        if (!$property) { die("Invalid property selection."); }

        $date1 = new DateTime($check_in);
        $date2 = new DateTime($check_out);
        $days = $date1->diff($date2)->days ?: 1;
        $total_amount = $property['Property_rate'] * $days;

        try {
            $this->db->beginTransaction();

            // 1. Insert into Payment (with Payment_Date)
            $paySql = "INSERT INTO Payment (Payment_Date, Payment_Method, Amount, Status) VALUES (CURDATE(), ?, ?, 'Paid')";
            $payStmt = $this->db->prepare($paySql);
            $payStmt->execute([$payment_method, $total_amount]);
            $payment_id = $this->db->lastInsertId();

            // 2. Insert Booking with Booking_Date, Check_In, Check_Out
            $bookSql = "INSERT INTO Booking (User_Id, Property_Id, Booking_Date, Check_In, Check_Out, Payment_Id, Reservation_Status)
                        VALUES (?, ?, CURDATE(), ?, ?, ?, 'Pending')";
            $bookStmt = $this->db->prepare($bookSql);
            $bookStmt->execute([$user_id, $property_id, $check_in, $check_out, $payment_id]);

            $this->db->commit();
            header("Location: index.php?action=my_bookings&status=success");
            exit();
        } catch (Exception $e) {
            $this->db->rollBack();
            die("Database Error: " . $e->getMessage());
        }
    }
}
}