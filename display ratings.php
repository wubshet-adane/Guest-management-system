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
    $search = htmlspecialchars($search); // Sanitize input
    $search_query = "WHERE id LIKE '%$search%' OR department LIKE '%$search%' OR rating LIKE '%$search%'";
}

// Fetch data from the ratings table with optional search
$sql = "SELECT * FROM ratings $search_query ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ratings</title>
    <link rel="stylesheet" href="css/goback.css">
    <link rel="stylesheet" href="otheradmin/admin_index.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFFFFF51;
            margin: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }

        .text {
            color: #096262;
            text-align: center;
        }

        .text h2 {
            color: #096262;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .text h3 {
            margin-top: 0;
            font-size: 20px;
        }

        .search {
            margin-bottom: 20px;
        }

        .search form {
            display: flex;
            align-items: right;
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
            color: #4CAF50;
            background-color: #FFFFFF;
        }

        .search a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }

        .search a:hover {
            color: #3e8e41;
        }

        .searchdir{
            width: 200px;
            position: absolute;
            top: 0;
            right: 0;
            border-radius: 20px;
        }

        .rating-container {
            width: 80%;
            margin-top: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background-color: #9DD4B500;
            margin-bottom: 15px;
        }

        .progress {
            width: 80%;
            background-color: #e0e0df;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 5px;
            margin-left: 10px;
        }

        .progress-bar {
            height: 20px;
            border-radius: 5px;
        }

        .stars {
            width: 40%;
            border:1px solid red;
            font-size: 20px;
            color: gold !important;;
        }
        h6{
           color:#797878; 
        }
    </style>
</head>
<body>
    <div class="back">
        <a href="otheradmin/admin_index.php">Go home</a>
    </div>
    <center>
        <img src="includes/images/OIP.jpg" alt="logo" width="20%">
        <div class="text">
            <h2>Ministry of Innovation and Technology</h2>
            <h3>--Rating About Officers--</h3>
        </div>
        <div class="searchdir">  
            <h3>Directions for Search: </h3>
            <h6><i>Unsatisfactory = 1 star,<br>  Below Average = 2 star,<br> Best = 3 star,<br>Very Good = 4 star,<br> Excellent = 5 star</i></h6>
        </div>
    </center>
    <div class="search">
        <form action="display ratings.php" method="POST">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by Department or Rating">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            $progress = 0;
            $color = '';
            $stars = '';

            switch ($row['rating']) {
                case 'Excellent':
                    $progress = 100;
                    $color = '#00AD17';
                    $stars = '★★★★★';
                    break;
                case 'Very Good':
                    $progress = 80;
                    $color = '#00E700';
                    $stars = '★★★★';
                    break;
                case 'Best':
                    $progress = 60;
                    $color = '#EEFD15';
                    $stars = '★★★';
                    break;
                case 'Below Average':
                    $progress = 40;
                    $color = '#FF7300';
                    $stars = '★★';
                    break;
                case 'Unsatisfactory':
                    $progress = 20;
                    $color = '#FF0000';
                    $stars = '★';
                    break;
                default:
                    $color = '#000000';
                    break;
            }
            ?>
            <div class="rating-container">
                <div>
                    <strong>To:</strong> &emsp;<span style="color:#0B1A38; text-transform: capitalize; background-color:#BEFAF8C9"><?php echo htmlspecialchars($row["department"]); ?></span>
                </div>
                <div>
                    <strong>Comment:</strong> &emsp;<i style="color:#2B2929;"><?php echo htmlspecialchars($row["comment"]); ?></i>
                </div>
                <br>
                <div>
                    <strong>Rating:</strong> <span class="stars"><?php echo $stars; ?></span>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: <?php echo $progress; ?>%; background-color: <?php echo $color; ?>;"></div>
                </div>
                <div>
                    <strong>Created At:</strong> <?php echo htmlspecialchars($row["created_at"]); ?>
                </div>
                <br>
                <div>
                    <a style="background-color:red;float:right;" href="otheradmin/delete_ratings.php?id=<?php echo urlencode($row['id']); ?>" class="delete">Delete</a>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p style='color:red;'>There are no rating records in the database! Please collect ratings from the guests.</p>";
    }

    // Close connection
    $conn->close();
    ?>
</body>
</html>
