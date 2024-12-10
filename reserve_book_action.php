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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "You must log in first.";
        header("Location: register.php");
        exit();
    }

    $user_id = $_SESSION['user_id']; 
    $book_id = $_POST['book_id'];

    $check_query = $conn->prepare("
        SELECT * 
        FROM reservations 
        WHERE BookID = ? AND ReserveEndDate >= CURDATE()
    ");
    $check_query->bind_param("i", $book_id);
    $check_query->execute();
    $check_result = $check_query->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = "Sorry, this book is already reserved.";
    } else {
        $reserve_date = date('Y-m-d', strtotime('+7 days')); // Default to 7-day reservation
        $insert_query = $conn->prepare("
            INSERT INTO reservations (BookID, UserID, ReserveEndDate) 
            VALUES (?, ?, ?)
        ");
        $insert_query->bind_param("iis", $book_id, $user_id, $reserve_date);
        if ($insert_query->execute()) {
            $_SESSION['message'] = "Book reserved successfully until $reserve_date.";
        } else {
            $_SESSION['error'] = "Could not reserve the book. Please try again." . $book_id . $user_id . $reserve_date;
        }
    }

    $check_query->close();
    $insert_query->close();
    $conn->close();

    header("Location: index.php");
    exit();
}
?>
