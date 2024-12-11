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

$total_penalty=0;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $penalty_message = '';

    $penalty_query = $conn->prepare("
        SELECT r.RentalID, b.Title, r.DueDate, DATEDIFF(CURRENT_DATE, r.DueDate) AS overdue_days
        FROM rentals r
        JOIN books b ON r.BookID = b.BookID
        WHERE r.UserID = ? AND r.DateReturned IS NULL AND r.DueDate < CURRENT_DATE
    ");
    $penalty_query->bind_param("i", $user_id);
    $penalty_query->execute();
    $penalty_result = $penalty_query->get_result();

    if ($penalty_result->num_rows > 0) {
        $total_penalty = 0;
        while ($penalty = $penalty_result->fetch_assoc()) {
            $overdue_days = $penalty['overdue_days'];
            $total_penalty += $overdue_days * 2;
        }
        $penalty_message = "You have overdue rentals.";
    }

    if ($penalty_message) {
        $_SESSION['penalty_message'] = $penalty_message;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
</head>
<body>
    <h1>Notifications</h1>
    <?php if ($total_penalty > 0): ?>
        <p style="color: red;" class="penalty-message">
            You have overdue rentals. You owe a total of $<?php echo number_format($total_penalty, 2); ?> for late returns.
                <button display="inline-block;" margin="left;" onclick="window.location.href='rentals.php';">View Current Rentals</button></p>
    <?php else: ?>
        <p>You have no overdue rentals and no penalties.</p>
    <?php endif; ?>
    <br>
    <button onclick="window.location.href='index.php';">Back to Home</button>
</body>
</html>
