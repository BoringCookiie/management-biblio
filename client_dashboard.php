<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'borrower') {
    header('Location: login.php');
    exit();
}

// Include database connection and Book class
require_once 'Database.php';
require_once 'Book.php';

// Create a new database connection
$database = new Database();
$db = $database->getConnection();

// Create Book object
$book = new Book($db);

// Get available books
$available_books = $book->getAvailableBooks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="client_dashboard.css">
</head>
<body>

<div class="sidebar">
    <h2>Client Panel</h2>
    <ul>
        <li><a href="client_dashboard.php">Dashboard</a></li>
        <li><a href="borrow_books.php">Borrow Books</a></li>
        <li><a href="my_borrowed_books.php">My Borrowed Books</a></li>
        <li class="logout"><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="content">
    <h1>Available Books</h1>

    <!-- Table to display available books -->
    <table>
        <tr>
            <th>Book ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
        <?php
        foreach ($available_books as $book) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($book['id']) . "</td>";
            echo "<td>" . htmlspecialchars($book['title']) . "</td>";
            
            // Get the author name
            $author_id = $book['author_id'];
            $author_query = "SELECT name FROM authors WHERE id = ?";
            $author_stmt = $db->prepare($author_query);
            $author_stmt->execute([$author_id]);
            $author = $author_stmt->fetch(PDO::FETCH_ASSOC);
            echo "<td>" . htmlspecialchars($author['name']) . "</td>";
            
            // Get the category name
            $category_id = $book['category_id'];
            $category_query = "SELECT name FROM categories WHERE id = ?";
            $category_stmt = $db->prepare($category_query);
            $category_stmt->execute([$category_id]);
            $category = $category_stmt->fetch(PDO::FETCH_ASSOC);
            echo "<td>" . htmlspecialchars($category['name']) . "</td>";

            echo "<td>" . htmlspecialchars($book['quantity']) . "</td>";
            echo "<td><a href='borrow_book.php?book_id=" . htmlspecialchars($book['id']) . "'>Borrow</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
