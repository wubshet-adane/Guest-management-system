<?php
session_start();
require 'includes/connection.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the passkey from the form
    $id =         isset($_POST['id']) ? $_POST['id'] : '';
    $sendername = isset($_POST['sendername']) ? $_POST['sendername'] : '';
    $response =   isset($_POST['response']) ? $_POST['response'] : '';
    $reject =     isset($_POST['reject']) ? $_POST['reject'] : '';
    if ($sendername && $response) {
        $status = "";
        if ($reject) {
            $status = $reject;
        } else {
            echo "Please check status";
            exit();
        } 
        $stmt = $conn->prepare("INSERT INTO responses (sendername, passkey, response, status, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $sendername, $id, $response, $status);
        if ($stmt->execute()) {
            echo "<script>alert('Response sent successfully!'); window.location.href='IT excutive.php';</script>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='position:absolute; top:20px; color:red;'> Please enter a response and select at least one status.</p>";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Response</title>
    <link rel="stylesheet" href="css/send_response.css">
    <link rel="stylesheet" href="css/goback.css">
</head>
<body>
    <div>
        <h2>Send Response To Reception</h2>
        <form action="send_response.php" method="post">
            <input type="hidden" id="passkey" name="id" readonly value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>" placeholder = "<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">
            <input type="hidden" id="sendername" name="sendername" value="<?php echo $_SESSION['role']; ?>">
            <label for="response">Write Message:</label><br>
            <textarea id="response" name="response" rows="4" cols="50" placeholder="Write something here!"></textarea><br><br>
            <h3>Check status</h3>
            <div class="radio_btn">
                <div>
                    <label for="accept">Accepted</label>
                    <input type="radio" id="accept" name="reject" value="accepted" required><br>
                </div>
                <div>
                    <label for="reject">Rejected</label>
                    <input type="radio" id="accept" name="reject" value="rejected" required><br><br>
                </div>
            </div>
            <input type="submit" value="Send Response" onclick="return confirm('Are you sure you want to send this guest information?');">
        </form>
        <div class="back">
            <a href="IT excutive.php">Go back</a>
        </div>
    </div>
</body>
</html>
