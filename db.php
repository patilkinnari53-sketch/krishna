<?php
$servername = "localhost";
$username = "root";
$password = ""; // update if needed
$database = "goverdhan_eco_village";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>