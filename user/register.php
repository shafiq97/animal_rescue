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
  // get the username, email and password from the form
  $username = $_POST['username'];
  $email    = $_POST['email'];
  $password = $_POST['password'];

  // hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // prepare and execute the SQL query to insert the user into the database
  $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $username, $email, $hashed_password);
  $stmt->execute();

  // set the user as logged in
  $_SESSION['loggedin'] = true;
  // redirect to the dashboard
  header('Location: dashboard.php');
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <!-- Custom CSS -->
  <style>
    body {
      padding-top: 5rem;
    }

    form {
      margin: 0 auto;
      max-width: 330px;
      padding: 15px;
    }

    .form-label-group {
      position: relative;
      margin-bottom: 1rem;
    }

    .form-label-group>input,
    .form-label-group>label {
      height: 3.125rem;
      padding: .75rem;
    }

    .form-label-group>label {
      position: absolute;
      top: 0;
      left: 0;
      display: block;
      width: 100%;
      margin-bottom: 0;
      /* Override default `<label>` margin */
      line-height: 1.5;
      color: #495057;
      border: 1px solid transparent;
      border-radius: .25rem;
      transition: all .1s ease-in-out;
    }

    .form-label-group input::-webkit-input-placeholder {
      color: transparent;
    }

    .form-label-group input:-ms-input-placeholder {
      color: transparent;
    }

    .form-label-group input::-ms-input-placeholder {
      color: transparent;
    }

    .form-label-group input::-moz-placeholder {
      color: transparent;
    }

    .form-label-group input::placeholder {
      color: transparent;
    }

    .form-label-group input:not(:placeholder-shown) {
      padding-top: 1.25rem;
      padding-bottom: .25rem;
    }

    .form-label-group input:not(:placeholder-shown)~label {
      padding-top: .25rem;
      padding-bottom: .25rem;
      font-size: 12px;
      color: #777;
    }

    .btn-register {
      font-size: 16px;
      font-weight: bold;
      background-color: #007bff;
      border-color: #007bff;
    }

    .form-label-group input::placeholder {
      font-size: 14px;
      color: #999;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-4 offset-md-4">
        <h2 class="text-center mb-4">
          <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form-signin">
            <div class="form-label-group">
              <input type="text" id="username" name="username" class="form-control" placeholder="Username" required
                autofocus>
            </div>

            <div class="form-label-group">
              <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required>
            </div>

            <div class="form-label-group">
              <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button class="btn btn-lg btn-primary btn-block btn-register" type="submit">Register</button>
          </form>

          <div class="text-center mt-3">
            <a href="login.php">Already have an account? Login here.</a>
          </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>