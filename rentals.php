<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'library_management_system';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$query = "
    SELECT r.RentalID, b.Title, b.Author, b.Genre, r.BookID, r.CheckoutDate, r.DueDate
    FROM rentals r
    JOIN books b ON r.BookID = b.BookID
    WHERE r.UserID = ? AND r.DateReturned IS NULL
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Current Rentals</title>
</head>
<body>
    <h1>Current Book Rentals</h1>

    <?php
    if ($result->num_rows == 0) {
        echo "<p>You have no current rentals.</p>";
    } else {
    ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Checkout Date</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($rental = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($rental['Title']); ?></td>
                        <td><?php echo htmlspecialchars($rental['Author']); ?></td>
                        <td><?php echo htmlspecialchars($rental['Genre']); ?></td>
                        <td><?php echo htmlspecialchars($rental['CheckoutDate']); ?></td>
                        <td><?php echo htmlspecialchars($rental['DueDate']); ?></td>
                        <td>
                            <form action="return_book.php" method="POST">
                                <input type="hidden" name="rental_id" value="<?php echo $rental['RentalID']; ?>">
                                <input type="hidden" name="book_id" value="<?php echo $rental['BookID']; ?>">
                                <button type="submit" name="return_book">Return Book</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php } ?>

    <br>
    <button onclick="window.location.href='index.php';">Back to Home</button>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
