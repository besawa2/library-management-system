<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

if ($_SESSION['username'] !== 'admin') {
    header("Location: index.php");
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

$sql="SELECT StaffID, Name, Position, Address FROM staff";
$result=$conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff</title>
    <link rel="stylesheet" href="styles/staff.css">
</head>
<body>
    <h1>Staff Members</h1>
    <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Address</th>
            </thead>
            <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
                <td><?php echo $row['StaffID']; ?></td>
                <td><?php echo htmlspecialchars($row['Name']); ?></td>
                <td><?php echo htmlspecialchars($row['Position']); ?></td>
                <td><?php echo htmlspecialchars($row['Address']); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <br>
    <button class="home-button" onclick="window.location.href='index.php';">Back to Home</button>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>