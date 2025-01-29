<?php
session_start();
require '../includes/db.php';

// Authentication check
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
    header('Location: ../index.php');
    exit();
}


$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM officers WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['role'];
    $stmt     = $pdo->prepare("UPDATE officers SET username = ?, password = ?, role = ? WHERE id = ?");
    if ($stmt->execute([$username, $password, $role, $id])) {
        header('Location: view department.php');
        exit();
    } else {
        echo "Error updating department.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="icon" type="image/svg+xml" href="../includes/images/OIP.jpg">
    <link rel="stylesheet" href="../css/edit_department.css">
    <link rel="stylesheet" href="../css/goback.css">
</head>
<body>
    <div>
    <form method="post" class = "container" name="myForm" onsubmit="return validateForm()">
        <h1>Ministry Of Innovation And Technology</h1>
        <h3>Edit Department</h3>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="<?php echo htmlspecialchars($user['username']); ?>" value="<?php  if (isset($row) && !is_null($row)) echo htmlspecialchars($user['username']); ?>" required><br>
        <label for="email">Email:</label>
        <input type="email" id="username" name="email" placeholder="<?php echo htmlspecialchars($user['email']); ?>" value="<?php  if (isset($row) && !is_null($row)) echo htmlspecialchars($user['email']); ?>" ><br>
        <label for="username">Password:</label>
        <input type="password" id="password" name="password" placeholder="<?php echo htmlspecialchars($user['password']); ?>" value="<?php  if (isset($row) && !is_null($row)) echo htmlspecialchars($user['password']); ?>" required><br>
        <p id="hidenpass" style="color:red;"></p>
        <label for="role">Role:</label>
        <select id="role" name="role" required placeholder="<?php echo htmlspecialchars($user['role']); ?>">
            <option value="" desabled><?php echo htmlspecialchars($user['role']); ?></option>
            <option value="admin">Admin</option>
            <option value="reception">reception</option>
            <option value="Audit Service Office"> Audit Service Office</option>
            <option value="Institutional Transition Office"> Institutional Transition Office</option>
            <option value="Ethics and Anti-Corruption Office"> Ethics and Anti-Corruption Offic</option>
            <option value="Public relations and Communication Office"> Public relations and Communication Office</option>
            <option value="Public Relations & Communication Team"> Public Relations & Communication Team</option>
            <option value="Women and Social Affairs Office"> Women and Social Affairs Office</option>
            <option value="Innovation & Technology Partnership and Alliance Affairs Office"> Innovation & Technology Partnership and Alliance Affairs Office</option>
            <option value="International Relations & Cooperation Desk"> International Relations & Cooperation Desk</option>
            <option value="Private Sector Industries Technology Desk">  Private Sector Industries Technology Desk</option>
            <option value="Innovation Fund Office"> Innovation Fund Office</option>
            <option value="Strategic Affairs Office"> Strategic Affairs Office</option>
            <option value="Finance & Procurement Office"> Finance & Procurement Office</option>
            <option value="FInance Team"> FInance Team</option>
            <option value="Human Resource Competency & Management Office"> Human Resource Competency & Management Office</option>
            <option value="Information Communication Technology Office"> Information Communication Technology Office</option>
            <option value="Human Resource Administration Team"> Human Resource Administration Team</option>
            <option value="Human Recourse Competency Development & Management Team"> Human Recourse Competency Development & Management Team</option>
            <option value="Records Management Team"> Records Management Team</option>
            <option value="Facilities Management Office"> Facilities Management Office</option>
            <option value="Property Management Team"> Property Management Team</option>
            <option value="Property Treasury Team"> Property Treasury Team</option>
            <option value="General Services Team">  General Services Team</option>
            <option value="Transport Deployment Service Team">  Transport Deployment Service Team</option>
            <option value="Inovatation and Reserch Sector">  Inovatation and Reserch Sector</option>
            <option value="National Research Office">  National Research Office</option>
            <option value="National Research Development Desk">  National Research Development Desk</option>
            <option value="National Research Infrastructure Development Desk"> National Research Infrastructure Development Desk</option>
            <option value="National Research Ethics and Methodology Development Desk"> National Research Ethics and Methodology Development Desk</option>
            <option value="Technology Transformation Office">  Technology Transformation Office</option>
            <option value="Innovation & Information Technology Development & Management Desk">  Innovation & Information Technology Development & Management Desk</option>
            <option value="TechnologIcal Transformation and Collaboration Desk">  TechnologIcal Transformation and Collaboration Desk</option>
            <option value="Indigenous Technology Development Desk">  Indigenous Technology Development Desk</option>
            <option value="Technology Innovation and Management Office">  Technology Innovation and Management Office</option>
            <option value="Innovation Development Desk">  Innovation Development Desk</option>
            <option value="Innovation Infrastructure Development Desk">  Innovation Infrastructure Development Desk</option>
            <option value="Starap & Innovative Enterprise Development Desk 1">  Starap & Innovative Enterprise Development Desk 1</option>
            <option value="Starap & Innovative Enterprise Development Desk 2">  Starap & Innovative Enterprise Development Desk 2</option>
            <option value="ICT and Digital Economy Sector">  ICT and Digital Economy Sector</option>
            <option value="National E-Government Services Office">  National E-Government Services Office</option>
            <option value="National E-Government Services Development & Management Desk">  National E-Government Services Development & Management Desk</option>
            <option value="National E-Government Strategy Coordination Desk">  National E-Government Strategy Coordination Desk</option>
            <option value="National Data Development Coordination Desk">  National Data Development Coordination Desk</option>
            <option value="ICT Infrastructure Development and Management Office">  ICT Infrastructure Development and Management Office</option>
            <option value="National Data Center Management Des">  National Data Center Management Desk</option>
            <option value="Cyber Security Desk">  Cyber Security Desk</option>
            <option value="National ICT Infrastructure Development Desk">  National ICT Infrastructure Development Desk</option>
            <option value="Digital Economy Development Sector Office">  Digital Economy Development Sector Office</option>
            <option value="Digital Economy Development Standards & Control Desk">  Digital Economy Development Standards & Control Desk</option>
            <option value="Digital Industry Development Desk">  Digital Industry Development Desk</option>
            <option value="Digital Society Development Desk">  Digital Society Development Desk</option>  
        </select><br>
        <center style="display:flex; justify-content:space-between; ">
            <button type="submit">Update</button>
            <button type="reset">cancel</button>
        </center>
        <div class = "back">
           <a href="javascript:history.back()">Go back</a>
        </div>
    </form>
    </div>
    <script src="toggle_visibility_of_guestlogs.js" ></script>
</body>
</html>
