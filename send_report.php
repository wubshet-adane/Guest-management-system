<?php
session_start();
include "includes/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_type = $_POST['event_type'];
    $description = $_POST['description'];
    $sender = $_POST['sender'];

    $stmt = $conn->prepare("INSERT INTO reports (event_type, description, sender) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $event_type, $description,  $sender);

    if ($stmt->execute()) {
        echo "<script>window.alert('report send succesfuly')</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/send_report.css">
</head>
<body>
    <div class = "fulldiv">
        <h1>Write Reports that need to discribe what you want to say!</h1>
        <h3> <a href="javascript:history.back()">Go home</a></h3>
        <form method="post" action="send_report.php">
            <input type="text" id="event_type" name="sender" hidden value="<?php echo $_SESSION['role'];?>">
            <label for="event_type">Event Type:</label>
            <input type="text" id="event_type" name="event_type" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows = "6"required  placeholder = "write something about problem"></textarea><br>
            
            <input type="submit" value="Submit Report">
        </form>
    </div>
</body>
</html>
