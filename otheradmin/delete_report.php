<?php
session_start();
require '../includes/db.php';

// Authentication check
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
    header('Location: ../index.php');
    exit();
}


$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM reports WHERE id = ?");
if ($stmt->execute([$id])) {
    echo "<script>alert(' successfully deleted!');</script>";
    header('Location: display_report.php');
    exit();
} else {
    echo "Error deleting user.";
}
?>
