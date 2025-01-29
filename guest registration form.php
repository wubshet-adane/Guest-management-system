<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'reception') {
    header('Location: index.php');
    exit();
}
require "includes/connection.php";
// Fetch data from the officers table
$sql = "SELECT * FROM officers";
$result = $conn->query($sql);
// Fetch all results as an associative array
$users = [];
if ($result->num_rows > 0) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
}
$id = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data with checks
    $id =      isset($_POST['id']) ?       $_POST['id'] : '';
    $name =    isset($_POST['name']) ?     $_POST['name'] : '';
    $email =   isset($_POST['email']) ?    $_POST['email'] : '';
    $adress =  isset($_POST['adress']) ?   $_POST['adress'] : '';
    $sex =     isset($_POST['sex']) ?      $_POST['sex'] : '';
    $purpose = isset($_POST['purpose']) ?  $_POST['purpose'] : '';
    $reciever= isset($_POST['reciever']) ? $_POST['reciever'] : '';
    $photo =   isset($_POST['photo']) ?    $_POST['photo'] : '';

    if ($id && $name && $adress && $sex && $purpose && $reciever && $photo){


        // Decode the base64 image
        list($type, $data) = explode(';', $photo);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);
        $photoPath = 'uploads/' . uniqid() . '.png';
        
        // Save the image file
        if (file_put_contents($photoPath, $data)) {
            // Insert data into database
            $stmt = $conn->prepare("INSERT INTO guest_log (id, name, email, adress, sex, purpose, reciever, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $id, $name, $email, $adress, $sex, $purpose, $reciever, $photoPath);
            if ($stmt->execute()) {
                echo "<script>alert('Guest information successfully registered!'); window.location.href='guest registration form.php';</script>";
            } else {
                echo " <p>Error: " . $stmt->error . "</p>";
            }
            // Close connection
            $stmt->close();
        } else {
            echo "<p>Error saving photo.</p>";
        }





        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO guest_log (id, name, email, adress, sex, purpose, reciever, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $id, $name, $email, $adress, $sex, $purpose, $reciever, $target_file);
        if ($stmt->execute()) {
        
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        // Close connection
        $stmt->close();
    } else {
        echo "<p>Please fill in all fields.</p>";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Guest Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="includes/images/OIP.jpg">
    <link rel="stylesheet" type="text/css" href="css/guest registration form.css">
    <link rel="stylesheet" type="text/css" href="css/reception.css">
</head>
<body>
<!--
    <header>
        <div class="logo"><a href="index.php"><img src="includes/images/Oip.jpg" height="30px" alt="logo"></a><span>Gate Management System of MINT Ethiopia</span>
        </div>
    </header>
-->
    <h2> New Guest Registration Form</h2><br>
    <form action="guest registration form.php" method="post" enctype="multipart/form-data">
        <p>
            <a href="reception_homepage.php">Go home</a>
        </p>
        <hr>
       <div class="fullform">
            <div class="partform">
                    <label for="id">Pass Key:<br><span style="color:#AFE1FD;">ሚስጥር ቁጥር</span></label>
                    <input type="text" id="id" name="id" value="<?php echo $id; ?>" readonly>
                    <br>
                    <label for="name">Full Name:<br><span style="color:#AFE1FD;">ሙሉ ስም</span></label>
                    <input type="text" id="name" name="name" required>
                    <br>
                    <label for="email">Email:<br><span style="color:#AFE1FD;">ኢሜል</span></label>
                    <input type="email" id="email" name="email" placeholder="optional">
                    <br>
                    <label for="adress">Address:<br><span style="color:#AFE1FD;">አድራሻ</span></label>
                    <input type="text" id="adress" name="adress" required>
                </div>
                <br>
            <div class="partform">
                <label for="sex">Sex:<br><span style="color:#AFE1FD;">ጾታ</span></label>
                    <select id="sex" name="sex" required>
                        <option value="" disabled selected>Select your sex</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    <br>
                    <label  for="purpose">Purpose:<br><span style="color:#AFE1FD;">የመጡበት አላማ</span></label>
                    <input  type="text"   id="purpose" name="purpose" required>
                    <label  for="photo">Capture Photo:<br><span style="color:#AFE1FD;">ፎቶ አስገባ</span></label>
                    <video  id="video"    width="320" height="240" autoplay style = "border-radius: 10px; display:center;"></video><br>
                    <button type="button" id="snap">Capture</button><br>
                    <canvas id="canvas"   width="320" height="240" style="display:none;"></canvas><br>
                    <input  type="hidden" id="photo" name="photo">
                    <br><br>
                    <select id="reciever" name="reciever" required>
                        <option value="" disabled selected>Select receiver<br><span style="color:#B2E0FA;">ተቀባይ ይምረጡ</span></option>
                        <?php
                            foreach ($users as $user):
                                echo "<option value=\"" . htmlspecialchars($user['role']) . "\">" . htmlspecialchars($user['role']) . "</option>";
                            endforeach;
                        ?>
                    </select><br><br>
                    <input type="submit" value="send" onclick="return confirm('Are you sure you want to send this guest information?');">

            </div>
       </div>

    </form>

    <audio id="capture-sound" src="includes/images/iphone-camera-capture-6448.mp3" preload="auto"></audio> <!-- Add the sound file here -->

    <script>
        // Access the camera and capture the photo
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const snap = document.getElementById('snap');
        const photo = document.getElementById('photo');
        const captureSound = document.getElementById('capture-sound'); // Reference to the audio element
        let stream = null;

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(mediaStream => {
                stream = mediaStream;
                video.srcObject = stream;
            })
            .catch(err => {
                console.error("Error accessing the camera: ", err);
            });

        snap.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, 320, 240);
            const dataURL = canvas.toDataURL('image/png');
            photo.value = dataURL;

            // Play the capture sound
            captureSound.play();

            // Stop the video stream
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
            }

            // Hide the video element
            // Hide the video element and display the canvas
            video.style.display = 'none';
            canvas.style.display = 'block';
        });
    </script>
</body>
</html>
