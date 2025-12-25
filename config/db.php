<?php
// db.php - Database connection using Render environment variables

$host = getenv('DB_HOST');   // from Render environment variables
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');   // can be empty
$db   = getenv('DB_NAME');

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
