<?php
class User
{
    private PDO $conn;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    /**
     * Login lookup by username or email.
     */
    public function login(string $identity): array|false
    {
        $sql = "SELECT User_Id, Name, Email, Phone, Password, profile_image, totp_enabled, totp_secret
        FROM user
        WHERE Email = :identity1 OR Username = :identity2
        LIMIT 1";
$stmt = $this->conn->prepare($sql);
$stmt->bindValue(':identity1', trim($identity), PDO::PARAM_STR);
$stmt->bindValue(':identity2', trim($identity), PDO::PARAM_STR);
$stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
