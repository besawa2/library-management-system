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

$sql="SELECT EventID, StartDate, EndDate, Location, Description FROM events ORDER BY StartDate ASC";
$result=$conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Events</title>
</head>
<body>
    <h1>Upcoming Library Events</h1>
    <?php
    if ($result->num_rows==0){
        echo "<p>No upcoming events at the moment.</p>";
    }
    else{ ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Event Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Location</th>
                    <th>Actions</th>
            </thead>
            <tbody>
                <?php while ($event=$result->fetch_assoc()):?>
                    <td><?php echo htmlspecialchars($event["Description"]); ?></td>
                    <td><?php echo htmlspecialchars(date("F j, Y", strtotime($event["StartDate"]))); ?></td>
                    <td><?php echo htmlspecialchars(date("F j, Y", strtotime($event["EndDate"]))); ?></td>
                    <td><?php echo htmlspecialchars($event["Location"]); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php }; ?>
    <br>
    <button onclick="window.location.href='index.php';">Back to Home</button>
</body>
</html>

<?php
$conn->close();
?>