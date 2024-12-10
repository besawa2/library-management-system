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
    die("You must log in first to view or reserve books.");
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch the user's reservations
$reservations_query = "
SELECT r.ReserveID, b.title, r.ReserveEndDate
FROM reservations r
JOIN books b ON r.BookID = b.BookID
WHERE r.UserID = ? AND r.ReserveEndDate >= CURDATE()
";
$reservations_stmt = $conn->prepare($reservations_query);
if (!$reservations_stmt) {
    die("Prepare failed: " . $conn->error);
}
$reservations_stmt->bind_param("i", $user_id);

$reservations_stmt->execute();
$reservations_result = $reservations_stmt->get_result();

// Fetch only books that don't have active reservations
$books_query = "
SELECT b.BookID, b.title
FROM books b
WHERE NOT EXISTS (
    SELECT 1 
    FROM reservations r 
    WHERE r.BookID = b.BookID AND r.ReserveEndDate >= CURDATE()
)";
$books = $conn->query($books_query);
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
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
        <input type="submit" value="Reserve Book">
        <button onclick="window.location.href='index.php'; return false;">Cancel</button> 
    </form>

    <h2>Your Reservations</h2>
    <table border="1">
        <tr>
            <th>Book Title</th>
            <th>Reserved Until</th>
            <th>Action</th>
        </tr>
        <?php
        if ($reservations_result->num_rows > 0) {
            while ($reservation = $reservations_result->fetch_assoc()) {
                echo "<tr>
                        <td>{$reservation['title']}</td>
                        <td>{$reservation['ReserveEndDate']}</td>
                        <td>
                            <form action='delete_reservation.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='reservation_id' value='{$reservation['ReserveID']}'>
                                <input type='submit' value='Cancel Reservation'>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No active reservations</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$reservations_stmt->close();
$conn->close();
?>
