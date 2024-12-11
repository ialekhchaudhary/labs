<?php

$tableName = "coffee";
$query = "SHOW TABLES LIKE '$tableName'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // do nothing - all good table exists
} else {
    echo "<p>Seems like the database wasn't initiated yet - so I'm resetting it. If you only just arrived on this page, just ignore this message, go ahead and refresh the page :) Enjoy the challenge!</p>";
    include_once 'init.php';
    echo "<hr>";
}
