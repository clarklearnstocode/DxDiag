<?php
class BookingController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function handleBooking() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if user is logged in
            if (!isset($_SESSION['user_id'])) {
                header("Location: index.php?action=login");
                exit();
            }

            $userId = $_SESSION['user_id'];
            $propertyId = $_POST['property_id'];
            
            // IMPORTANT: Ensure these match the 'name' attribute in your HTML form tags
            // If your form uses <input name="check_in">, use $_POST['check_in']
            $checkIn = $_POST['Check_In'] ?? $_POST['Check_In'];
            $checkOut = $_POST['Check_Out'] ?? $_POST['Check_Out'];

            // 1. Availability Check
            if (!$this->isAvailable($propertyId, $checkIn, $checkOut)) {
                header("Location: index.php?action=view_property&id=$propertyId&error=occupied");
                exit();
            }

            // 2. Calculate Total Price
            $stmt = $this->db->prepare("SELECT Property_rate FROM Property WHERE Property_Id = ?");
            $stmt->execute([$propertyId]);
            $rate = $stmt->fetchColumn();

            $date1 = new DateTime($checkIn);
            $date2 = new DateTime($checkOut);
            $days = $date1->diff($date2)->days ?: 1; 
            $totalPrice = $days * $rate;

            // 3. Updated INSERT statement 
            // Added Total_Price to the column list and matched the 5 parameters
            $sql = "INSERT INTO Booking (User_Id, Property_Id, Check_In, Check_Out, Reservation_Status, Property_rate) 
                    VALUES (?, ?, ?, ?, 'Pending', ?)";
            
            $stmt = $this->db->prepare($sql);
            
            // Execute with exactly 5 parameters to match the 5 '?' above
            $success = $stmt->execute([
                $userId, 
                $propertyId, 
                $checkIn, 
                $checkOut, 
                $totalPrice
            ]);

            if ($success) {
                header("Location: index.php?action=my_bookings&success=reserved");
            } else {
                echo "Error: Could not save booking.";
            }
            exit();
        }
    }

    private function isAvailable($propertyId, $checkIn, $checkOut) {
        // Updated to use your Reservation_Status column name
        $sql = "SELECT COUNT(*) FROM Booking 
                WHERE Property_Id = ? 
                AND Reservation_Status = 'Confirmed'
                AND (
                    (Check_In < ? AND Check_Out > ?)
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$propertyId, $checkOut, $checkIn]);
        return $stmt->fetchColumn() == 0;
    }
}