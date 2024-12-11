<?php
require 'db.php';
require 'db-check.php';
session_start();

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['rating']) && isset($_POST['comment']) && isset($_POST['coffee_id'])) {

        $username = $_SESSION['username'];
        $rating = mysqli_real_escape_string($conn, $_POST['rating']);
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        $coffee_id = mysqli_real_escape_string($conn, $_POST['coffee_id']);

        // get the user_id from users table
        $userQuery = "SELECT user_id FROM users WHERE username = ?";
        $userStmt = $conn->prepare($userQuery);
        $userStmt->bind_param("s", $username);
        $userStmt->execute();
        $userResult = $userStmt->get_result();

        if ($userResult->num_rows === 0) {
            $message = "User not found.";
        } else {
            $user = $userResult->fetch_assoc();
            $user_id = $user['user_id'];

            // insert rating
            $ratingQuery = "INSERT INTO ratings (rater_id, coffee_id, rating, comment) VALUES (?, ?, ?, ?)";
            $ratingStmt = $conn->prepare($ratingQuery);
            $ratingStmt->bind_param("iiis", $user_id, $coffee_id, $rating, $comment);

            if ($ratingStmt->execute()) {
                $message = "Rating submitted successfully.";
            } else {
                $message = "Sorry, there was an error submitting your rating.";
            }
        }
    } else {
        $message = "Please provide all rating information.";
    }
}


if (isset($_GET['coffee'])) {
    $coffee = $_GET['coffee'];
} else {
    header("Location: index.php?message=Error finding that coffee, please try again!");
    die();
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
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM coffee WHERE coffee_id = '$coffee'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // print stuff
                            echo '<h1 style="color:#8C4411">' . $row['name'] . '</h1>';
                            echo '<div class="col">';
                            echo '<img src="assets/' . $row['coffee_id'] . '.png" class="featurette-image img-fluid mx-auto" alt="Responsive image" width="500" height="400" preserveAspectRatio="xMidYMid slice" focusable="false" style="border-radius: 1em; width: 100%">';
                            echo '</div>';
                            echo '<div class="col">';
                            echo '<p>Scoring: ' . $row['scoring'] . '</p>';
                            echo '<p>Region: ' . $row['region'] . '</p>';
                            echo '<p>Notes: ' . $row['notes'] . '</p>';
                            echo '<p>Varietal: ' . $row['varietal'] . '</p>';

                            // calculate rating
                            $sql2 = "SELECT * FROM ratings WHERE coffee_id = '$coffee'";
                            $result2 = $conn->query($sql2);
                            $total_score = 0;
                            $raing_count = 0;
                            $comments = array();
                            if ($result2->num_rows > 0) {
                                while ($row2 = $result2->fetch_assoc()) {
                                    $total_score += $row2['rating'];
                                    $rating_count += 1;
                                    array_push($comments, $row2['comment']);
                                }
                                $final_score = round($total_score / $rating_count, 2);
                            }
                            if ($final_score == 0) {
                                echo '<p>Customer raing: No rating yet</p>';
                            } else {
                                echo '<p>Customer raing: ' . $final_score . '/5</p>';
                            }
                            $final_score = 0;
                            echo '<a href="/capstone/coffee.php?coffee=' . $row['coffee_id'] . '" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#ratingModal">Add rating</a>';
                        }
                    } else {
                        echo "<p>No coffee found</p>";
                    }
                    $conn->close();
                    ?>

                </div>
            </div>
            <div class="row">
                <h2 style="margin: 1em 0 0.5em 0">Customer comments:</h2>
                <ul>
                    <?php
                    if ($comments) {
                        foreach ($comments as $comment) {
                            print '<li style="margin-left: 2em">' . $comment . "</li>";
                        }
                    } ?>
                </ul>
            </div>
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

        <!-- Rating modal -->
        <div class="modal" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ratingModalLabel">Add rating</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (isset($_SESSION['username'])) : ?>
                            <form action="/capstone/coffee.php?coffee=<?php echo $_GET['coffee']; ?>" method="post">
                                <div class="mb-3 form-group">
                                    <label for="rating">Rating</label>
                                    <div class="d-flex justify-content-between">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rating" id="rating1" value="1">
                                            <label class="form-check-label" for="rating1">1</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rating" id="rating2" value="2">
                                            <label class="form-check-label" for="rating2">2</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rating" id="rating3" value="3">
                                            <label class="form-check-label" for="rating3">3</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rating" id="rating4" value="4">
                                            <label class="form-check-label" for="rating4">4</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="rating" id="rating5" value="5">
                                            <label class="form-check-label" for="rating5">5</label>
                                        </div>
                                        <input type="hidden" name="coffee_id" value="<?php echo $_GET['coffee']; ?>">
                                    </div>
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="comment">Comment</label>
                                    <textarea class="form-control" name="comment" id="comment" rows="3" placeholder="Enter your comment"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-secondary">Submit</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        <?php else : ?>
                            <p>Please login before leaving a rating!</p>
                        <?php endif; ?>
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