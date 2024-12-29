<?php
class Database {
    private $host = "localhost";
    private $db_name = "gestion_biblio";  // Change this to your actual database name
    private $username = "root";  // Update username if necessary
    private $password = "";  // Update password if necessary
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Create a PDO connection
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
