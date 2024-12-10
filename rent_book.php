<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$host = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'library_management_system';

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if book ID is provided
if (isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session

    // Check if the book is available
    $check_availability = $conn->prepare("SELECT BookStatus FROM books WHERE BookID = ?");
    if ($check_availability === false) {
        die('Error preparing statement: ' . $conn->error);
    }
    $check_availability->bind_param("i", $book_id);
    $check_availability->execute();
    $result = $check_availability->get_result();
    $book = $result->fetch_assoc();

    if ($book['BookStatus'] == 'available') {
        // Update the book status to 'rented'
        $update_book_status = $conn->prepare("UPDATE books SET BookStatus = 'rented' WHERE BookID = ?");
        $update_book_status->bind_param("i", $book_id);
        $update_book_status->execute();

        // Insert rental record
        $checkout_date = date('Y-m-d');
        $due_date = date('Y-m-d', strtotime('+7 days')); // Example: 7 days rental period
        $insert_rental = $conn->prepare("INSERT INTO rentals (BookID, CheckoutDate, DueDate, UserID) VALUES (?, ?, ?, ?)");
        $insert_rental->bind_param("issi", $book_id, $checkout_date, $due_date, $user_id);
        if($insert_rental->execute()) {
            $_SESSION['message'] = "Book rented successfully!";
        }
        else{
            $_SESSION['error'] = "Insert failed!" . "book id ". $book_id . " checkout " . $checkout_date . " due " .  $due_date . " user id". $user_id;
        }

    } else {
        $_SESSION['error'] = "This book is already rented.";
    }

    header("Location: index.php");
    exit();
}

$conn->close();
?>
