<?php
// Database connection details
$host = '127.0.0.1';           // The hostname or IP address of the database server
$dbname = 'test_db';           // Name of the database to connect to
$username = 'test_user';       // Database username
$password = 'test_password';   // Database password

try {
    // Create a new PDO instance for connecting to the database
    // 'mysql:host=$host;dbname=$dbname;charset=utf8' sets the DSN (Data Source Name)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set PDO error mode to Exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Connection successful message (useful for debugging, remove in production)
    // echo "Database connection successful!";
} catch (PDOException $e) {
    // Handle database connection errors gracefully
    // Avoid exposing sensitive details like database credentials in the error message
    die("Database connection failed: " . $e->getMessage());
}
?>
