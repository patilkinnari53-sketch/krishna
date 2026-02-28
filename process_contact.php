<?php
// process_contact.php

// Enable error reporting for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database connection settings
$servername = "localhost";
$username = "root";      // default XAMPP username
$password = "";          // default XAMPP password
$dbname = "goverdhan_eco_village";  // your database name

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');

    // Get form data (with checks)
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Check if required fields are empty
    if (empty($name) || empty($email) || empty($message)) {
        header("Location: contact.php?error=1");
        exit();
    }

    // Prepare and bind (using backticks for table/column names with spaces/special chars)
    $stmt = $conn->prepare("INSERT INTO `contact us` (`Name`, `E-mail`, `Your message`) VALUES (?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute
    if ($stmt->execute()) {
        header("Location: contact.php?success=1");
        exit();
    } else {
        header("Location: contact.php?error=1");
        exit();
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    // For debugging, you can show the error message.
    // In production, you may want to log the error and show a generic message.
    echo "Error: " . $e->getMessage();
    exit();
}
?>
