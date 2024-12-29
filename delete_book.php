<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Include database and book files
require_once 'Database.php';
require_once 'Book.php';

// Create database connection
$db = new Database();
$conn = $db->getConnection();

// Create an instance of Book class
$book = new Book($conn);

// Check if the book ID is provided
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $book->id = $book_id;

    if ($book->delete()) {
        echo "Book deleted successfully!";
        header('Location: manage_books.php'); // Redirect to manage books page
    } else {
        echo "Failed to delete book.";
    }
}

?>
