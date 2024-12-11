<?php

$servername = "peh-capstone-db";
$username = "peh-capstone-user";
$password = "peh-capstone-password";
$dbname = "peh-capstone-labs";
$port = 3306;

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
