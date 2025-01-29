<?php
session_start();
require 'includes/db.php';

$roles = $_GET['roles'];
// Authentication check
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != $roles) {
    header('Location: index.php');
    exit();
}

// Retrieve sent reports
$search = '';
$reports = [];
$reportCount = 0;

// Check if search is set
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $search = htmlspecialchars($search); // Escape special characters
    $stmt = $pdo->prepare("SELECT * FROM reports WHERE id LIKE :search OR event_type LIKE :search OR description LIKE :search OR sender LIKE :search OR timestamp LIKE :search ORDER BY timestamp DESC");
    $stmt->execute(['search' => "%$search%"]);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $reportCount = count($reports);
} else {
    $stmt = $pdo->query("SELECT * FROM reports where sender = '$roles' ORDER BY timestamp DESC");
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $reportCount = count($reports);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>sent reports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="includes/images/OIP.jpg">
    <link id="mainStylesheet" rel="stylesheet" href="otheradmin/admin_index.css">
    <link rel="stylesheet" href="css/index.css">
    <script src="otheradmin/toggle_visibility_of_guestlogs.js" defer></script>
    <style>
        .search {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search form {
            display: flex;
            align-items: center;
        }

        .search form input {
            padding: 5px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search form button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search form button:hover {
            background-color: #45a049;
        }
        .search a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }   
    </style>
</head>
<body>
    <div>     
        <h2>Sent Reports</h2>    
        <?php 
            if ($reportCount > 0){
                echo "<center><h3>You Had  Sent<b>" .$reportCount."</b> reports To Admin!</h3></center>";
            }
            else{
                echo "<center><h3 style='color: #FF3C00;'>No reports found</h3></center>";
            } 
        ?>
    </div>          
    <div class="search">
        <a href="javascript:history.back()">Go back</a>
        <form action="sent_reports.php" method="POST">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by ID, Event Type, Description, Sender, or Timestamp">
            <button type="submit">Search</button>
        </form>
    </div>
    <div class="">    
        <?php if ($reportCount > 0): ?> 
        <table>
            <tr>
                <th>ID</th>
                <th>Event Type</th>
                <th>Description</th>
                <th>Sent From</th>
                <th>Received Time</th>
            </tr>
            <?php foreach ($reports as $ripo): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ripo["id"]); ?></td>
                    <td><?php echo htmlspecialchars($ripo["event_type"]); ?></td>
                    <td><?php echo htmlspecialchars($ripo["description"]); ?></td>
                    <td><?php echo htmlspecialchars($ripo["sender"]); ?></td>
                    <td><?php echo htmlspecialchars($ripo["timestamp"]); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No reports found</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>    
    <?php include "includes/footer.php"; ?>
</body>
</html>
