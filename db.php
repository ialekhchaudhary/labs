<?php

$servername = "peh-db";
$username = "peh-labs-user";
$password = "peh-labs-password";
$dbname = "peh-labs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
