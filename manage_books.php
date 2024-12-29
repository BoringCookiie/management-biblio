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

// Fetch all books
$books = $book->getAllBooks();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="manage_books.php" class="btn">Manage Books</a></li>
            <li><a href="add_category.php">Add Category</a></li>
            <li><a href="add_author.php">Add Author</a></li>
            <li class="logout"><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>Manage Books</h1>

        <table>
            <tr>
                <th>Book Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>

            <?php
            // Loop through the books and display them in the table
            foreach ($books as $book) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($book['title']) . "</td>";
                echo "<td>" . htmlspecialchars($book['author_name']) . "</td>";
                echo "<td>" . htmlspecialchars($book['category_name']) . "</td>";
                echo "<td>" . htmlspecialchars($book['quantity']) . "</td>";
                echo "<td>
                        <a href='edit_book.php?id=" . $book['id'] . "'>Edit</a> | 
                        <a href='delete_book.php?id=" . $book['id'] . "'>Delete</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

</body>
</html>
