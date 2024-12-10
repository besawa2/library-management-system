<?php
session_start();

$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'library_management_system';

$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must log in first to reserve a book.");
}

// Fetch only books that don't have active reservations
$query = "
SELECT b.BookID, b.title
FROM books b
WHERE NOT EXISTS (
    SELECT 1 
    FROM reservations r 
    WHERE r.BookID = b.BookID AND r.ReserveEndDate >= CURDATE()
)";

$books = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reserve a Book</title>
</head>
<body>
    <h2>Reserve a Book</h2>
    <form action="reserve_book_action.php" method="POST">
        <label for="bookSelect">Choose a Book to Reserve:</label>
        <select name="book_id" id="bookSelect" required>
            <?php
            if ($books->num_rows > 0) {
                while ($book = $books->fetch_assoc()) {
                    echo "<option value='{$book['BookID']}'>{$book['title']}</option>";
                }
            } else {
                echo "<option value=''>No books available for reservation</option>";
            }
            ?>
        </select>
        <br><br>
        <!-- Hidden user ID passed automatically from session -->
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
        <input type="submit" value="Reserve Book">
        <button  onclick="window.location.href='index.php'; return false;">Cancel</button> 

    </form>
</body>
</html>

<?php
$conn->close();
?>
