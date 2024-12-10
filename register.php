<?php
session_start();

// Database connection
$host = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'library_management_system';

$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    $check_stmt = $conn->prepare("SELECT username FROM user WHERE username = ?");
    if (!$check_stmt) {
        die("Failed to prepare statement: " . $conn->error);
    }
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['message'] = "Username is taken, choose another.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO user (username, password, Name) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Failed to prepare statement: " . $conn->error);
        }
        $stmt->bind_param("sss", $username, $hashed_password, $name);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['message'] = "Error during registration. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
</head>
<body>
  <h2>Registration Page</h2>
  <?php if (isset($_SESSION['message'])): ?>
    <p style="color: red;"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
  <?php endif; ?>
  <form method="POST" action="register.php">
    <label>Full Name: </label><br>
    <input type="text" name="name" required><br><br>
    
    <label>Username: </label><br>
    <input type="text" name="username" required><br><br>
    
    <label>Password: </label><br>
    <input type="password" name="password" required><br><br>
    
    <input type="submit" value="Register">
  </form>
</body>
</html>
