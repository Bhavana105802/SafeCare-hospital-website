<?php
// Database configuration
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "safecare_hospital";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
if (!$conn->set_charset("utf8mb4")) {
    die("Error loading character set utf8mb4: " . $conn->error);
}

echo "Database connection successful!";
?>