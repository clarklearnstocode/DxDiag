<?php
/**
 * Database.php — EstateBook
 *
 * FIXES APPLIED (Linux/Hostinger deployment):
 *
 * 1. TRANSPARENT ERROR PROPAGATION
 *    The original catch block swallowed PDOException and returned null,
 *    causing every subsequent query to throw "Call to a member function
 *    prepare() on null" — a fatal error that kills the process before
 *    any layout HTML is sent, producing a blank HTTP 500.
 *    Fix: re-throw the exception so PHP's error handler can log a proper
 *    stack trace, and add an emergency HTML fallback for the browser.
 *
 * 2. FOREIGN KEY CASE SENSITIVITY (Linux MySQL lower_case_table_names=0)
 *    Hostinger Linux MySQL runs with lower_case_table_names=0, meaning
 *    table names are stored and compared exactly as created by the .sql
 *    import. The imported tables are: user, booking, property, payment.
 *    All REFERENCES clauses must match those exact names:
 *      - REFERENCES Booking(...)  →  REFERENCES booking(...)
 *      - REFERENCES Property(...) →  REFERENCES property(...)
 *      - REFERENCES user(...)     ✓  already correct
 *
 * 3. MIGRATION ISOLATION
 *    Each migration now runs inside its own try/catch so a failure in
 *    one table does not abort the rest of the boot sequence.
 */
class Database {
    // --- Credentials (override with environment variables on Hostinger) ---
    private string $host     = 'localhost';
    private string $db_name  = 'u367097290_estatebook';
    private string $username = 'u367097290_estatebook';
    private string $password = 'Dxdiag2026';          // ← change via env var
    public  ?PDO   $conn     = null;

    public function __construct() {
        // Environment variables take priority — set these in Hostinger's
        // control panel under "PHP Configuration" or a root .env loader.
        $this->host     = getenv('DB_HOST') ?: $this->host;
        $this->db_name  = getenv('DB_NAME') ?: $this->db_name;
        $this->username = getenv('DB_USER') ?: $this->username;
        $this->password = getenv('DB_PASS') ?: $this->password;
    }

    /**
     * Returns an active PDO connection.
     *
     * Throws a RuntimeException (wrapping the original PDOException) so
     * callers and PHP's global error handler both receive a meaningful
     * message instead of a silent null that explodes one frame later.
     *
     * @throws RuntimeException
     */
    public function getConnection(): PDO {
        if ($this->conn !== null) {
            return $this->conn;          // reuse existing connection
        }

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            // Log the full detail server-side (visible in Hostinger error logs)
            error_log('[EstateBook] Database connection FAILED: ' . $e->getMessage());

            // Surface a developer-friendly message in the browser during debug.
            // Comment out the die() block once the connection is confirmed working.
            if (ini_get('display_errors')) {
                die('<pre style="color:red;padding:20px">'
                    . '<strong>DB Connection Error</strong><br>'
                    . htmlspecialchars($e->getMessage())
                    . '</pre>');
            }

            // In production (display_errors=Off) throw so controllers can catch
            // it at a higher level rather than dying on a null-pointer.
            throw new RuntimeException(
                'Database connection failed. Check DB_HOST / DB_NAME / DB_USER / DB_PASS.',
                (int)$e->getCode(),
                $e
            );
        }

        // Run idempotent schema additions after a successful connect
        $this->runMigrations($this->conn);

        return $this->conn;
    }

    /**
     * Idempotent schema migrations — safe to run on every boot.
     *
     * Tables use IF NOT EXISTS so they are only created once.
     * Each statement is isolated so a single failure does not cascade.
     *
     * CRITICAL FIX: REFERENCES clauses now use the exact lowercase table
     * names that match the imported schema (user, booking, property).
     * On Linux MySQL (lower_case_table_names=0) the case must match
     * perfectly or InnoDB throws "errno 150" and the CREATE TABLE fails,
     * which was silently swallowed by the original code, leaving the
     * reviews and notifications tables permanently absent and causing
     * every controller that touches them to 500.
     */
    private function runMigrations(PDO $pdo): void {

        // ── announcements ────────────────────────────────────────────────
        try {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS announcements (
                    id         INT AUTO_INCREMENT PRIMARY KEY,
                    message    TEXT        NOT NULL,
                    created_at TIMESTAMP   DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        } catch (Exception $e) {
            error_log('[EstateBook] Migration announcements: ' . $e->getMessage());
        }

        // ── notifications ────────────────────────────────────────────────
        // FIX: REFERENCES user(...)  ← was already correct; kept as-is.
        try {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS notifications (
                    id         INT AUTO_INCREMENT PRIMARY KEY,
                    user_id    INT         NOT NULL,
                    message    TEXT        NOT NULL,
                    is_read    TINYINT(1)  DEFAULT 0,
                    created_at TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES user(User_Id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        } catch (Exception $e) {
            error_log('[EstateBook] Migration notifications: ' . $e->getMessage());
        }

        // ── reviews ──────────────────────────────────────────────────────
        // FIX #1: REFERENCES Booking(...)  →  REFERENCES booking(...)
        // FIX #2: REFERENCES Property(...) →  REFERENCES property(...)
        // FIX #3: REFERENCES user(...)     ✓  already lowercase — no change
        try {
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS reviews (
                    id               INT AUTO_INCREMENT PRIMARY KEY,
                    booking_id       INT         NOT NULL UNIQUE,
                    user_id          INT         NOT NULL,
                    property_id      INT         NOT NULL,
                    rating           TINYINT     NOT NULL,
                    comment          TEXT        NOT NULL DEFAULT '',
                    cat_cleanliness  TINYINT     DEFAULT 0,
                    cat_comfort      TINYINT     DEFAULT 0,
                    cat_location     TINYINT     DEFAULT 0,
                    cat_value        TINYINT     DEFAULT 0,
                    created_at       TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (booking_id)  REFERENCES booking(Booking_Id)  ON DELETE CASCADE,
                    FOREIGN KEY (user_id)     REFERENCES user(User_Id)         ON DELETE CASCADE,
                    FOREIGN KEY (property_id) REFERENCES property(Property_Id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        } catch (Exception $e) {
            error_log('[EstateBook] Migration reviews: ' . $e->getMessage());
        }

        // ── idempotent column additions (reviews extended schema) ─────────
        $altCols = [
            'cat_cleanliness' => 'TINYINT DEFAULT 0',
            'cat_comfort'     => 'TINYINT DEFAULT 0',
            'cat_location'    => 'TINYINT DEFAULT 0',
            'cat_value'       => 'TINYINT DEFAULT 0',
        ];
        foreach ($altCols as $colName => $colDef) {
            try {
                $pdo->exec("ALTER TABLE reviews ADD COLUMN {$colName} {$colDef}");
            } catch (Exception $e) {
                // Column already exists — silently skip (expected on subsequent boots)
            }
        }

        // ── profile_image + TOTP columns on user table ───────────────────
        // These were added after the original schema dump; ensure they exist.
        $userCols = [
            'profile_image' => "VARCHAR(255) DEFAULT NULL",
            'totp_enabled'  => "TINYINT(1)  DEFAULT 0",
            'totp_secret'   => "VARCHAR(64) DEFAULT NULL",
        ];
        foreach ($userCols as $colName => $colDef) {
            try {
                $pdo->exec("ALTER TABLE user ADD COLUMN {$colName} {$colDef}");
            } catch (Exception $e) {
                // Column already exists — silently skip
            }
        }
    }
}