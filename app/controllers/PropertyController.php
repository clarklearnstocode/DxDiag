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

    // ── Landing page — only 3 featured properties ──
    public function index() {
        // readFeatured(3) returns only 3 properties for the "Our Featured Estates" section
        // The full list is only visible inside the authenticated user dashboard
        $stmt       = $this->propertyModel->readFeatured(3);
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once __DIR__ . '/../views/landing.php';
    }

    // ── Book / reserve page for a single property ──
    public function book($id) {
        $property = $this->propertyModel->getOne($id);

        if (!$property) {
            die("Property not found.");
        }

        // Fetch all active bookings so the front-end can block those date ranges
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

    // ── Handle booking form submission ──
    public function confirmBooking() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=dashboard");
            exit();
        }

        $property_id     = intval($_POST['property_id']   ?? 0);
        $user_id         = $_SESSION['user_id'];
        $check_in        = trim($_POST['check_in']        ?? '');
        $check_out       = trim($_POST['check_out']       ?? '');
        $check_in_time   = trim($_POST['check_in_time']   ?? '14:00');
        $check_out_time  = trim($_POST['check_out_time']  ?? '12:00');
        $payment_method  = trim($_POST['payment_method']  ?? '');

        if (empty($check_in) || empty($check_out)) {
            header("Location: index.php?action=view_property&id={$property_id}&error=dates");
            exit();
        }

        // Server-side date overlap check
        $overlapStmt = $this->db->prepare(
            "SELECT COUNT(*) FROM Booking
             WHERE Property_Id = ?
               AND Reservation_Status IN ('Pending', 'Confirmed')
               AND Check_In < ? AND Check_Out > ?"
        );
        $overlapStmt->execute([$property_id, $check_out, $check_in]);
        if ($overlapStmt->fetchColumn() > 0) {
            header("Location: index.php?action=view_property&id={$property_id}&error=dates_taken");
            exit();
        }

        $property = $this->propertyModel->getOne($property_id);
        if (!$property) {
            die("Invalid property selection.");
        }

        $d1           = new DateTime($check_in);
        $d2           = new DateTime($check_out);
        $days         = $d1->diff($d2)->days ?: 1;
        $total_amount = $property['Property_rate'] * $days;

        try {
            $this->db->beginTransaction();

            // 1. Insert Payment
            $payStmt = $this->db->prepare(
                "INSERT INTO Payment (Payment_Date, Payment_Method, Amount, Status)
                 VALUES (CURDATE(), ?, ?, 'Paid')"
            );
            $payStmt->execute([$payment_method, $total_amount]);
            $payment_id = $this->db->lastInsertId();

            // 2. Insert Booking (capture ID before commit)
            $bookStmt = $this->db->prepare(
                "INSERT INTO Booking
                     (User_Id, Property_Id, Booking_Date, Check_In, Check_In_Time,
                      Check_Out, Check_Out_Time, Payment_Id, Reservation_Status)
                 VALUES (?, ?, CURDATE(), ?, ?, ?, ?, ?, 'Pending')"
            );
            $bookStmt->execute([
                $user_id, $property_id,
                $check_in, $check_in_time,
                $check_out, $check_out_time,
                $payment_id
            ]);
            $booking_id = $this->db->lastInsertId(); // captured BEFORE commit

            $this->db->commit();

            // Redirect to thank-you confirmation page
            header("Location: index.php?action=booking_confirmation&booking_id=" . $booking_id);
            exit();

        } catch (Exception $e) {
            $this->db->rollBack();
            die("Database Error: " . $e->getMessage());
        }
    }

    // ── Thank-you / confirmation page after booking ──
    public function showBookingConfirmation() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $booking_id = intval($_GET['booking_id'] ?? 0);

        $stmt = $this->db->prepare(
            "SELECT b.*, p.Property_Name, p.Property_location, p.Property_rate,
                    p.image_path, pay.Payment_Method, pay.Amount
             FROM Booking b
             JOIN Property p  ON b.Property_Id  = p.Property_Id
             LEFT JOIN Payment pay ON b.Payment_Id = pay.Payment_Id
             WHERE b.Booking_Id = ? AND b.User_Id = ?"
        );
        $stmt->execute([$booking_id, $_SESSION['user_id']]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            header("Location: index.php?action=my_bookings");
            exit();
        }

        require_once __DIR__ . '/../views/User/booking_confirmation.php';
    }

    // ── Cancel a pending booking ──
    public function cancelBooking() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $booking_id = intval($_GET['id'] ?? 0);

        // Only allow cancel if booking belongs to this user AND is still Pending
        $stmt = $this->db->prepare(
            "SELECT Booking_Id, Property_Id, Payment_Id
             FROM Booking
             WHERE Booking_Id = ? AND User_Id = ? AND Reservation_Status = 'Pending'"
        );
        $stmt->execute([$booking_id, $_SESSION['user_id']]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            header("Location: index.php?action=my_bookings&error=cannot_cancel");
            exit();
        }

        // Mark booking as Cancelled
        $this->db->prepare("UPDATE Booking SET Reservation_Status = 'Cancelled' WHERE Booking_Id = ?")
                 ->execute([$booking_id]);

        // Set property back to Available
        $this->db->prepare(
            "UPDATE Property SET Status = 'Available' WHERE Property_Id = ?"
        )->execute([$booking['Property_Id']]);

        header("Location: index.php?action=my_bookings&success=cancelled");
        exit();
    }

    // ── Show edit-booking form (change dates / payment method) ──
    public function showEditBooking() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }

        $booking_id = intval($_GET['id'] ?? 0);

        $stmt = $this->db->prepare(
            "SELECT b.*, p.Property_Name, p.Property_location, p.Property_rate,
                    p.image_path, p.Property_Id,
                    pay.Payment_Method, pay.Amount
             FROM Booking b
             JOIN Property p  ON b.Property_Id  = p.Property_Id
             LEFT JOIN Payment pay ON b.Payment_Id = pay.Payment_Id
             WHERE b.Booking_Id = ? AND b.User_Id = ?"
        );
        $stmt->execute([$booking_id, $_SESSION['user_id']]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking || strtolower($booking['Reservation_Status']) !== 'pending') {
            header("Location: index.php?action=my_bookings&error=cannot_edit");
            exit();
        }

        // Fetch other bookings for this property (excluding current) to show conflict hints
        $rangeStmt = $this->db->prepare(
            "SELECT Booking_Id, Check_In, Check_Out FROM Booking
             WHERE Property_Id = ? AND Booking_Id != ?
               AND Reservation_Status IN ('Pending', 'Confirmed')
               AND Check_Out >= CURDATE()"
        );
        $rangeStmt->execute([$booking['Property_Id'], $booking_id]);
        $bookedRanges = $rangeStmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/User/edit_booking.php';
    }

    // ── Save updated booking (new dates + payment method) ──
    public function updateBooking() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=my_bookings");
            exit();
        }

        $booking_id      = intval($_POST['booking_id']     ?? 0);
        $check_in        = trim($_POST['check_in']         ?? '');
        $check_out       = trim($_POST['check_out']        ?? '');
        $check_in_time   = trim($_POST['check_in_time']    ?? '14:00');
        $check_out_time  = trim($_POST['check_out_time']   ?? '12:00');
        $payment_method  = trim($_POST['payment_method']   ?? '');

        // Booking must belong to this user and still be Pending
        $stmt = $this->db->prepare(
            "SELECT b.*, p.Property_rate FROM Booking b
             JOIN Property p ON b.Property_Id = p.Property_Id
             WHERE b.Booking_Id = ? AND b.User_Id = ? AND b.Reservation_Status = 'Pending'"
        );
        $stmt->execute([$booking_id, $_SESSION['user_id']]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            header("Location: index.php?action=my_bookings&error=cannot_edit");
            exit();
        }

        if (empty($check_in) || empty($check_out)) {
            header("Location: index.php?action=edit_booking&id={$booking_id}&error=dates");
            exit();
        }

        // Server-side overlap check (excluding this booking)
        $overlapStmt = $this->db->prepare(
            "SELECT COUNT(*) FROM Booking
             WHERE Property_Id = ? AND Booking_Id != ?
               AND Reservation_Status IN ('Pending', 'Confirmed')
               AND Check_In < ? AND Check_Out > ?"
        );
        $overlapStmt->execute([$booking['Property_Id'], $booking_id, $check_out, $check_in]);
        if ($overlapStmt->fetchColumn() > 0) {
            header("Location: index.php?action=edit_booking&id={$booking_id}&error=dates_taken");
            exit();
        }

        // Recalculate amount
        $d1     = new DateTime($check_in);
        $d2     = new DateTime($check_out);
        $nights = $d1->diff($d2)->days ?: 1;
        $total  = $booking['Property_rate'] * $nights;

        // Update booking dates and times
        $this->db->prepare(
            "UPDATE Booking SET Check_In = ?, Check_In_Time = ?, Check_Out = ?, Check_Out_Time = ?
             WHERE Booking_Id = ?"
        )->execute([$check_in, $check_in_time, $check_out, $check_out_time, $booking_id]);

        // Update payment method and recalculated amount
        $this->db->prepare("UPDATE Payment SET Payment_Method = ?, Amount = ? WHERE Payment_Id = ?")
                 ->execute([$payment_method, $total, $booking['Payment_Id']]);

        header("Location: index.php?action=my_bookings&success=updated");
        exit();
    }
}
