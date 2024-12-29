<?php
class Author {
    private $conn;
    private $table = "authors";

    public $id;
    public $name;

    // Constructor to initialize the database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch all authors from the database
    public function read() {
        $query = "SELECT id, name FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new author
    public function create() {
        $query = "INSERT INTO " . $this->table . " (name) VALUES (:name)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get the author ID by their name
    public function getAuthorIdByName($name) {
        // Query to fetch the author ID based on the name
        $query = "SELECT id FROM " . $this->table . " WHERE name = :name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);

        // Execute the query
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the author exists and return the ID
        if ($result) {
            return $result['id'];
        } else {
            return null; // Author not found
        }
    }
}
?>

