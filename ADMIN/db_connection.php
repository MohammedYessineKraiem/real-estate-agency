<?php
// Database connection settings
$servername = "localhost"; // Host of the database (usually localhost)
$username = "root"; // MySQL username
$password = ""; // No password for root in XAMPP by default
$dbname = "bzbzd"; // Database name

// Function to open a database connection
function OpenCon() {
    global $servername, $username, $password, $dbname;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn; // Return the connection
}

// Function to close the connection
function CloseCon($conn) {
    $conn->close();
}
?>
