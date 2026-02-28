<?php
session_start();  // ✅ Add this at the very top (first line)

// Step 1: Connect to the database
$servername = "localhost";
$username = "root";      // default XAMPP username
$password = "";          // default XAMPP password
$dbname = "goverdhan_eco_village";  // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Step 2: Check if the connection worked
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Step 3: Get the form data
$fullName = $_POST['full_name'];
$email = $_POST['email_address'];
$amount = $_POST['donation_amount'];
$payment = $_POST['payment_method'];
$message = $_POST['message'];

// Step 4: Save data into the database
$sql = "INSERT INTO make_a_donation (`Full Name`, `Email address`, `Donation Amount`, `Payment Method`, `Message`)
        VALUES ('$fullName', '$email', '$amount', '$payment', '$message')";

if ($conn->query($sql) === TRUE) {
  $_SESSION['donation_amount'] = $amount;   // ✅ Save donation in session

  echo "<script>alert('🎉 Thank you for your donation!'); window.location.href = 'index.php';</script>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

// Step 5: Close the connection
$conn->close();
?>
