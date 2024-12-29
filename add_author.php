<?php
// Include Database connection and Author class
require_once 'Database.php';
require_once 'Author.php';

// Create a new database connection
$database = new Database();
$db = $database->getConnection();

// Create Author object
$author = new Author($db);
$authors = $author->read(); // Fetch all authors from the database

// Handle form submission to add a new author
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['author_name'])) {
        $author->name = $_POST['author_name'];
        if ($author->create()) {
            echo "Author added successfully!";
        } else {
            echo "Failed to add author.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Author</title>
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
    <h1>Add Author</h1>

    <!-- Add Author Form -->
    <form action="add_author.php" method="post">
        <label for="author_name">Author Name:</label>
        <input type="text" id="author_name" name="author_name" required>
        <input type="submit" name="submit" value="Add Author">
    </form>

    <h2>Existing Authors</h2>
    <!-- Table to display existing authors -->
    <table>
        <tr>
            <th>Author ID</th>
            <th>Author Name</th>
        </tr>
        <?php
        // Loop through the authors and display them in the table
        foreach ($authors as $auth) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($auth['id']) . "</td>";
            echo "<td>" . htmlspecialchars($auth['name']) . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
