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
    $book_id = $_POST['book_id']; 
    $return_date = date('Y-m-d');

    $conn->begin_transaction();

    try {
        $update_rental_query = "
            UPDATE rentals
            SET DateReturned = ?
            WHERE RentalID = ? AND DateReturned IS NULL
        ";
        $stmt = $conn->prepare($update_rental_query);
        $stmt->bind_param("si", $return_date, $rental_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Error updating rental record.");
        }

        $update_book_query = "
            UPDATE books
            SET BookStatus = 'available'
            WHERE BookID = ?
        ";
        $stmt = $conn->prepare($update_book_query);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Error updating book status.");
        }

        $conn->commit();

        $_SESSION['message'] = "Book returned and status updated successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = $e->getMessage();
    }

    header("Location: rentals.php");
    exit();
}

$stmt->close();
$conn->close();
?>
