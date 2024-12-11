<?php
session_start();

$host = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'library_management_system';

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (isset($_GET['book_id'])) {
    $book_id = (int)$_GET['book_id'];

    $sql = $conn->prepare("SELECT * FROM books WHERE BookID = ?");
    $sql->bind_param("i", $book_id);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc(); // Store the book details in the $book variable
    } else {
        die("Book not found.");
    }
} else {
    die("No book selected.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link rel="stylesheet" href="styles/book_details.css">
</head>
<body>
<div class="header">
    <a href="index.php">Back to Books</a>
</div>

<div class="content">
    <?php if (isset($book)): ?>
        <h3><?= htmlspecialchars($book['Title']); ?></h3>
        <p><strong>Author:</strong> <?= htmlspecialchars($book['Author']); ?></p>
        <p><strong>Genre:</strong> <?= htmlspecialchars($book['Genre']); ?></p>
        <p><strong>ISBN:</strong> <?= htmlspecialchars($book['ISBN']); ?></p>
        <p><strong>Publish Date:</strong> <?= htmlspecialchars($book['PublishDate']); ?></p>
        <p><strong>Publisher:</strong> <?= htmlspecialchars($book['Publisher']); ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($book['BookStatus']); ?></p>
        <img src="<?= htmlspecialchars($book['BookCover']); ?>" alt="<?= htmlspecialchars($book['Title']); ?> cover">

        <?php if ($book['BookStatus'] == 'available'): ?>
            <form action="rent_book.php" method="POST">
                <input type="hidden" name="book_id" value="<?= $book['BookID'] ?>" />
                <button type="submit">Rent This Book</button>
            </form>
        <?php else: ?>
            <p>This book is currently unavailable for rent.</p>
        <?php endif; ?>
    <?php else: ?>
        <p>No details available for this book.</p>
    <?php endif; ?>
</div>
</body>
</html>
