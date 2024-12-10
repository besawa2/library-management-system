<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'library_management_system';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_book'])) {
    $book_id = $_POST['book_id'];
    $new_title = trim($_POST['new_title']);
    $new_author = trim($_POST['new_author']);
    $new_isbn = trim($_POST['new_isbn']);
    $new_genre = trim($_POST['new_genre']);
    $new_publish_date = trim($_POST['new_publish_date']);
    $new_publisher = trim($_POST['new_publisher']);
    $new_book_cover = trim($_POST['new_book_cover']);

    $current_query = $conn->prepare("SELECT Title, Author, ISBN, Genre, PublishDate, Publisher, BookCover FROM books WHERE BookID = ?");
    $current_query->bind_param("i", $book_id);
    $current_query->execute();
    $current_result = $current_query->get_result();
    $current_book = $current_result->fetch_assoc();
    $current_query->close();

    $title = empty($new_title) ? $current_book['Title'] : $new_title;
    $author = empty($new_author) ? $current_book['Author'] : $new_author;
    $isbn = empty($new_isbn) ? $current_book['ISBN'] : $new_isbn;
    $genre = empty($new_genre) ? $current_book['Genre'] : $new_genre;
    $publish_date = empty($new_publish_date) ? $current_book['PublishDate'] : $new_publish_date;
    $publisher = empty($new_publisher) ? $current_book['Publisher'] : $new_publisher;
    $book_cover = empty($new_book_cover) ? $current_book['BookCover'] : $new_book_cover;

    if (empty($title) || empty($author) || empty($isbn)) {
        $_SESSION['error'] = "Title, Author, and ISBN are required fields for updating.";
    } else {
        $update_query = $conn->prepare("
            UPDATE books 
            SET Title = ?, Author = ?, ISBN = ?, Genre = ?, PublishDate = ?, Publisher = ?, BookCover = ? 
            WHERE BookID = ?
        ");
        $update_query->bind_param("sssssssi", $title, $author, $isbn, $genre, $publish_date, $publisher, $book_cover, $book_id);

        if ($update_query->execute()) {
            $_SESSION['message'] = "Book updated successfully!";
        } else {
            $_SESSION['error'] = "Error updating book: " . $conn->error;
        }

        $update_query->close();
    }

    header("Location: index.php");
    exit();
}

// Fetch books with BookID > 24 for the update dropdown
$fetch_books_query = "SELECT BookID, Title FROM books WHERE BookID > 24";
$books_result = $conn->query($fetch_books_query);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donate or Update Books</title>
</head>
<body>
    <h1>Donate a Book</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['message'])): ?>
        <p style="color: green;"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <form action="donate_books.php" method="POST">
        <label for="title">Book Title (Required):</label>
        <input type="text" name="title" id="title" required>
        <br><br>

        <label for="author">Author (Required):</label>
        <input type="text" name="author" id="author" required>
        <br><br>

        <label for="isbn">ISBN (Required):</label>
        <input type="number" name="isbn" id="isbn" required>
        <br><br>

        <label for="genre">Genre:</label>
        <select name="genre" id="genre">
            <option value="Fiction">Fiction</option>
            <option value="Dystopian">Dystopian</option>
            <option value="Adventure">Adventure</option>
            <option value="Romance">Romance</option>
            <option value="Horror">Horror</option>
        </select>
        <br><br>

        <label for="publish_date">Publish Date:</label>
        <input type="date" name="publish_date" id="publish_date">
        <br><br>

        <label for="publisher">Publisher:</label>
        <input type="text" name="publisher" id="publisher">
        <br><br>

        <label for="book_cover">Book Cover URL:</label>
        <input type="text" name="book_cover" id="book_cover">
        <br><br>

        <button type="submit">Donate Book</button>
        <button type="button" onclick="window.location.href='index.php';">Cancel</button>
    </form>

    <h2>Update a Donated Book</h2>

    <form action="donate_books.php" method="POST">
        <label for="book_id">Select a Book to Update:</label>
        <select name="book_id" id="book_id" required>
            <?php if ($books_result->num_rows > 0): ?>
                <?php while ($book = $books_result->fetch_assoc()): ?>
                    <option value="<?php echo $book['BookID']; ?>">
                        <?php echo $book['Title']; ?>
                    </option>
                <?php endwhile; ?>
            <?php else: ?>
                <option value="">No donated books available</option>
            <?php endif; ?>
        </select>
        <br><br>

        <label for="new_title">New Title (Leave empty to keep current):</label>
        <input type="text" name="new_title" id="new_title">
        <br><br>

        <label for="new_author">New Author (Leave empty to keep current):</label>
        <input type="text" name="new_author" id="new_author">
        <br><br>

        <label for="new_isbn">New ISBN (Leave empty to keep current):</label>
        <input type="number" name="new_isbn" id="new_isbn">
        <br><br>

        <label for="new_genre">New Genre (Leave empty to keep current):</label>
        <select name="new_genre" id="new_genre">
            <option value="">Leave unchanged</option>
            <option value="Fiction">Fiction</option>
            <option value="Dystopian">Dystopian</option>
            <option value="Adventure">Adventure</option>
            <option value="Romance">Romance</option>
            <option value="Horror">Horror</option>
        </select>
        <br><br>

        <label for="new_publish_date">New Publish Date (Leave empty to keep current):</label>
        <input type="date" name="new_publish_date" id="new_publish_date">
        <br><br>

        <label for="new_publisher">New Publisher (Leave empty to keep current):</label>
        <input type="text" name="new_publisher" id="new_publisher">
        <br><br>

        <label for="new_book_cover">New Book Cover URL (Leave empty to keep current):</label>
        <input type="text" name="new_book_cover" id="new_book_cover">
        <br><br>

        <button type="submit" name="update_book">Update Book</button>
        <button type="button" onclick="window.location.href='index.php';">Cancel</button>
    </form>
</body>
</html>
