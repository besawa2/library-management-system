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

$book = null;
$reviews = [];
$error_message = '';

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
    $genre = $book['Genre'];
    $rand_book_sql = $conn->prepare("SELECT * FROM books WHERE Genre = ? AND BookID != ? ORDER BY RAND() LIMIT 3");
    $rand_book_sql->bind_param("si", $genre, $book_id);
    $rand_book_sql->execute();
    $rand_book_result = $rand_book_sql->get_result();

    $reviews_sql = $conn->prepare("
        SELECT r.ReviewText, r.Rating, r.ReviewDate, u.Username
        FROM reviews r
        JOIN user u ON r.UserID = u.UserID
        WHERE r.BookID = ? ORDER BY r.ReviewDate DESC
    ");

    if ($reviews_sql === false) {
        die("Error preparing query: " . $conn->error);
    }

    $reviews_sql->bind_param("i", $book_id);
    $reviews_sql->execute();
    $reviews_result = $reviews_sql->get_result();

    while ($review = $reviews_result->fetch_assoc()) {
        $reviews[] = $review;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $review_text = htmlspecialchars($_POST['review_text']);
        $rating = (int)$_POST['rating'];

        if (!empty($review_text) && $rating >= 1 && $rating <= 5) {
            $insert_review = $conn->prepare("
                INSERT INTO reviews (BookID, UserID, ReviewText, Rating) 
                VALUES (?, ?, ?, ?)
            ");
            $insert_review->bind_param("iisi", $book_id, $user_id, $review_text, $rating);
            $insert_review->execute();

            header("Location: " . $_SERVER['PHP_SELF'] . "?book_id=" . $book_id);
            exit();
        } else {
            $error_message = "Please provide a valid review and rating (1-5).";
        }
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

        <h4>Other books like this one!</h4>
            <div class="recommendations">
                <?php while ($rand_book = $rand_book_result->fetch_assoc()): ?>
                    <div class="book-suggest">
                        <a style="width:120px; height:auto" href="book_details.php?book_id=<?= $random_book['BookID'] ?>">
                            <img src="<?= htmlspecialchars($rand_book['BookCover']); ?>" alt="<?= htmlspecialchars($rand_book['Title']); ?> cover" width="120">
                        </a>
                        <p><?= htmlspecialchars($rand_book['Title']); ?></p>
                    </div>
                <?php endwhile; ?>
        </div>

        <h4>Reviews</h4>
        <?php if (count($reviews) > 0): ?>
            <?php foreach ($reviews as $review): ?>
                <div style="border:2px solid #4CAF50; margin-bottom:5px">
                    <p><strong><?= htmlspecialchars($review['Username']); ?> (Rating: <?= $review['Rating']; ?>/5)</strong></p>
                    <p><?= htmlspecialchars($review['ReviewText']); ?></p>
                    <p><em>Reviewed on: <?= $review['ReviewDate']; ?></em></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No reviews yet, be the first!</p>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <h4>Submit Your Review</h4>
            <form action="book_details.php?book_id=<?= $book_id; ?>" method="POST">
                <textarea name="review_text" required placeholder="Write your review here..." rows="1" cols="40"></textarea><br>
                <label for="rating" style="padding:10px; margin-left:100px">Rating</label>
                <input type="number" name="rating" min="1" max="5" required><br>
                <button type="submit" style="margin-left:100px">Submit Review</button>
            </form>
            <?php if (isset($error_message)): ?>
                <p style="color: red;"><?= $error_message; ?></p>
            <?php endif; ?>
        <?php else: ?>
            <p>You must be logged in to submit a review.</p>
        <?php endif; ?>
    <?php else: ?>
        <p>No details available for this book.</p>
    <?php endif; ?>
</div>
</body>
</html>
