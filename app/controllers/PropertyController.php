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
        // Fetch the specific property using the getOne method in your Model
        // This will include image_path, Property_Name, Property_rate, etc.
        $property = $this->propertyModel->getOne($id); 

        if (!$property) {
            die("Property not found. ID: " . htmlspecialchars($id));
        }

        // Pass the $property variable to the view
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
            $check_in = $_POST['check_in'];
            $check_out = $_POST['check_out'];
            $payment_method = $_POST['payment_method'];

            // 1. Fetch property rate to calculate total
            $property = $this->propertyModel->getOne($property_id);
            if (!$property) {
                die("Invalid property selection.");
            }

            // 2. Calculate total amount based on days
            $date1 = new DateTime($check_in);
            $date2 = new DateTime($check_out);
            $interval = $date1->diff($date2);
            $days = $interval->days;
            if ($days <= 0) $days = 1; // Minimum 1 night charge

            $total_amount = $property['Property_rate'] * $days;

            try {
                $this->db->beginTransaction();

                // 3. Insert into Payment Table
                $paySql = "INSERT INTO Payment (Payment_Method, Amount, Status) VALUES (?, ?, 'Paid')";
                $payStmt = $this->db->prepare($paySql);
                $payStmt->execute([$payment_method, $total_amount]);
                $payment_id = $this->db->lastInsertId();

                // 4. Insert into Booking Table
                // Note: Using check_in as the Booking_Date for reservation context
                $bookSql = "INSERT INTO Booking (User_Id, Property_Id, Booking_Date, Payment_Id, Reservation_Status) 
                            VALUES (?, ?, ?, ?, 'Confirmed')";
                $bookStmt = $this->db->prepare($bookSql);
                $bookStmt->execute([$user_id, $property_id, $check_in, $payment_id]);

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