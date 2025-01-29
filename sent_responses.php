<?php
session_start();
require 'includes/connection.php';
$roles = $_GET['roles'];
// Fetch all data from the responses table
$sql = "SELECT * FROM responses where sendername = '$roles'";
$result = $conn->query($sql);

// Store responses in an array
$responses = [];
if ($result->num_rows > 0) {
    $responses = $result->fetch_all(MYSQLI_ASSOC);
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
    <link rel="stylesheet" href="otheradmin/admin_index.css">
    <style>
        th {
            background-color: #4CAF50;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        td {
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
    <div class="container">

        <main class="main-content">
            <section class="responses">
                <h1>Here Are All Responses That You Sent!</h1>
                <a href="javascript:history.back()">Go back</a>
                <table>
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>For Guest ID</th>
                            <th>Response Message</th>
                            <th>Status</th>
                            <th>Time</th>
                            <th>Seen</th>
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
                                    <!--
                                    <td>
                                        <form action="sent_responses.php" method="post">
                                            <input type="hidden" name="response_id" value="<?php echo $response['id']; ?>">
                                            <button type="submit" name="delete_response" class="delete-btn ">Delete</button>
                                        </form>
                                    </td>
                                    -->
                                </tr>
                            <?php
                            endforeach;
                        else:
                            ?>
                            <tr>
                                <td colspan="7">No responses found.</td>
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