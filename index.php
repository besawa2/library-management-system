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

// Handle genre filter request
$genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';

// Dynamically query the database based on the selected genre
if ($genre_filter) {
    $sql = $conn->prepare("SELECT title, author FROM books WHERE genre = ? LIMIT 9");
    $sql->bind_param("s", $genre_filter);
} else {
    $sql = $conn->prepare("SELECT title, author FROM books LIMIT 9");
}
$sql->execute();
$result = $sql->get_result();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="styles/index.css">
</head>
<body>
  <div class="header">
    Library Management System

    <?php if (isset($_SESSION['message'])): ?>
    <p style="color: green;"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
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
      </form>
    </div>

    <!-- Main content displaying dynamic books table -->
    <div class="content">
      <table>
        <tr>
          <?php 
          $counter = 0; 
          while ($row = $result->fetch_assoc()) { 
            echo "<td>{$row['title']}<br><small>{$row['author']}</small></td>";
            $counter++;
            if ($counter % 3 == 0 && $counter != 9) {
              echo "</tr><tr>";
            }
          } 
          ?>
        </tr>
      </table>
    </div>
  </div>
</body>
</html>
