<!-- Admin Dashboard -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        <li class="logout"><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="content">
    <h1>Welcome to the Admin Dashboard</h1>
    <p>Manage books, categories, and perform other admin tasks.</p>

    <h2>Books Management</h2>
    <a href="add_book.php" class="btn">Add New Book</a>

   

    <!-- Book List Table -->
    <h2>Book List</h2>
    <table class="book-list-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once 'Database.php';
            $db = new Database();
            $conn = $db->getConnection();

            try {
                $stmt = $conn->query("SELECT b.id, b.title, b.quantity, a.name AS author_name, c.name AS category_name
                                      FROM books b
                                      JOIN authors a ON b.author_id = a.id
                                      JOIN categories c ON b.category_id = c.id");
                $books = $stmt->fetchAll();

                foreach ($books as $book) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($book['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($book['author_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($book['category_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($book['quantity']) . "</td>";
                    echo "<td><a href='edit_book.php?id=" . $book['id'] . "' class='btn'>Edit</a>
                              <a href='delete_book.php?id=" . $book['id'] . "' class='btn' onclick='return confirm(\"Are you sure you want to delete this book?\")'>Delete</a></td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='5'>Error fetching books: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
