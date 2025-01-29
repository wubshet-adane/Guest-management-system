<?php
session_start();
require 'includes/connection.php';

// Authentication check
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'reception') {
    header('Location: index.php');
    exit();
}
$itsname = $_SESSION['role'];
// Fetch unseen data from the responses table
    $sql = "SELECT * FROM responses WHERE seen = 0";
    $result = $conn->query($sql);

// Store responses in an array
    $responses = [];
    if ($result->num_rows > 0) {
        $responses = $result->fetch_all(MYSQLI_ASSOC);
    }

 // Update the seen status for displayed responses
    if (!empty($responses)) {
        $conn->query("UPDATE responses SET seen = 1 WHERE seen = 0");
    }

// Fetch all data from the responses table
    $all = "SELECT * FROM responses";
    $val = $conn->query($all);

// Store responses in an array
    $allresponses = [];
    if ($val->num_rows > 0) {
        $allresponses = $val->fetch_all(MYSQLI_ASSOC);
    }

    
// Handle search
$search = '';
$search_query = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $search = mysqli_real_escape_string($conn, $search); // Escape special characters
    $search_query = "WHERE sendername LIKE '%$search%' OR passkey LIKE '%$search%' OR response LIKE '%$search%' OR status LIKE '%$search%' OR created_at LIKE '%$search%'";
}


// Retrieve guest logs
$sql = "SELECT * FROM responses $search_query ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reception homepage</title>
    <link rel="icon" type="image/svg+xml" href="includes/images/OIP.jpg">
    <link rel="stylesheet" id = "changecss" href="css/reception.css">
    <link rel="stylesheet" href="css/index.css">    
    <style>        
          @keyframes example{
            0% {
                visibility: visible;
            }
            20% {
                visibility: hidden;
            }
            40% {
                visibility: visible;
            }
            80%{
                visibility: hidden;
            }
        }
        .new {
            animation-name: example;
            animation-duration: 1s;
            animation-iteration-count: infinite;visibility: hidden;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
    <script>
        function toggleSettings() {
            const mainStylesheet = document.getElementById('changecss');
            const currentHref = mainStylesheet.getAttribute('href');
            mainStylesheet.setAttribute('href', currentHref === 'css/reception.css' ? 'css/change_reception.css' : 'css/reception.css');
        }
    </script>
</head>
<body>
    <header>
        <div class="logo"><a href="index.php"><img src="includes/images/OIP.jpg" alt="logo"></a><span>Gate Management System of MINT Ethiopia</span></div>
        <nav>
            <ul class="appbar_menu">
                <li>
                    <details>
                        <summary>Guest Registration</summary>
                        <a href="guest registration form.php">Register new guest</a>
                    </details>
                </li>
                <li>
                    <details>
                        <summary>Responses</summary>
                        <a href="display all responses.php">All responses</a>
                    </details>
                </li>
                <li>
                    <details>
                        <summary>Report</summary>
                        <a href="send_report.php">send report</a>
                        <a href="sent_reports.php?roles=<?php echo urlencode($itsname); ?>">view sent report</a>
                    </details>
                </li>
                <li>
                    <details>
                        <summary>Settings</summary>
                        <a href="#" onclick="toggleSettings()">Change Appearance</a>
                        <a href="otheradmin/logout.php">Logout</a>
                    </details>
                </li>
                <li>
                    <div style="display:block;">
                        <a href="" style="margin:0; background-color:relative;">
                            <img style="border-radius: 50%; display:block; margin:0;" src="../includes/images/OIP.jpg" alt="profile" with="60px" height="60px">
                            <span style="display:block; margin:0;">hello <?php echo $_SESSION['username']; ?></span>
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <aside class="sidebar">
            <ul>
                <li><button onclick = "window.location.href='index.php'">Home:</button></li><br>
                <li><button id="toggleResponsesBtn">Display All Responses</button></li><br>
                <li><button id="allguest">Registered Guests</button></li><br>
            </ul>
        </aside>
        <main class="main-content">
            <section class="dashboard">
                <h1>Reception Dashboard</h1>
                <h2 class = "cot">Respect for strangers is a sign of maturity!!!</h2>
                <div class="overview">
                    <div id="unseenResponses">
                        <?php 
                            if (!empty($responses)): 
                                $counts=count($responses);
                        ?>
                                <div class="new">
                                    <h2> Hello: <?php echo $_SESSION['role'];?> </h2>
                                    <h3 style = "color:green; font-family:monospace;"><?php echo "<span style='color:white;background-color:green;border-radius:50%;margin:3px;padding:3px;'>".$counts."</span>";?>New <u><strong>un</strong>seen</u> Responses Recieved!</h3>
                                </div>
                            <?php
                                foreach ($responses as $response):
                            ?> 
                            <div class="response">
                                <p><strong>From:          </strong> <?php echo htmlspecialchars($response['sendername'] ?? ''); ?></p>
                                <p><strong>Passkey:       </strong> <?php echo htmlspecialchars($response['passkey'] ?? ''); ?>   </p>
                                <p><strong>Message:       </strong> <?php echo htmlspecialchars($response['response'] ?? ''); ?>  </p>
                                <p><strong>Status :       </strong> <?php echo htmlspecialchars($response['status'] ?? ''); ?>    </p>
                                <p><strong>Recieved Time: </strong> <?php echo htmlspecialchars($response['created_at'] ?? ''); ?></p>
                            </div>
                            <hr>
                        <?php 
                            endforeach;
                            else: 
                        ?>
                            <p style = "color:red;">There are NO new/unseen responses recieved!!!</p>
                        <?php 
                            endif; 
                        ?>
                    </div>
                    <!--when click the button remove the class 'hidden' of bellow div tag and display all responses-->
                    <div id="displayallresponses" class="hidden">
                        <div class="search">
                        <h3>All Responses Sent From Different Officers:</h3>
                            <form action="reception_homepage.php" method="POST">
                                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by PassKey, sendername, message, status, or time">
                                <button type="submit">Search</button>
                            </form>
                        </div>
                        <?php 
                        if (!empty($allresponses)): 
                            foreach ($allresponses as $allresponse):
                        ?>
                            <div class="response">
                                <p><strong>From:          </strong> <?php echo htmlspecialchars($allresponse['sendername'] ?? ''); ?></p>
                                <p><strong>Passkey:       </strong> <?php echo htmlspecialchars($allresponse['passkey'] ?? ''); ?></p>
                                <p><strong>Message:       </strong> <?php echo htmlspecialchars($allresponse['response'] ?? ''); ?></p>
                                <p><strong>Status:        </strong> <?php echo htmlspecialchars($allresponse['status'] ?? ''); ?></p>
                                <p><strong>Recieved Time: </strong> <?php echo htmlspecialchars($allresponse['created_at'] ?? ''); ?></p>
                            </div>
                            <hr>
                        <?php 
                            endforeach;
                        else: 
                        ?>
                            <p>responses are <span style="color:red;">deleted</span> or not recieved.</p>
                        <?php 
                        endif; 
                        ?>
                    </div>
                  
                    <div id = "allguests" class="allguestss">
                        <?php
                            $sql = "SELECT * from guest_log ORDER BY created_at DESC";
                            $result = mysqli_query($conn, $sql);
                            echo "<table border='1'>";
                            echo "<tr>   <th>Pass Key</th>   <th>Guest name</th>    <th>Sex</th>   <th>Address</th>    <th>Purpose</th> <th>Guest email</th> <th>Created at</th> </tr>";
                            while ($row = mysqli_fetch_assoc($result)) {?>
                                <tr>  
                                    <td> <?php echo $row['id'];?></td>
                                    <td> <?php echo $row['name'];?></td>  
                                    <td> <?php echo $row['sex'];?></td>   
                                    <td> <?php echo $row['adress'];?></td>    
                                    <td> <?php echo $row['purpose'];?></td>    
                                    <td> <?php echo $row['email'];?></td>   
                                    <td> <?php echo $row['created_at'];?></td> 
                                </tr>
                            <?php
                            }
                            echo "</table>";
                            mysqli_close($conn);
                        ?>
                    </div>
                </div>
            </section>
            <section class="quick-actions">
                <h2>Quick Actions</h2>
                <button onclick = "location.href='guest registration form.php'">Register New guest</button>
                <button onclick = "location.href='store_rating.php'">Set Rating</button>
                <button onclick = "window.location.href='send_report.php'">Send report:</button><br>
            </section>
        </main>
    </div>
    <?php
       include "includes/footer.php";
    ?>
    <script>
        document.getElementById('toggleResponsesBtn').addEventListener('click', function() {
            var unseenResponses = document.getElementById('unseenResponses');
            var displayallresponses = document.getElementById('displayallresponses');
            if (!unseenResponses.classList.contains('unseen') && displayallresponses.classList.contains('hidden')) {
                unseenResponses.classList.add('unseen');
                displayallresponses.classList.remove('hidden');
                this.textContent = 'Hide Seen Responses';
            } 
            else {
                unseenResponses.classList.remove('unseen');
                displayallresponses.classList.add('hidden');
                this.textContent = 'Display All Responses';
            }
        });
    </script>
    <script>
        document.getElementById('allguest').addEventListener('click', function() {
            var displayallguests = document.getElementById('allguests');
            if (displayallguests.classList.contains('allguestss')) {
                displayallguests.classList.remove('allguestss');
                this.textContent = 'Registered Guests';
            } 
            else {
                displayallguests.classList.add('allguestss');
                this.textContent = 'Hide Registered Guests';
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
            }
    </script>

</body>
</html>
