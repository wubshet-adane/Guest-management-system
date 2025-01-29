<?php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "mint";
   $conn = mysqli_connect("$servername","$username","$password","$dbname");
   if(!$conn){
      echo "database connection error";
      die("connection faild".mysqli_connect_error());
   }
  
?>