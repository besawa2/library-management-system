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

if (isset($_POST['delete_staff_id'])){
    $staff_id_delete=$_POST['delete_staff_id'];
    $delete_sql=$conn->prepare("DELETE FROM staff WHERE StaffID=?");
    $delete_sql->bind_param("i", $staff_id_delete);

    if ($delete_sql->execute()){
        echo "<p>Staff Member Removed</p>";
    } else{
        echo "<p>Error RemovingStaff Member</p>";
    }
    $delete_sql->close();
}

if ($_SERVER["REQUEST_METHOD"]==="POST" && isset($_POST['add_staff'])){
    $name=$_POST['name'];
    $position=$_POST['position'];
    $address=$_POST['address'];
    $salary=$_POST['salary'];

    if(!empty($name) && !empty($position) && !empty($address) && !empty($salary)){
        $sql="INSERT INTO staff (Name, Position, Address, Salary) VALUES (?, ?, ?, ?)";
        $stmt=$conn->prepare($sql);
        $stmt->bind_param("sssd", $name, $position, $address, $salary);

        if ($stmt->execute()){
            $message="Staff member added!";
        } else{
            $message="Error adding staff member.";
        }
        $stmt->close();
    } else{
        $message="Please fill in all fields";
    }
}

$sql="SELECT StaffID, Name, Position, Address, Salary FROM staff";
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
                    <th>Salary</th>
            </thead>
            <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
                <td><?php echo $row['StaffID']; ?></td>
                <td><?php echo htmlspecialchars($row['Name']); ?></td>
                <td><?php echo htmlspecialchars($row['Position']); ?></td>
                <td><?php echo htmlspecialchars($row['Address']); ?></td>
                <td><?php echo htmlspecialchars($row['Salary']); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <h3>Add New Staff Member<h3>
        <form action="staff.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <br><br>
            <label for="position">Position:</label>
            <input type="text" id="position" name="position" required>
            <br><br>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <br><br>
            <label for="salary">Salary:</label>
            <input type="text" id="salary" name="salary" required>
            <br><br>
            <button type="submit" name="add_staff">Add Staff Member</button>
        </form>
        
    <h3>Remove Staff Members<h3>
        <form style="margin-left:750px" action="staff.php" method="POST">
            <select name="delete_staff_id" required>
                <option value="">Select a Staff Member to remove</option>
                <?php $result->data_seek(0);
                while ($row = $result->fetch_assoc()): ?>
                <option value="<?= $row['StaffID']; ?>"><?= htmlspecialchars($row['Name']); ?> (<?= htmlspecialchars($row['Position']); ?>)</option>
            <?php endwhile; ?>
            </select>
            <button type="submit">Remove Staff</button>
        </form>

    <br>
    <button class="home-button" onclick="window.location.href='index.php';">Back to Home</button>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>