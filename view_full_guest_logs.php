<?php
session_start();
require 'includes/connection.php';

// Authentication check
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
    header('Location:index.php');
    exit();
}

// Handle search
$search = '';
$search_query = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $search = mysqli_real_escape_string($conn, $search); // Escape special characters
    $search_query = "WHERE id LIKE '%$search%' OR name LIKE '%$search%' OR sex LIKE '%$search%' OR adress LIKE '%$search%' OR purpose LIKE '%$search%' OR email LIKE '%$search%'";
}

// Retrieve guest logs
$sql = "SELECT * FROM guest_log $search_query ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Guest Logs</title>
    <link rel="icon" type="image/svg+xml" href="includes/images/OIP.jpg">
    <link id="mainStylesheet" rel="stylesheet" href="otheradmin/admin_index.css">
    <link rel="stylesheet" href="css/index.css">
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
        
      

        .enlarged {
            box-shadow: 0 0 10px #8AEE42;
            cursor: pointer;
            transform: scale(6.5);
            border-radius: 5%;
            position: fixed;
            top:50%;
            bottom: 50%;
            left: 50%;
            right: auto;
            transition: transform 1s ease;

        }


    </style>
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.enlarge_image').forEach(function(image) {
                image.addEventListener('click', function() {
                    this.classList.toggle('enlarged');
                });
            });
        });

    </script>
</head>
<body>
    <h2>Full Guest Logs</h2>
    <div class="search">
        <a href="otheradmin/admin_index.php">Go back</a>
        <form action="view_full_guest_logs.php" method="POST">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by Pass Key, Name, Address, Purpose, or Email">
            <button type="submit">Search</button>
        </form>
    </div>
    
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>Pass Key</th><th>Guest photo</th><th>Guest Name</th><th>Sex</th><th>Address</th><th>Purpose</th><th>Guest Email</th><th>Reciever</th><th>Created At</th><th>Action</th></tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
           <tr>
            <td style='color:red;font-family: cursive; font-weight: bolder;'><?php echo htmlspecialchars($row['id']);?></td>
            <td><img class="enlarge_image" src="<?php echo htmlspecialchars($row['photo']); ?>" alt="Guest Photo" width="100"></td>
            <td><?php echo htmlspecialchars($row['name']);?></td>
            <td><?php echo htmlspecialchars($row['sex']);?></td>
            <td><?php echo htmlspecialchars($row['adress']);?></td>
            <td><?php echo htmlspecialchars($row['purpose']);?></td>
            <td><?php echo htmlspecialchars($row['email']);?></td>
            <td><?php echo htmlspecialchars($row['reciever']);?></td>
            <td><?php echo htmlspecialchars($row['created_at']);?></td>
            <td><?php echo "<a href='otheradmin/delete_guest_log.php?id=".urlencode($row['id'])."class='dlete'>Delete</a>"?></td>
            </tr>
       <?php
        }
        echo "</table>";
    } else {
        echo "<center><h3 style='color:red; margin:auto;text-align:center; width:50%;'>Guest lists not found, guests were not arrived or lists are deleted! Please communicate with the receptionist.</h3></center>";
    }
    mysqli_close($conn);
    ?>
</body>
</html>
