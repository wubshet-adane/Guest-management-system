<?php
session_start();
require 'includes/connection.php';

// Authentication check
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'reception') {
    header('Location: index.php');
    exit();
}

// Check if response_id is provided via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['response_id'])) {
    $response_id = $_POST['response_id'];
    
    // Perform delete operation in the database
    $delete_sql = "DELETE FROM responses WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $response_id);
    
    if ($stmt->execute()) {
        // Redirect back to display_all_responses.php after successful deletion
        header('Location: display all responses.php');
        exit();
    } else {
        echo '<p>Error deleting response: ' . $conn->error . '</p>';
    }
    
    $stmt->close();
}

$conn->close();
?>
