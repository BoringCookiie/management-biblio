<?php
class Book {
    private $conn;
    private $table = "books";  // Assuming the table name is 'books'

    public $id;
    public $title;
    public $author_id;
    public $category_id;
    public $quantity;

    // Constructor to initialize database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to create a new book
    public function create() {
        $query = "INSERT INTO " . $this->table . " (title, author_id, category_id, quantity) VALUES (:title, :author_id, :category_id, :quantity)";
        $stmt = $this->conn->prepare($query);

        // Bind parameters to the prepared statement
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':quantity', $this->quantity);

        // Execute the statement and return success or failure
        return $stmt->execute();
    }

    // Method to get all books
    public function getAllBooks() {
        $query = "SELECT books.id, books.title, authors.name AS author_name, categories.name AS category_name, books.quantity 
                  FROM books 
                  LEFT JOIN authors ON books.author_id = authors.id 
                  LEFT JOIN categories ON books.category_id = categories.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to update a book
    public function update() {
        $query = "UPDATE books SET title = :title, author_id = :author_id, category_id = :category_id, quantity = :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':id', $this->id);

        // Execute the query
        return $stmt->execute();
    }

    // Method to delete a book
    public function delete() {
        $query = "DELETE FROM books WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Bind the book ID parameter
        $stmt->bindParam(':id', $this->id);

        // Execute the query
        return $stmt->execute();
    }

    // Method to get a book by its ID
    public function getBookById() {
        $query = "SELECT id, title, author_id, category_id, quantity FROM books WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Bind the book ID to the query
        $stmt->bindParam(':id', $this->id);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the book details if found
        if ($book) {
            $this->title = $book['title'];
            $this->author_id = $book['author_id'];
            $this->category_id = $book['category_id'];
            $this->quantity = $book['quantity'];
            return true;
        }

        return false; // Return false if no book is found
    }
    public function borrowBook($userId, $bookId) {
        // Check if the book is available (quantity > 0)
        $query = "SELECT quantity FROM " . $this->table . " WHERE id = :book_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':book_id', $bookId);
        $stmt->execute();
        
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($book && $book['quantity'] > 0) {
            // Book is available, reduce quantity by 1
            $updateQuery = "UPDATE " . $this->table . " SET quantity = quantity - 1 WHERE id = :book_id";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(':book_id', $bookId);
            $updateStmt->execute();

            // Record the borrowing in the borrowed_books table
            $borrowQuery = "INSERT INTO borrowed_books (user_id, book_id, borrow_date) VALUES (:user_id, :book_id, NOW())";
            $borrowStmt = $this->conn->prepare($borrowQuery);
            $borrowStmt->bindParam(':user_id', $userId);
            $borrowStmt->bindParam(':book_id', $bookId);
            return $borrowStmt->execute();
        }
        return false; // Book is not available
    }

    // Method to get available books (you may already have this)
    public function getAvailableBooks() {
        $query = "SELECT id, title, author_id, category_id, quantity FROM books WHERE quantity > 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
