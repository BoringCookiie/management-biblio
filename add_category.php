<?php
// Include Database connection and Category class
require_once 'Database.php';
require_once 'Category.php';

// Create a new database connection
$database = new Database();
$db = $database->getConnection();

// Create Category object
$category = new Category($db);
$categories = $category->read(); // Fetch all categories from the database

// Handle form submission to add a new category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['category_name'])) {
        $category->name = $_POST['category_name'];
        if ($category->create()) {
            echo "Category added successfully!";
        } else {
            echo "Failed to add category.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_books.php">Manage Books</a></li>
        <!-- Add Category button -->
        <li class="logout"><a href="add_category.php">Add Category</a></li>
        <!-- Add Author button -->
        <li class="logout"><a href="add_author.php">Add Author</a></li>
        <!-- Logout button should be last -->
        <li class="logout"><a href="logout.php">Logout</a></li>
    </ul>
</div>


    <div class="content">
        <h1>Add Category</h1>

        <!-- Add Category Form -->
        <form action="add_category.php" method="post">
            <label for="category_name">Category Name:</label>
            <input type="text" id="category_name" name="category_name" required>
            <input type="submit" name="submit" value="Add Category">
        </form>

        <h2>Existing Categories</h2>
        <!-- Table to display existing categories -->
        <table>
            <tr>
                <th>Category ID</th>
                <th>Category Name</th>
            </tr>
            <?php
            // Loop through the categories and display them in the table
            foreach ($categories as $cat) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($cat['id']) . "</td>";
                echo "<td>" . htmlspecialchars($cat['name']) . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

</body>
</html>
