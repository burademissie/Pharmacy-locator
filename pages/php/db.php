<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "medfinder";

$conn = new mysqli($servername,$username,$password,$database);

if($conn->connect_error){
    die("❌ Connection failed: " . $conn->connect_error);

}
else{
    header("Location: ../html/landing.html");
    // echo "connected";
}
?>