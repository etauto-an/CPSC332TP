<?php
// Database connection details
$host = '127.0.0.1'; 
$dbname = 'test_db'; 
$username = 'test_user'; 
$password = 'test_password'; 

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Connection successful message (optional, can be removed in production)
    // echo "Database connection successful!";
} catch (PDOException $e) {
    // Handle connection errors
    die("Database connection failed: " . $e->getMessage());
}
?>
