<?php
require 'db.php';
require 'db-check.php';
session_start();

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Specialty Coffee Review</title>
    <link href="../assets/bootstrap.min.css" rel="stylesheet">
    <link href="assets/custom.css" rel="stylesheet">
</head>

<body>
    <main>
        <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3">
                <div class="col-md-3 mb-2 mb-md-0">
                    <a href="/capstone/index.php"><img src="assets/coffee_logo.png" style="width: 70px"></a>
                </div>

                <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="/capstone/index.php" class="nav-link px-2 link-secondary">Home</a></li>
                </ul>

                <div class="col-md-3 text-end">
                    <?php if (isset($_SESSION['username'])) : ?>
                        <a href="/capstone/logout.php" class="btn btn-outline-secondary me-2">Logout</a>
                    <?php else : ?>
                        <button type="button" class="btn btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#signupModal">Sign-up</button>
                    <?php endif; ?>
                </div>

            </header>

            <?php if ($message) { ?>
                <div class="alert alert-success" role="alert">
                    <p class="text-center m-0"><?php echo $message; ?></p>
                </div>
            <?php } ?>
        </div>
        <div class="album py-5 bg-body-tertiary" style="background-color: #EAEAEA !important">
            <div class="container">
                <?php
                $sql = "SELECT * FROM coffee";
                $result = $conn->query($sql);

                echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">';
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // print stuff
                        echo '<div class="col" style="border-radius:1em; padding: 0 1em 1em 1em"><div class="card shadow-sm" style="border-radius:1em">';
                        //echo '<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">';
                        echo '<img src="assets/' . $row['coffee_id'] . '.png" class="featurette-image img-fluid mx-auto" alt="Responsive image" width="500" height="400" preserveAspectRatio="xMidYMid slice" focusable="false" style="border-radius: 1em 1em 0 0">';
                        echo '<title>' . $row['name'] . '</title>';
                        echo '</svg>';
                        echo '<div class="card-body">';
                        echo '<h2 class="card-text" style="margin-bottom:0;color:#8C4411">' . $row['name'] . '</h2>';
                        echo '<p class="card-text" style="margin-bottom:0">Scoring: ' . $row['scoring'] . '</p>';
                        echo '<p class="card-text" style="margin-bottom:0">Region: ' . $row['region'] . '</p>';
                        echo '<p class="card-text" style="margin-bottom:0">Notes: ' . $row['notes'] . '</p>';
                        echo '<p class="card-text">Varietal: ' . $row['varietal'] . '</p>';
                        echo '<div class="d-flex justify-content-between align-items-center">';
                        echo '<div class="btn-group">';
                        echo '<a href="/capstone/coffee.php?coffee=' . $row['coffee_id'] . '" class="btn btn-sm btn-outline-secondary">View</a>';
                        echo '<a href="/capstone/coffee.php?coffee=' . $row['coffee_id'] . '" class="btn btn-sm btn-outline-secondary">Add rating</a>';
                        echo '</div>';
                        // calculate rating
                        $current_coffee = $row['coffee_id'];
                        $sql2 = "SELECT * FROM ratings WHERE coffee_id = '$current_coffee'";
                        $result2 = $conn->query($sql2);
                        $total_score = 0;
                        $raing_count = 0;
                        if ($result2->num_rows > 0) {
                            while ($row2 = $result2->fetch_assoc()) {
                                $total_score += $row2['rating'];
                                $rating_count += 1;
                            }
                            $final_score = $total_score / $rating_count;
                        }
                        if ($final_score == 0) {
                            echo '<small class="text-body-secondary">Customer raing: No rating yet</small>';
                        } else {
                            echo '<small class="text-body-secondary">Customer raing: <strong>' . $final_score . '/5</strong></small>';
                        }
                        $final_score = 0;
                        echo '</div></div></div></div>';
                    }
                } else {
                    echo "<p>No coffee found - try resetting the DB at /init.php</p>";
                }
                echo '</div>';
                $conn->close();
                ?>
            </div>
        </div>

        <!-- Login modal -->
        <div class="modal" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Login</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/capstone/auth.php" method="post">
                            <div class="mb-3 form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" id="username" aria-describedby="usernameHelp" placeholder="Enter username">
                            </div>
                            <div class="mb-3 form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                            </div>
                            <input type="text" name="auth" hidden value="login">

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-secondary">Submit</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- Signup modal -->
        <div class="modal" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="signupModalLabel">Sign up</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/capstone/auth.php" method="post">
                            <div class="mb-3 form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" id="username" aria-describedby="usernameHelp" placeholder="Enter username">
                            </div>
                            <div class="mb-3 form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                            </div>
                            <div class="mb-3 form-group">
                                <label for="password-repeat">Password</label>
                                <input type="password" name="password-repeat" class="form-control" id="password-repeat" placeholder="Password repeated">
                            </div>
                            <input type="text" name="auth" hidden value="signup">

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-secondary">Submit</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <script src="../assets/jquery-3.2.1.slim.min.js"></script>
    <script src="../assets/popper.min.js"></script>
    <script src="../assets/bootstrap.min.js"></script>
</body>

</html>