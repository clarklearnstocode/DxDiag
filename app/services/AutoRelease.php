<?php
/**
 * AutoRelease Service
 * ─────────────────────────────────────────────────────────────
 * Called once per page request (from index.php, before routing).
 *
 * What it does:
 *   1. Finds every Confirmed booking whose check-out datetime
 *      has already passed (date + time combined).
 *   2. Marks those bookings as "Completed".
 *   3. Sets the linked Property back to "Available".
 *
 * Why this approach:
 *   XAMPP has no built-in task scheduler, so we piggyback on
 *   PHP's normal request cycle. The SQL is a single indexed
 *   UPDATE that completes in < 1ms on any reasonable dataset,
 *   so it adds no perceptible overhead to page loads.
 *
 * Check-out logic:
 *   The booking is considered "over" when NOW() is past
 *   (Check_Out date + Check_Out_Time). If Check_Out_Time is
 *   NULL we fall back to end-of-day (23:59:59) so the property
 *   is released no later than midnight of the check-out day.
 */
class AutoRelease
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function run()
    {
        if (!$this->db) return;

        try {
            // ── Find expired Confirmed bookings ──
            // TIMESTAMP(date, time) combines into a full datetime for comparison.
            // COALESCE falls back to '23:59:59' when Check_Out_Time is NULL.
            $stmt = $this->db->query("
                SELECT b.Booking_Id, b.Property_Id
                FROM   Booking b
                WHERE  b.Reservation_Status = 'Confirmed'
                  AND  TIMESTAMP(
                           b.Check_Out,
                           COALESCE(b.Check_Out_Time, '23:59:59')
                       ) < NOW()
            ");
            $expired = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($expired)) return;

            $bookingIds  = array_column($expired, 'Booking_Id');
            $propertyIds = array_unique(array_column($expired, 'Property_Id'));

            // ── Mark bookings as Completed ──
            $ph = implode(',', array_fill(0, count($bookingIds), '?'));
            $this->db->prepare(
                "UPDATE Booking SET Reservation_Status = 'Completed'
                 WHERE  Booking_Id IN ($ph)"
            )->execute($bookingIds);

            // ── Release each property only if no other booking is still active ──
            foreach ($propertyIds as $propId) {
                $check = $this->db->prepare("
                    SELECT COUNT(*) FROM Booking
                    WHERE  Property_Id = ?
                      AND  Reservation_Status = 'Confirmed'
                      AND  TIMESTAMP(
                               Check_Out,
                               COALESCE(Check_Out_Time, '23:59:59')
                           ) >= NOW()
                ");
                $check->execute([$propId]);

                if ($check->fetchColumn() == 0) {
                    $this->db->prepare(
                        "UPDATE Property SET Status = 'Available' WHERE Property_Id = ?"
                    )->execute([$propId]);
                }
            }

        } catch (Exception $e) {
            // Silent fail — never crash the page over a background task
            // error_log('AutoRelease: ' . $e->getMessage());
        }
    }
}
