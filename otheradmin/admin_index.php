<?php
session_start();
require '../includes/db.php';

// Authentication check
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
    header('Location: ../index.php');

    exit();
}

// Retrieve reports for the admin
$reports = $pdo->query("SELECT * FROM reports ORDER BY timestamp DESC")->fetchAll(PDO::FETCH_ASSOC);
$reportCount = count($reports);
// Fetch data
$users = $pdo->query("SELECT * FROM officers")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all guest logs 
$allGuests = $pdo->query("SELECT * FROM guest_log ")->fetchAll(PDO::FETCH_ASSOC);
$allGuestCount = count($allGuests);

// Fetch guest logs for today
$todayGuests = $pdo->query("SELECT * FROM guest_log WHERE DATE(created_at) = CURDATE()")->fetchAll(PDO::FETCH_ASSOC);
$todayGuestCount = count($todayGuests);

// Fetch guest logs for yesterday
$yesterdayGuests = $pdo->query("SELECT * FROM guest_log WHERE DATE(created_at) = CURDATE() - INTERVAL 1 DAY")->fetchAll(PDO::FETCH_ASSOC);
$yesterdayGuestCount = count($yesterdayGuests);

// Fetch guest logs for this week
$weekGuests = $pdo->query("SELECT * FROM guest_log WHERE DATE(created_at) BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()")->fetchAll(PDO::FETCH_ASSOC);
$weekGuestCount = count($weekGuests);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/svg+xml" href="../includes/images/OIP.jpg">
    <link id="mainStylesheet" rel="stylesheet" href="admin_index.css">
    <link rel="stylesheet" href="../css/index.css">
    <script src="toggle_visibility_of_guestlogs.js" defer></script>
    <style>

    </style>
    <script>
        function toggleSettings() {
            const mainStylesheet = document.getElementById('mainStylesheet');
            const currentHref = mainStylesheet.getAttribute('href');
            mainStylesheet.setAttribute('href', currentHref === 'admin_index.css' ? 'change_admindashboard_appearance.css' : 'admin_index.css');
        }
    </script>
</head>

<body>
    <header>
        <div class="logo">
            <a href="../index.php">
                <img src="../includes/images/OIP.jpg" alt="logo">
            </a>
            <p style="margin:auto;">Gate Management System of MINT Ethiopia</p>
        </div>
        <nav class="topnav" id="myTopnav">
            <ul class="appbar_menu">
                <li class="manage-guest">
                    <details>
                        <summary>Manage Guest</summary>
                        <a href="../view_full_guest_logs.php">All Guests</a>
                        <a href="delete_guest_log.php">Delete Guest</a>
                    </details>
                </li>
                <li class="manage-department">
                    <details>
                        <summary>Manage Department</summary>
                        <a href="view department.php">View Department</a>
                        <a href="add department.php">Add Department</a>
                    </details>
                </li>
                <li class="about-ratings">
                    <details>
                        <summary>About Ratings</summary>
                        <a href="../display ratings.php">Display Ratings</a>
                        <a href="delete_ratings.php">Remove Ratings</a>
                    </details>
                </li>
                <li class="settings">
                    <details>
                        <summary>Settings</summary>
                        <a href="display_report.php">view report</a>
                        <a href="#" onclick="toggleSettings()">Change Appearance</a>
                        <a href="logout.php">Logout</a>
                    </details>
                </li>
                <li>
                    <div class="profile">
                        <a href="" style="margin:0; background-color:relative;">
                            <img style="border-radius: 50%; display:block; margin:0;" src="../includes/images/OIP.jpg"
                                alt="profile" with="60px" height="60px">
                            <!--<span style="display:block; margin:0;">hello <?php echo $_SESSION['username']; ?></span>-->
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
    <h1>Admin Dashboard</h1>
    <h2> Reports from system users!</h2>
    <div class="guestlists" style="width:fit-content;margin:auto;">
        <div class="countall">
            <h2>Recieved Reports</h2>
            <?php
            if ($reportCount > 0) {
                echo "<center><h3> There Are <b>" . $reportCount . "</b> reports recieved from reception or officers!</h3></center>";
            } else {
                echo "<h3 style = 'color: #FF3C00;'>no reports found</h3>";
            }
            ?>
        </div>
    </div>
    <?php
    if ($allGuestCount > 0) {
        echo "<center><h3 class='all'> There Are <b>" . $allGuestCount . "</b> total guests Arrived to MINT until now!</h3></center>";
    } else {
        echo "<center><h3 style = 'color: #FF3C00;'>no guests found</h3></center>";
    }
    ?>
    <div class="guest_log">
        <div class="guestlists">
            <div>
                <h2>Today's Guests:</h2>
            </div>
            <div>
                <?php
                if ($todayGuestCount > 0) {
                    echo "<p><b>" . $todayGuestCount . "</b> guests wellcame today!</p>";
                } else {
                    echo "<p style = 'color: #FF3C00;'>no guests today</p>";
                }
                ?>
                <br>
                <button id="toggleTodayguest">view today guest logs</button>
            </div>
            <div class="hidden">
                <?php if ($todayGuestCount > 0): ?>
                    <table border="1">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Sex</th>
                            <th>Purpose</th>
                            <th>Actions</th>
                        </tr>
                        <?php foreach ($todayGuests as $guest): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($guest['id']); ?></td>
                                <td><?php echo htmlspecialchars($guest['name']); ?></td>
                                <td><?php echo htmlspecialchars($guest['email']); ?></td>
                                <td><?php echo htmlspecialchars($guest['adress']); ?></td>
                                <td><?php echo htmlspecialchars($guest['sex']); ?></td>
                                <td><?php echo htmlspecialchars($guest['purpose']); ?></td>
                                <td>
                                    <a href="delete_guest_log.php?id=<?php echo $guest['id']; ?>" class="dlete">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No guests for today.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="guestlists">
            <div>
                <h2>Yesterday's Guests:</h2>
            </div>
            <div>
                <?php
                if ($yesterdayGuestCount > 0) {
                    echo "<p><b>" . $yesterdayGuestCount . "</b> guests wellcame yesterday!</p>";
                } else {
                    echo "<p style = 'color: #FF3C00;'>no guests yesterday!</p>";
                }
                ?>
                <br>
                <button id="toggleYesterdayguest">view guest logs</button>
            </div>
            <div class="hidden">
                <?php if ($yesterdayGuestCount > 0): ?>
                    <table border="1">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Sex</th>
                            <th>Purpose</th>
                            <th>Actions</th>
                        </tr>
                        <?php foreach ($yesterdayGuests as $guest): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($guest['id']); ?></td>
                                <td><?php echo htmlspecialchars($guest['name']); ?></td>
                                <td><?php echo htmlspecialchars($guest['email']); ?></td>
                                <td><?php echo htmlspecialchars($guest['adress']); ?></td>
                                <td><?php echo htmlspecialchars($guest['sex']); ?></td>
                                <td><?php echo htmlspecialchars($guest['purpose']); ?></td>
                                <td>
                                    <a href="delete_guest_log.php?id=<?php echo $guest['id']; ?>" class="dlete">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No guests for yesterday.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="guestlists">
            <div>
                <h2>This Week's Guests:</h2>
            </div>
            <div>
                <?php
                if ($weekGuestCount > 0) {
                    echo "<p><b>" . $weekGuestCount . "</b> guests wellcame in this week!</p>";
                } else {
                    echo "<p style = 'color: #FF3C00;'>no guests for the past week!</p>";
                }
                ?>
                <br>
                <button id="toggleWeeklyguest">view guest logs</button>
            </div>
            <div class="hidden">
                <?php if ($weekGuestCount > 0): ?>
                    <table border="1">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Sex</th>
                            <th>Purpose</th>
                            <th>Actions</th>
                        </tr>
                        <?php foreach ($weekGuests as $guest): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($guest['id']); ?></td>
                                <td><?php echo htmlspecialchars($guest['name']); ?></td>
                                <td><?php echo htmlspecialchars($guest['email']); ?></td>
                                <td><?php echo htmlspecialchars($guest['adress']); ?></td>
                                <td><?php echo htmlspecialchars($guest['sex']); ?></td>
                                <td><?php echo htmlspecialchars($guest['purpose']); ?></td>
                                <td>
                                    <a href="delete_guest_log.php?id=<?php echo $guest['id']; ?>" class="dlete">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No guests for this week.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="guestdisply">
    </div>




    <!--<h2>Other Officers</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Access Permission</th>
            <th>Actions</th>
        </tr>
        <?php #foreach ($residents as $resident): ?>
            <tr>
                <td><?php # echo htmlspecialchars($resident['id']); ?></td>
                <td><?php #echo htmlspecialchars($resident['name']); ?></td>
                <td><?php #echo $resident['access_permission'] ? 'Granted' : 'Denied'; ?></td>
                <td>
                    <a href="edit_resident.php?id=<?php #echo $resident['id']; ?>">Edit</a>
                    <a href="delete_resident.php?id=<?php #echo $resident['id']; ?>" class="delete">Delete</a>
                </td>
            </tr>
        <?php #endforeach; ?>
    </table>
    <a href="add_resident.php">Add Other Officer</a>

    <a href="logout.php">Logout</a>
        -->

    <?php include "../includes/footer.php"; ?>
</body>

</html>