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

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

$genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';

// SQL Query to get available books with genre filter (if any)
if ($genre_filter) {
    $sql = $conn->prepare("SELECT title, author, BookCover, BookID FROM books WHERE genre = ? AND BookStatus = 'available' LIMIT ?, ?");
    $sql->bind_param("sii", $genre_filter, $offset, $limit);
} else {
    $sql = $conn->prepare("SELECT title, author, BookCover, BookID FROM books WHERE BookStatus = 'available' LIMIT ?, ?");
    $sql->bind_param("ii", $offset, $limit);
}

$sql->execute();
$result = $sql->get_result();

// Fetch the total number of available books for pagination
$total_sql = $conn->prepare("SELECT COUNT(*) AS total FROM books WHERE BookStatus = 'available'" . ($genre_filter ? " AND genre = ?" : ""));
if ($genre_filter) {
    $total_sql->bind_param("s", $genre_filter);
}
$total_sql->execute();
$total_result = $total_sql->get_result();
$total_row = $total_result->fetch_assoc();
$total_books = $total_row['total'];
$total_pages = ceil($total_books / $limit);

// Check for overdue rentals and calculate penalties
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $penalty_message = ''; // Default message

    // Query for overdue rentals
    $penalty_query = $conn->prepare("
        SELECT r.RentalID, b.Title, r.DueDate, DATEDIFF(CURRENT_DATE, r.DueDate) AS overdue_days
        FROM rentals r
        JOIN books b ON r.BookID = b.BookID
        WHERE r.UserID = ? AND r.DateReturned IS NULL AND r.DueDate < CURRENT_DATE
    ");
    $penalty_query->bind_param("i", $user_id);
    $penalty_query->execute();
    $penalty_result = $penalty_query->get_result();

    // If the user has overdue rentals, calculate the penalty
    if ($penalty_result->num_rows > 0) {
        $total_penalty = 0;
        while ($penalty = $penalty_result->fetch_assoc()) {
            $overdue_days = $penalty['overdue_days'];
            $total_penalty += $overdue_days * 2; // $2 per day overdue
        }
        $penalty_message = "You have overdue rentals. You owe $" . $total_penalty . " for late returns.";
    }

    // Store penalty message in session if exists
    if ($penalty_message) {
        $_SESSION['penalty_message'] = $penalty_message;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="styles/index.css">
    <style>
        img {
            max-width: 200px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="header">
    Library Management System
    <?php if (isset($_SESSION['message'])): ?>
        <p style="color: green;"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['penalty_message'])): ?>
        <p style="color: red;"><?php echo $_SESSION['penalty_message']; unset($_SESSION['penalty_message']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['username'])): ?>
        <span style="float:right;">Welcome, <?= htmlspecialchars($_SESSION['username']); ?> | <a href="logout.php">Logout</a></span>
    <?php else: ?>
        <button class="login-button" onclick="window.location.href='login.php'">Login</button>
    <?php endif; ?>
</div>

<div class="main-container">
    <div class="sidebar">
        <form method="GET" action="">
            <select name="genre" id="genre">
                <option value="">All Genres</option>
                <option value="Fiction">Fiction</option>
                <option value="Dystopian">Dystopian</option>
                <option value="Adventure">Adventure</option>
                <option value="Romance">Romance</option>
                <option value="Horror">Horror</option>
            </select>
            <br><br>
            <button type="submit" name="filter" class="sidegrid-button">Filter</button>
            <button class="sidegrid-button" onclick="window.location.href='reserve_book.php'; return false;">Reserve a Book</button> 
            <button class="sidegrid-button" onclick="window.location.href='donate_books.php'; return false;">Donate Books</button>
            <button class="sidegrid-button" onclick="window.location.href='rentals.php'; return false;">Current Rentals</button>
            <button class="sidegrid-button" onclick="window.location.href='events.php'; return false;">Library Events</button>
        </form>
    </div>

    <div class="content">
        <table>
            <tr>
            <?php 
              $counter = 0; 
              while ($row = $result->fetch_assoc()) { 
                  echo "<td>
                          <a href='book_details.php?book_id={$row['BookID']}'>
                              <img src='{$row['BookCover']}' alt='{$row['title']} cover'>
                          </a>
                          <p>{$row['title']}<br><small>{$row['author']}</small></p>
                          <form action='rent_book.php' method='POST'>
                              <input type='hidden' name='book_id' value='{$row['BookID']}' />
                              <button type='submit' name='rentbutton' >Rent This Book</button>
                          </form>
                      </td>";
                  $counter++;
                  if ($counter % 3 == 0 && $counter != $limit) {
                      echo "</tr><tr>";
                  }
              } 
            ?>
            </tr>
        </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>&genre=<?= htmlspecialchars($genre_filter) ?>" class="pagination-button">&laquo; <b>Prev</b></a>
            <?php endif; ?>
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>&genre=<?= htmlspecialchars($genre_filter) ?>" class="pagination-button"><b>Next</b> &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
