<?php
class Property {
    private $conn;
    private $table_name = "Property";

    // Object properties
    public $Property_Id;
    public $Property_Name;
    public $Property_location;
    public $Property_rate;
    public $Property_capacity;
    public $Property_size;
    public $Property_bathrooms;
    public $Has_pool;
    public $Property_Description;
    public $image_path;
    public $Status;

    // Constructor to inject the database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to fetch all properties for the dashboard grid
    public function readAll() {
        // We use * to ensure we get Property_size, Property_bathrooms, and Has_Pool
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY Property_Id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Method to fetch a single property (for the booking/details page)
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE Property_Id = ? LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE Property_Id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch only 3 featured properties for the landing page (not all)
    public function readFeatured($limit = 3) {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY Property_Id ASC LIMIT " . intval($limit);
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

}