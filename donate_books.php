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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $genre = trim($_POST['genre']);
    $isbn = trim($_POST['isbn']);
    $publish_date = trim($_POST['publish_date']);
    $publisher = trim($_POST['publisher']);
    $book_cover = trim($_POST['book_cover']);

    if (empty($title) || empty($author) || empty($isbn)) {
        $_SESSION['error'] = "Title, Author, and ISBN are required fields.";
    } else {
        // Default book cover if not provided
        if (empty($book_cover)) {
            $book_cover = 'https://d28hgpri8am2if.cloudfront.net/book_images/onix/cvr9781787550360/classic-book-cover-foiled-journal-9781787550360_hr.jpg';
        }

        $query = $conn->prepare("
            INSERT INTO books (Title, Author, Genre, ISBN, PublishDate, Publisher, BookCover) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $query->bind_param(
            "sssssss",
            $title,
            $author,
            $genre,
            $isbn,
            $publish_date,
            $publisher,
            $book_cover
        );

        if ($query->execute()) {
            $_SESSION['message'] = "Book donated successfully!";
        } else {
            $_SESSION['error'] = "Error donating book: " . $conn->error;
        }

        $query->close();
    }

    header("Location: index.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donate a Book</title>
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
</body>
</html>
