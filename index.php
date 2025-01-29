<?php
session_start();
require 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href="includes/images/OIP.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Management System</title>
    <link rel="stylesheet" href="css/index.css">
    <style>
        body{
        }
        .dropdown>.dropdownlink {
            color: red;
            display: block;
            margin: 4px;
            padding: 6px;
        }

        .dropdown>.dropdownlink>a {
            text-decoration: none;
            color: rgb(255, 255, 255);
            font-size: 1.2rem;
            font-family: Arial, Helvetica, sans-serif;
            text-transform: capitalize;
            background-color: rgb(78, 76, 76);
            padding: 5px;
            border-radius: 7px;
        }

        .dropdown>.dropdownlink>a:hover {
            color: rgb(255, 255, 255);
            background-color: rgb(35, 32, 32);
        }

        form {
            margin: auto;
            background: #C0C0C04D);
            padding: 2em;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.8);
            max-width: 400px;
            width: 100%;
        }

        h1 {
            text-align: center;
            margin-bottom: 1rem;
            font-size: 30px;
            color: #5A7F9D;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #000000;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 15px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 15px;
        }

        button[type="submit"] {
            border: 2px solid #00AAFF;
            margin-top: 1rem;
            width: 100%;
            padding: 6px;
            background-color: #00AAFF00;
            border-radius: 15px;
            color: #00AAFF;
            font-size: 26px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #00AAFF;
            color: #fff;
        }

        h3 {
            text-align: center;
            color: red;
        }
    </style>
</head>

<body>
    <div>
        <header >
            <div class="logo" style="height:70px;" >
                <a href="" style="margin:auto;"><img src="includes/images/OIP.jpg" height="fit-content" width="fit-content" alt="logo"></a>
                <p style="margin:auto;padding-left: 20px;">Guest Management System of Ministry Of Innovation and Technology Ethiopia</p>
            </div>
        </header>
        <div class="container">
            <main class="main-content">
                <div class="container">
                    <h1>Guest Management System</h1>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $username = $_POST['username'];
                        $password = $_POST['password'];

                        $stmt = $pdo->prepare("SELECT * FROM officers WHERE username = ?");
                        $stmt->execute([$username]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($user && ($password == $user['password']) && $username == $user['username']) {
                            $_SESSION['logged_in'] = true;
                            $_SESSION['username'] = $user['username'];
                            $_SESSION['role'] = $user['role'];

                            if ($user['role'] == 'admin') {
                                header('Location: otheradmin/admin_index.php');
                                exit();
                            } 
                            elseif ($user['role'] == 'reception') {
                                header('Location: reception_homepage.php');
                                exit();
                            }
                            else{
                                header('Location: IT excutive.php');
                                exit(); 
                            }
                        }
                        else {
                            $error = "Invalid username or password.";
                        }
                    }
                    ?>
                    <?php if (isset($error)): ?>
                        <h3><?php echo htmlspecialchars($error); ?></h3>
                    <?php endif; ?>
                    <form method="post">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required placeholder="Enter username"><br>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required placeholder="Enter password"><br>
                        <button type="submit">Login</button>
                    </form>
                </div>
                <section></section>
            </main>
        </div>

        <?php
        include "includes/footer.php";
        ?>
    </div>
</body>
</html>
