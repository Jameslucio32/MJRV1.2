<?php
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "rposystem"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get message and user type
$message = $_POST['message'];
$user_type = $_POST['user_type'];

// Insert message into the database
$stmt = $conn->prepare("INSERT INTO messages (user_type, message) VALUES (?, ?)");
$stmt->bind_param("ss", $user_type, $message);
$stmt->execute();

$stmt->close();
$conn->close();
?>