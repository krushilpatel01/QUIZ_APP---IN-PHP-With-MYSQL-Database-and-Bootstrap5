<?php
// admin/includes/config.php
// Database connection settings
$host = "localhost";      // XAMPP default
$user = "root";           // XAMPP default MySQL user
$pass = "";               // XAMPP default MySQL password (empty)
$dbname = "quiz_app";     // change this to your actual database name

// Create connection
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Optional: set charset to utf8mb4
mysqli_set_charset($conn, "utf8mb4");
?>
