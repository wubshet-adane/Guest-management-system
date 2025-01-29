<?php

require "includes/connection.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department = $_POST['department'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO ratings (department, comment, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $department, $comment, $rating);

    // Execute the statement
    if ($stmt->execute()) {
       echo "<p style = 'color:#00FF51;text-align:center;'>Information Successfuly Recorded</p>";
    } 
    else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ratings Form</title>
    <link rel="stylesheet" type="text/css" href="css/store_rating.css">
    <link rel="stylesheet" href="css/goback.css">
</head>
<body>
    <div class="fullform">
        <h1 style="text-align:center; color:#02A79E;">Minstry Of Innovation Technology Ethiopia!</h1>      
        <h2>Rating Form</h2>
        <form method="post" action="store_rating.php">
            <label for="department">Department:</label><br>
            <input type="text" id="department" name="department" required><br><br>
            <label for="comment">Comment:</label><br>
            <textarea id="comment" name="comment" required rows = "4"></textarea><br><br>
            <label for="rating">Rate Us:</label>
            <select name="rating" id = "rating" required>
                <option value="" disabled selected>select rate!</option>
                <option value="Unsatisfactory">Unsatisfactory</option>
                <option value="Below Average">Below Average</option>
                <option value="Best">Satisfactory</option>
                <option value="Very Good">Very Good</option>
                <option value="Excellent">Excellent</option>
            </select><br><br>
            <input type="submit" value="Store">
            <div class = "back">
                <a href="reception_homepage">Go back</a>
            </div>
        </form>
    </div>
</body>
</html>
