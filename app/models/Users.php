<?php
class User {
    private $conn;
    private $table_name = "User";

    // Object properties matching your ERD
    public $User_Id;
    public $Name;
    public $Phone;
    public $Email;
    public $Username;
    public $Password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to create a new user (Registration)
    public function register() {
        // UPDATED: Changed 'full_name' to 'Name' to match your SQL table
        $query = "INSERT INTO " . $this->table_name . " 
                  SET Name=:name, Phone=:phone, Email=:email, Username=:username, Password=:password";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->Phone = htmlspecialchars(strip_tags($this->Phone));
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $this->Username = htmlspecialchars(strip_tags($this->Username));
        
        // Bind values
        $stmt->bindParam(":name", $this->Name);
        $stmt->bindParam(":phone", $this->Phone);
        $stmt->bindParam(":email", $this->Email);
        $stmt->bindParam(":username", $this->Username);
        $stmt->bindParam(":password", $this->Password);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Method for Login
    public function login($identity) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE Username = :id OR Email = :id LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $identity);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}