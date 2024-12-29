<?php
class Category {
    private $conn;
    private $table = "categories";
    public $id;
    public $name;

    // Constructor expects a 'name' argument
    public function __construct($name = null) {
        $this->conn = (new Database())->getConnection();  // Use the Database class to get the connection
        if ($name) {
            $this->name = $name;
        }
    }

    // Method to fetch all categories from the database
    public function read() {
        $query = "SELECT id, name FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to create a new category
    public function create() {
        $query = "INSERT INTO " . $this->table . " (name) VALUES (:name)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Method to get the category ID by name
    public function getCategoryIdByName($name) {
        // Query to fetch the category ID based on the name
        $query = "SELECT id FROM " . $this->table . " WHERE name = :name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);

        // Execute the query
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the category exists and return the ID
        if ($result) {
            return $result['id'];
        } else {
            return null; // Category not found
        }
    }
}
?>
