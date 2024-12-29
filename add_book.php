<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Include database and author files
require_once 'Database.php';
require_once 'Author.php';
require_once 'Category.php';
require_once 'Book.php'; // Assuming you have a Book class for handling books

// Create database connection
$db = new Database();
$conn = $db->getConnection();

// Create instances of Author and Category classes
$author = new Author($conn);
$category = new Category($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize POST values
    $title = htmlspecialchars($_POST['title']);
    $author_name = htmlspecialchars($_POST['author']);
    $category_name = htmlspecialchars($_POST['category']);
    $quantity = (int) $_POST['quantity'];  // Ensure quantity is an integer

    // Get author_id by author name
    $author_id = $author->getAuthorIdByName($author_name);

    // If the author does not exist, you can create the author or handle the error
    if ($author_id === null) {
        echo "Author not found! ";
        // Optionally, you can add code here to create the author
        // For example: $author->name = $author_name; $author->create();
        exit();
    }

    // Get category_id by category name
    $category_id = $category->getCategoryIdByName($category_name);

    // If the category does not exist, you can create the category or handle the error
    if ($category_id === null) {
        echo "Category not found! ";
        // Optionally, you can add code here to create the category
        // For example: $category->name = $category_name; $category->create();
        exit();
    }

    // Now you can proceed with adding the book using the Book class
    $book = new Book($conn);
    $book->title = $title;
    $book->author_id = $author_id;
    $book->category_id = $category_id;
    $book->quantity = $quantity;

    // Attempt to create the book in the database
    if ($book->create()) {
        echo "Book added successfully!";
    } else {
        echo "Failed to add book! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="manage_books.php">Manage Books</a></li>
            <li><a href="add_category.php">Add Category</a></li>
            <li><a href="add_author.php">Add Author</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>Add a New Book</h1>
        <!-- Add Book Form -->
        <form action="add_book.php" method="POST">
            <label for="title">Book Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="author">Author Name:</label>
            <input type="text" id="author" name="author" required>

            <label for="category">Category Name:</label>
            <input type="text" id="category" name="category" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="1" required>

            <button type="submit">Add Book</button>
        </form>
    </div>

</body>
</html>
