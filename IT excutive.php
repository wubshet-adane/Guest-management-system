<?php
require 'includes/connection.php';

// Authentication check
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit();
}

// Set the name of this department to variable "$itsname"
$itsname = $_SESSION['role'];

// Retrieve unseen guests sent to this department
$sql = "SELECT * FROM guest_log WHERE reciever='$itsname' AND seen = '0' ORDER BY created_at DESC";
$result = $conn->query($sql);

// Store unseen guests in an array
$row = [];
if ($result->num_rows > 0) {
    $row = $result->fetch_all(MYSQLI_ASSOC);
}

// Update the seen status for displayed guests
if (!empty($row)) {
    $conn->query("UPDATE guest_log SET seen = 1 WHERE reciever='$itsname' AND seen = 0");
}

// Retrieve all guests sent to this department
$allguests = "SELECT * FROM guest_log WHERE reciever='$itsname' ORDER BY created_at DESC";
$value = $conn->query($allguests);

// Store all guests in an array
$display_guests = [];
if ($value->num_rows > 0) {
    $display_guests = $value->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="includes/images/OIP.jpg">
    <link rel="stylesheet" id="changecss" href="css/officer_homepage.css">
    <link rel="stylesheet" href="css/index.css">
    <title>IT Executive</title>
    <style>
        .notification_box{
            background-color: #DCD9D997;
            border-radius: 10px;
        }
        @keyframes example{
            0% {
                visibility: visible;
            }
            20% {
                visibility: hidden;
            }
            40% {
                visibility: hidden;
            }
            80%{
                visibility: visible;
            }
        }
        .notification {
            background-color: #FFFFFF;
            box-shadow: 0 0 10px #000000;
            width: fit-content;
            padding: 10px;
            border-radius: 50%;
            color: green;
            font-family: monospace;
            animation-name: example;
            animation-duration: 1s;
            animation-iteration-count: infinite;
        }
        .hidden {
            display: none;
        }
        .enlarged {
            box-shadow: 0 0 10px #000000;
            cursor: pointer;
            justify-content: center;
            align-items: center;
        }
        .enlarge_image {
            width: 100px;
            height: auto;
            cursor: pointer;
            box-shadow: 0 0 10px #000000;
            border-radius: 3%;
            justify-content: center;
            align-items: center;
            transition: transform 0.5s ease;
        }
        .enlarge_image:hover {
            transform: scale(6.5);
        }
        .enlarge_image:active {
            transform: scale(1);
        }
       
    </style>
    <script>
        function toggleSettings() {
            const mainStylesheet = document.getElementById('changecss');
            const currentHref = mainStylesheet.getAttribute('href');
            mainStylesheet.setAttribute('href', currentHref === 'css/officer_homepage.css' ? 'css/change_officers.css' : 'css/officer_homepage.css');
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.enlarge_image').forEach(function(image) {
                image.addEventListener('click', function() {
                    this.classList.toggle('enlarged');
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('toggleResponsesBtn').addEventListener('click', function() {
                var allguests = document.getElementById('allguests');
                if (allguests.classList.contains('hidden')) {
                    allguests.classList.remove('hidden');
                    this.textContent = 'Hide All Guests';
                } else {
                    allguests.classList.add('hidden');
                    this.textContent = 'Display All Guests';
                }
            });
        });
    </script>
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="index.php"><img src="includes/images/OIP.jpg" alt="logo"></a><span>Gate Management System of MINT Ethiopia</span>
        </div>
        <nav class="topnav">
            <ul>
                <li>
                    Report
                    <div class="dropdown">
                        <a href="send_report.php">Send Report</a>
                        <a href="sent_reports.php?roles=<?php echo urlencode($itsname); ?>">View Sent Report</a>
                    </div>
                </li>
                <li>
                    Settings
                    <div class="dropdown">
                        <a href="#" onclick="toggleSettings()">Appearance</a>
                        <a href="otheradmin/logout.php">Logout</a>
                    </div>
                </li>
                <li>
                    <div style="display:block;">
                        <a href="profile.php?username=<?php echo htmlspecialchars($_SESSION['username'] ?? '');?>" style="margin:0; background-color:relative;">
                            <img style="border-radius: 50%; display:block; margin:0;" src="../includes/images/OIP.jpg" alt="profile" with="60px" height="60px">
                            <span style="display:block; margin:0;">hello <?php echo $_SESSION['username']; ?></span>
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <main class="main-content">
            <div class="container">
                <div class="sidebar">
                    <div>
                        <ul>
                            <li><a href="#">Guest Logs</a></li><br>
                            <!--<li><a href="send_response.php">Send Response</a></li><br>-->
                            <li><button id="toggleResponsesBtn">Display All Guests</button></li><br>
                            <li><a href="sent_responses.php?roles=<?php echo urlencode($itsname); ?>">Sent Responses</a></li>
                        </ul>
                    </div>
                </div>
                <section class="dashboard">
                    <h1><?php echo $_SESSION['role']; ?>  Dashboard</h1>
                    <div class="notification_box">
                        <?php if (!empty($row)): ?>
                            <div class="new">
                                <center class="notification">
                                    <h1 >
                                        Notification!!! <span style="color:green; font-family:monospace;">
                                        <?php echo "<span style='color:white; background-color:blue; border-radius:50%; margin:3px; padding:3px;'>".count($row)."</span>"; ?>
                                        New <u><strong>Guests</strong></u> Arrived!
                                    </span></h1>
                                </center>
                                <h2>Hello: <?php echo "<span style='color:#00D203;'>".$_SESSION['role']."</span>"; ?> Officer</h2>
                            </div>
                            <?php foreach ($row as $index => $rr): ?>
                                <div class="arrival">
                                    <p><strong>Pass Key:    </strong> <?php echo htmlspecialchars($rr['id'] ?? ''); ?></p>
                                    <p><strong>Photo:       </strong> <img id="toggleImage<?php echo $index; ?>" class="enlarge_image" src="<?php echo htmlspecialchars($rr['photo']); ?>" alt="Guest Photo"></p>
                                    <p><strong>Guest Name:  </strong> <?php echo htmlspecialchars($rr['name'] ?? ''); ?></p>
                                    <p><strong>Sex:         </strong> <?php echo htmlspecialchars($rr['sex'] ?? ''); ?></p>
                                    <p><strong>Address:     </strong> <?php echo htmlspecialchars($rr['adress'] ?? ''); ?></p>
                                    <p><strong>Purpose:     </strong> <?php echo htmlspecialchars($rr['purpose'] ?? ''); ?></p>
                                    <p><strong>Guest Email: </strong> <?php echo htmlspecialchars($rr['email'] ?? ''); ?></p>
                                    <p><strong>Arrival Time:</strong> <?php echo htmlspecialchars($rr['created_at'] ?? ''); ?></p>
                                    <center><a href="send_response.php?id=<?php echo htmlspecialchars($rr['id'] ?? '');?>" style="color:blue;">Send Response</a></center>
                                </div>
                                <hr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color:red;">There are NO new/unseen Guests arrived!!!</p>
                        <?php endif; ?>
                    </div>
                    <section class="hidden" id="allguests">
                        <?php if (!empty($display_guests)): ?>
                            <div class="new">
                                <center>
                                    <h1><?php echo "<span style='color:white; background-color:blue; border-radius:50%; margin:3px; padding:3px;'>".count($display_guests)."</span>"; ?> Total <u><strong>Guests</strong></u> Arrived Until Now!</h1>
                                    <h2>Hello: <?php echo "<span style='color:green;'>".$_SESSION['role']."</span>"; ?> Officer</h2>
                                </center>
                            </div>
                            <?php foreach ($display_guests as $index => $disp_guest): ?>
                                <div class="arrival">
                                    <p><strong>Pass Key:   </strong> <?php echo htmlspecialchars($disp_guest['id'] ?? ''); ?></p>
                                    <p><strong>Photo:      </strong> <img id="toggleImage<?php echo $index + count($row); ?>" class="enlarge_image" src="<?php echo htmlspecialchars($disp_guest['photo']); ?>" alt="Guest Photo"></p>
                                    <p><strong>Guest Name: </strong> <?php echo htmlspecialchars($disp_guest['name'] ?? ''); ?></p>
                                    <p><strong>Sex:        </strong> <?php echo htmlspecialchars($disp_guest['sex'] ?? ''); ?></p>
                                    <p><strong>Address:    </strong> <?php echo htmlspecialchars($disp_guest['adress'] ?? ''); ?></p>
                                    <p><strong>Purpose:    </strong> <?php echo htmlspecialchars($disp_guest['purpose'] ?? ''); ?></p>
                                    <p><strong>Guest Email: </strong> <?php echo htmlspecialchars($disp_guest['email'] ?? ''); ?></p>
                                    <p><strong>Arrival Time:</strong> <?php echo htmlspecialchars($disp_guest['created_at'] ?? ''); ?></p>
                                    <center><a href="send_response.php?id=<?php echo htmlspecialchars($disp_guest['id'] ?? '');?>" style="color:blue;">Send Response</a></center>
                                </div>
                                <hr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color:red;">----No Guests arrived!!!----</p>
                        <?php endif; ?>
                    </section>
                    <section class="quick-actions">
                        <h2>Quick Actions</h2>
                        <button onclick="location.href='send_response.php'">Send Response To Reception</button>
                    </section>
                </section>
            </div>
        </main>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>
