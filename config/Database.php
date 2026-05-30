<?php
class Database {
    // Database credentials (override via env vars)
    private $host = "localhost";
    private $db_name = "estatebook_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function __construct() {
        $this->host     = getenv('DB_HOST') ?: $this->host;
        $this->db_name  = getenv('DB_NAME') ?: $this->db_name;
        $this->username = getenv('DB_USER') ?: $this->username;
        $this->password = getenv('DB_PASS') ?: $this->password;
    }

    // Get the database connection
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->runMigrations($this->conn);
        } catch(PDOException $exception) {
            error_log("Database connection error: " . $exception->getMessage());
        }

        return $this->conn;
    }

    /**
     * Idempotent schema migrations — safe to run on every boot.
     * Tables use IF NOT EXISTS so they are only created once.
     */
    private function runMigrations(PDO $pdo): void {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS announcements (
                id         INT AUTO_INCREMENT PRIMARY KEY,
                message    TEXT        NOT NULL,
                created_at TIMESTAMP   DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

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
                FOREIGN KEY (booking_id)  REFERENCES Booking(Booking_Id)   ON DELETE CASCADE,
                FOREIGN KEY (user_id)     REFERENCES user(User_Id)          ON DELETE CASCADE,
                FOREIGN KEY (property_id) REFERENCES Property(Property_Id)  ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // Idempotent: add extended columns if table was created with the minimal spec schema
        $altCols = [
            "cat_cleanliness TINYINT DEFAULT 0",
            "cat_comfort     TINYINT DEFAULT 0",
            "cat_location    TINYINT DEFAULT 0",
            "cat_value       TINYINT DEFAULT 0",
        ];
        foreach ($altCols as $colDef) {
            $colName = trim(explode(' ', $colDef)[0]);
            try {
                $pdo->exec("ALTER TABLE reviews ADD COLUMN $colDef");
            } catch (Exception $e) {
                // Column already exists — silently skip
            }
        }
    }
}
?>