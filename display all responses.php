<?php
session_start();
require 'includes/connection.php';


// Fetch all data from the responses table
$sql = "SELECT id, sendername, response, status, created_at, seen FROM responses";
$result = $conn->query($sql);

// Store responses in an array
$responses = [];
if ($result->num_rows > 0) {
    $responses = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_response'])) {
    $response_id = $_POST['response_id'];
    
    // Perform delete operation in the database
    $delete_sql = "DELETE FROM responses WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $response_id);
    
    if ($stmt->execute()) {
        // Redirect to same page after successful deletion
        header('Location: display_all_responses.php');
        exit();
    } else {
        echo '<p>Error deleting response: ' . $conn->error . '</p>';
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display All Responses</title>
    <link rel="icon" type="image/svg+xml" href="includes/images/OIP.jpg">
    <link rel="stylesheet" href="css/reception.css">
    <link rel="stylesheet" href="css/index.css">
    <style>
        th{
            background-color: #4CAF50;
            color: white;
        }
        table{
            width: 100%;
            border-collapse: collapse;
        }
        th, td{
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {background-color: #f5f5f5;
        }
        td{
            background-color: #f2f2f2;
            color: black;
        }
        .delete-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: darkred;
        }

    </style>
</head>

<body>
    <header>
        <div class="logo"><a href="index.php"><img src="MINTLOGO1.jpg" alt="logo"></a><span>Gate Management System of MINT Ethiopia</span></div>
    </header>

    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><a href="index.php">Homepage:</a></li>
                <li><a href="reception_homepage.php">Back to Reception Dashboard</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <section class="responses">
                <h1>All Responses</h1>
                <table>
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>For Guest ID</th>
                            <th>Response Message</th>
                            <th>Status</th>
                            <th>Time</th>
                            <th>Seen</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (!empty($responses)): 
                            foreach ($responses as $response):
                        ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($response['sendername'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($response['passkey'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($response['response'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($response['status'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($response['created_at'] ?? ''); ?></td>
                                    <td><?php echo $response['seen'] ? 'Yes' : 'No'; ?></td>
                                    <td>
                                    <form action="delete_response.php" method="post">
                                        <input type="hidden" name="response_id" value="<?php echo $response['id']; ?>">
                                        <button type="submit" name="delete_response" class="delete-btn ">Delete</button>
                                    </form>

                                    </td>
                                </tr>
                        <?php 
                            endforeach;
                        else: 
                        ?>
                            <tr>
                                <td colspan="6">No responses found.</td>
                            </tr>
                        <?php 
                        endif; 
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <?php
       include "includes/footer.php";
    ?>

    <script src="scripts.js"></script>
</body>
</html>
