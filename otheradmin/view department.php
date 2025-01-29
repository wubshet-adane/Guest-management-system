<?php
session_start();
require '../includes/db.php';

// Authentication check
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
    header('Location: ../index.php');
    exit();
}

// Handle search
$search = '';
$search_query = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $search = htmlspecialchars($search); // Sanitize input
    $search_query = "WHERE id LIKE '%$search%' OR username LIKE '%$search%' OR role LIKE '%$search%'";
}

// Fetch data with optional search
$sql = "SELECT * FROM officers $search_query ORDER by role asc";
$users = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/svg+xml" href="../includes/images/OIP.jpg">
    <link id="mainStylesheet" rel="stylesheet" href="admin_index.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/go_back.css">
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
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }
        .search a:hover {
            color: #3e8e41;
        }
    </style>
</head>
<body>
    <a style="position:absolute;left:10px;top:40px;" href="admin_index.php">Go home</a>
    <center>
        <img src="../includes/images/OIP.jpg" alt="logo" width="20%">
    </center>
    <div class="search">
        <h2 style="text-align:left;">Departments</h2>
        <form action="view department.php" method="POST">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by ID, Username, or Role">
            <button type="submit">Search</button>
        </form>
    </div>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>password</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['password']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <a href="edit_department.php?id=<?php echo urlencode($user['id']); ?>">Edit</a>
                        <a href="delete_department.php?id=<?php echo urlencode($user['id']); ?>" class="dlete">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4"><center>No results found</center></td>
            </tr>
        <?php endif; ?>
    </table>
  
   <?php
       include "../includes/footer.php";
   ?>

</body>
</html>
