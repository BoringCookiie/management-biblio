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
require_once 'Author.php';
require_once 'Category.php';

// Create database connection
$db = new Database();
$conn = $db->getConnection();

// Create instances of Book, Author, and Category classes
$book = new Book($conn);
$author = new Author($conn);
$category = new Category($conn);

// Check if the book ID is provided
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $book->id = $book_id;  // Set book ID

    // Fetch book details based on the ID
    $book_details = $book->getBookById();

    if ($book_details === null) {
        // If no book found, show an error message
        echo "No book found with that ID.";
        exit();
    }

    // Handle form submission to update the book
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $book->title = $_POST['title'];
        $book->author_id = $_POST['author'];
        $book->category_id = $_POST['category'];
        $book->quantity = $_POST['quantity'];

        // Update book and redirect on success
        if ($book->update()) {
            header('Location: manage_books.php'); // Redirect to manage books page
            exit();
        } else {
            echo "Failed to update book.";
        }
    }
} else {
    echo "Book ID is missing.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
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
        <h1>Edit Book</h1>

        <form action="edit_book.php?id=<?php echo $book_id; ?>" method="POST">
            <label for="title">Book Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book_details['title']); ?>" required>

            <label for="author">Author Name:</label>
            <select id="author" name="author" required>
                <?php
                // Fetch authors for dropdown
                $authors = $author->getAllAuthors();
                foreach ($authors as $auth) {
                    echo "<option value='" . $auth['id'] . "' " . ($auth['id'] == $book_details['author_id'] ? 'selected' : '') . ">" . $auth['name'] . "</option>";
                }
                ?>
            </select>

            <label for="category">Category Name:</label>
            <select id="category" name="category" required>
                <?php
                // Fetch categories for dropdown
                $categories = $category->getAllCategories();
                foreach ($categories as $cat) {
                    echo "<option value='" . $cat['id'] . "' " . ($cat['id'] == $book_details['category_id'] ? 'selected' : '') . ">" . $cat['name'] . "</option>";
                }
                ?>
            </select>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($book_details['quantity']); ?>" required>

            <button type="submit">Update Book</button>
        </form>
    </div>

</body>
</html>
