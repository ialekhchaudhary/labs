<?php
require '../db.php';
session_start();

if (isset($_SESSION['type']) && $_SESSION['type'] === 'admin') {
    // do nothing ;)
} else {
    header("Location: ../index.php?message=Admins only!");
    die();
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && isset($_POST['region']) && isset($_POST['scoring']) && isset($_POST['varietal']) && isset($_POST['notes']) && isset($_POST['roast']) && isset($_FILES['image'])) {

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $region = mysqli_real_escape_string($conn, $_POST['region']);
        $scoring = mysqli_real_escape_string($conn, $_POST['scoring']);
        $varietal = mysqli_real_escape_string($conn, $_POST['varietal']);
        $notes = mysqli_real_escape_string($conn, $_POST['notes']);
        $roast = mysqli_real_escape_string($conn, $_POST['roast']);

        $images_dir = '../assets/';
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_info = getimagesize($_FILES['image']['tmp_name']);
        $client_provided_type = $_FILES['image']['type'];

        if ($image_info['mime'] != 'image/png' || $client_provided_type != 'image/png') {
            $message = 'Please upload a PNG file.';
        } else {
            $sql = "INSERT INTO coffee (name, region, scoring, varietal, notes, roast) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $name, $region, $scoring, $varietal, $notes, $roast);

            if ($stmt->execute()) {
                $coffee_id = $conn->insert_id;
                $new_image_path = "$images_dir/$coffee_id.$file_ext";
                if (move_uploaded_file($_FILES['image']['tmp_name'], $new_image_path)) {
                    $message = "The file has been uploaded successfully.";
                } else {
                    $message = "Sorry, there was an error uploading your file.";
                }
            } else {
                $message =  "Sorry, there was an error adding the coffee: " . $stmt->error;
            }
        }
    } else {
        $message =  "Please fill all the fields and upload an image.";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Specialty Coffee Review</title>
    <link href="../../assets/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/custom.css" rel="stylesheet">
</head>

<body>
    <main>
        <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3">
                <div class="col-md-3 mb-2 mb-md-0">
                    <a href="/capstone/index.php"><img src="../assets/coffee_logo.png" style="width: 70px"></a>
                </div>

                <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="/capstone/index.php" class="nav-link px-2 link-secondary">Home</a></li>
                </ul>

                <div class="col-md-3 text-end">
                    <a href="/capstone/logout.php" class="btn btn-outline-secondary me-2">Logout</a>
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
                <h1>Add New Coffee</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Coffee Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="region">Region</label>
                        <input type="text" class="form-control" id="region" name="region" required>
                    </div>

                    <div class="form-group">
                        <label for="scoring">Scoring</label>
                        <input type="text" class="form-control" id="scoring" name="scoring" required>
                    </div>

                    <div class="form-group">
                        <label for="varietal">Varietal</label>
                        <input type="text" class="form-control" id="varietal" name="varietal" required>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <input type="text" class="form-control" id="notes" name="notes" required>
                    </div>

                    <div class="form-group">
                        <label for="roast">Roast</label>
                        <input type="text" class="form-control" id="roast" name="roast" required>
                    </div>

                    <div class="form-group mb-2">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/png, image/jpeg" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

    </main>
    <script src="../../assets/jquery-3.2.1.slim.min.js"></script>
    <script src="../../assets/popper.min.js"></script>
    <script src="../../assets/bootstrap.min.js"></script>
</body>

</html>