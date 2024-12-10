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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return_book'])) {
    $rental_id = $_POST['rental_id'];
    $BookStatus = $_POST['BookStatus'];
    $return_date = date('Y-m-d');

    $update_query = "
        UPDATE rentals
        SET DateReturned = ?
        WHERE RentalID = ? AND DateReturned IS NULL
    ";

    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $return_date, $rental_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "Book returned successfully!";
    } else {
        $_SESSION['error'] = "Error returning the book.";
    }

    header("Location: rentals.php");
    exit();
}

$stmt->close();
$conn->close();
?>
