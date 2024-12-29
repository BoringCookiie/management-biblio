<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'borrower') {
    header('Location: login.php');
    exit();
}

require_once 'Database.php';
require_once 'Book.php';

// Create a new database connection
$database = new Database();
$db = $database->getConnection();

// Get the user ID from session
$userId = $_SESSION['user_id'];

// Check if book_id is passed
if (isset($_GET['book_id'])) {
    $bookId = $_GET['book_id'];

    // Create Book object
    $book = new Book($db);

    // Attempt to borrow the book
    if ($book->borrowBook($userId, $bookId)) {
        echo "Book borrowed successfully!";
    } else {
        echo "Failed to borrow book. The book might be unavailable.";
    }
} else {
    echo "Invalid book ID.";
}
?>
