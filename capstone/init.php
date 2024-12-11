<?php
require 'db.php';

// ################################################################################################
// Setup db
// ################################################################################################

$dropTable = "DROP TABLE IF EXISTS users";
if ($conn->query($dropTable) === TRUE) {
    echo "Table users dropped successfully\n";
} else {
    echo "Error dropping table: " . $conn->error . "\n";
}

$createTable = "CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(60) NOT NULL,
    type VARCHAR(10) NOT NULL,
    PRIMARY KEY (user_id)
)";

if ($conn->query($createTable) === TRUE) {
    echo "Table users created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

$stmt = $conn->prepare("INSERT INTO users (username, password, type) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $type);

$username = 'jeremy';
$password = '$2y$10$F9bvqz5eoawIS6g0FH.wGOUkNdBYLFBaCSzXvo2HTegQdNg/HlMJy';
$type = 'admin';
$stmt->execute();

$username = 'jessamy';
$password = '$2y$10$meh2WXtPZgzZPZrjAmHi2ObKk6uXd2yZio7EB8t.MVuV1KwhWv6yS';
$type = 'admin';
$stmt->execute();

$username = 'raj';
$password = '$2y$10$cCXaMFLC.ymTSqu1whYWbuU38RBN900NutjYBvCClqh.UHHg/XfFy';
$type = 'admin';
$stmt->execute();

$username = 'bob';
$password = '$2y$10$ojC8YCMKX2r/Suqco/h.TOFTIaw5k3Io5FVSCeWjCCqL8GWwmAczC';
$type = 'user';
$stmt->execute();

$username = 'maria';
$password = '$2y$10$EPM4Unjn4wnn4SjoEPJu7em6OLISImA50QS3T1jCLyh48d7Pv6KBi';
$type = 'user';
$stmt->execute();

$username = 'amir';
$password = '$2y$10$qAXjb233b7CMHc69CU.8ueluFWZDt9f08.XYJjsJ.EfC/O5JGSOqW';
$type = 'user';
$stmt->execute();

$username = 'xinyi';
$password = '$2y$10$37gojoTFmj86E6NbENGg9e2Xu2z6OKKSgnjYxDkXJn/8dvSk2tKfG';
$type = 'user';
$stmt->execute();

$username = 'kofi';
$password = '$2y$10$5sVvPfZOjzRTSeXJtQBGc.CfsDEwvITNkIg2IF9jSBhZZ1Rq.IK3.';
$type = 'user';
$stmt->execute();


echo "Users records created successfully\n";

### COFFEE TABLE ###

$dropTable = "DROP TABLE IF EXISTS coffee";
if ($conn->query($dropTable) === TRUE) {
    echo "Table coffee dropped successfully\n";
} else {
    echo "Error dropping table: " . $conn->error . "\n";
}

$createTable = "CREATE TABLE coffee (
    coffee_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(30) NOT NULL,
    region VARCHAR(20) NOT NULL,
    scoring VARCHAR(10) NOT NULL,
    varietal VARCHAR(20) NOT NULL,
    notes VARCHAR(60) NOT NULL,
    roast VARCHAR(10) NOT NULL,
    PRIMARY KEY (coffee_id)
)";

if ($conn->query($createTable) === TRUE) {
    echo "Table coffee created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

$stmt = $conn->prepare("INSERT INTO coffee (coffee_id, name, region, scoring, varietal, notes, roast) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssss", $coffee_id, $name, $region, $scoring, $varietal, $notes, $roast);

$coffee_id = 1;
$name = 'Huan';
$region = 'Aregentina';
$scoring = '87.1';
$varietal = 'Catuai';
$notes = 'Apple, Caramel, Blackberry';
$roast = 'dark';
$stmt->execute();

$coffee_id = 2;
$name = 'Kahawa';
$region = 'Kenya';
$scoring = '88.4';
$varietal = 'SL28, SL34';
$notes = 'Blackcurrant, Citrus, Molasses';
$roast = 'medium';
$stmt->execute();

$coffee_id = 3;
$name = 'Cafe Del Sol';
$region = 'Colombia';
$scoring = '89.7';
$varietal = 'Caturra, Typica';
$notes = 'Dark Chocolate, Cherry, Brown Sugar';
$roast = 'dark';
$stmt->execute();

$coffee_id = 4;
$name = 'Buna Ababa';
$region = 'Ethiopia';
$scoring = '90.1';
$varietal = 'Heirloom';
$notes = 'Blueberry, Jasmine, Lemon';
$roast = 'light';
$stmt->execute();

$coffee_id = 5;
$name = 'Java Royale';
$region = 'Indonesia';
$scoring = '86.8';
$varietal = 'Ateng, Jember';
$notes = 'Dark Chocolate, Licorice, Spice';
$roast = 'dark';
$stmt->execute();

$coffee_id = 6;
$name = 'Rising Sun';
$region = 'Japan';
$scoring = '85.5';
$varietal = 'Typica';
$notes = 'Green Tea, Peach, Toasted Almonds';
$roast = 'medium';
$stmt->execute();

$coffee_id = 7;
$name = 'Mount Kona';
$region = 'Hawaii';
$scoring = '90.3';
$varietal = 'Kona Typica';
$notes = 'Vanilla, Caramel, Macadamia Nut';
$roast = 'medium';
$stmt->execute();

$coffee_id = 8;
$name = 'Arabian Nights';
$region = 'Yemen';
$scoring = '88.6';
$varietal = 'Dawairi, Tuffahi';
$notes = 'Fig, Apricot, Honey';
$roast = 'light';
$stmt->execute();

### RATINGS TABLE ###

$dropTable = "DROP TABLE IF EXISTS ratings";
if ($conn->query($dropTable) === TRUE) {
    echo "Table ratings dropped successfully\n";
} else {
    echo "Error dropping table: " . $conn->error . "\n";
}

$createTable = "CREATE TABLE ratings (
    rating_id INT NOT NULL AUTO_INCREMENT,
    rater_id INT NOT NULL,
    coffee_id INT NOT NULL,
    rating INT NOT NULL,
    comment VARCHAR(100) NOT NULL,
    PRIMARY KEY (rating_id)
)";

if ($conn->query($createTable) === TRUE) {
    echo "Table raings created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

$stmt = $conn->prepare("INSERT INTO ratings (rater_id, coffee_id, rating, comment) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $rater_id, $coffee_id, $rating, $comment);

$rater_id = 2;
$coffee_id = 1;
$rating = 4;
$comment = 'Perfect for a morning espresso!';
$stmt->execute();
