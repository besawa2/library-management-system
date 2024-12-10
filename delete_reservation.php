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

if (!isset($_SESSION['user_id'])) {
    die("You must log in first.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['reservation_id'];

    $verify_query = $conn->prepare("
        SELECT ReserveID 
        FROM reservations 
        WHERE ReserveID = ? AND UserID = ?
    ");
    if (!$verify_query) {
        die("Prepare failed: " . $conn->error);
    }
    $verify_query->bind_param("ii", $reservation_id, $_SESSION['user_id']);
    $verify_query->execute();
    $verify_result = $verify_query->get_result();

    if ($verify_result->num_rows > 0) {
        $delete_query = $conn->prepare("
            DELETE FROM reservations 
            WHERE ReserveID = ?
        ");
        
        $delete_query->bind_param("i", $reservation_id);

        if ($delete_query->execute()) {
            $_SESSION['message'] = "Reservation canceled successfully.";
        } else {
            $_SESSION['error'] = "Failed to cancel the reservation. Please try again.";
        }
    } else {
        $_SESSION['error'] = "Invalid reservation or you don't have permission to cancel it.";
    }

    $verify_query->close();
    $delete_query->close();
    $conn->close();

    header("Location: reserve_book.php");
    exit();
}
?>
