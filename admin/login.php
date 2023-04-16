<?php
// start the session
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

// connect to the database
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "animal_rescue";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // prepare and execute the SQL query
    $sql    = "SELECT * FROM users WHERE username='$username' and password = '$password'";
    $result = mysqli_query($conn, $sql);

    // check if the query returned any rows
    if (mysqli_num_rows($result) == 1) {
        // verify the password
        // set the user as logged in
        $row                  = mysqli_fetch_assoc($result);
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['email']    = $row['email'];
        $_SESSION['id']       = $row['id'];
        // redirect to the dashboard
        if ($row['role'] == 'admin') {
            header('Location: ../admin/dashboard.php');
        } else {

            header('Location: dashboard.php');
        }
        // display an error message        }
    } else {
        // display an error message
        $error = 'Invalid username';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
    <!-- Custom CSS -->

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <img src="https://static.vecteezy.com/system/resources/previews/006/470/722/original/pet-shop-logo-design-template-modern-animal-icon-label-for-store-veterinary-clinic-hospital-shelter-business-services-flat-illustration-background-with-dog-cat-and-horse-free-vector.jpg"
                    alt="Animal Logo" class="logo">
                <h2 class="text-center mb-4">User Login</h2>
                <form method="post" action="login.php" class="form-signin">
                    <div class="form-label-group">
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username"
                            required autofocus>
                        <label for="username">Username</label>
                    </div>

                    <div class="form-label-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password"
                            required>
                        <label for="password">Password</label>
                    </div>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <button class="btn btn-lg btn-primary btn-block btn-login" type="submit">Login</button>
                    <div class="text-center mt-3">
                        <a href="register.php">Create an account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>