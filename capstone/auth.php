<?php
require 'db.php';
// post here from login & signup modals
// once done, send back to index.php with a message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // check for auth type
    $auth = $_POST['auth'];

    if ($auth === 'login' && isset($_POST['username']) && isset($_POST['password'])) {
        // attempt login
        $username = $_POST['username'];
        $password = $_POST['password'];

        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // verify password
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['username'] = $user['username'];
                $_SESSION['type'] = $user['type'];
                header("Location: index.php?message=You successfully logged in!");
                exit();
            } else {
                header("Location: index.php?message=Login failed!");
                exit();
            }
        } else {
            header("Location: index.php?message=Login failed!");
            exit();
        }
    } elseif ($auth === 'signup' && isset($_POST['username']) && isset($_POST['password'])) {
        // attempt signup
        $username = $_POST['username'];
        $password = $_POST['password'];

        $username = stripslashes($username);
        $password = stripslashes($password);

        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        // hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (username, password, type) VALUES (?, ?, 'user')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            header("Location: index.php?message=You successfully signed up! Please log in.");
        } else {
            header("Location: index.php?message=Sign up failed!");
        }
        exit();
    } else {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
    die();
}
