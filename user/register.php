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
  // get the username, email, password, name, country, state, city, phone, and bank account number from the form
  $username    = $_POST['username'];
  $email       = $_POST['email'];
  $password    = $_POST['password'];
  $name        = $_POST['name'];
  $country     = $_POST['country'];
  $state       = $_POST['state'];
  $city        = $_POST['city'];
  $phone       = $_POST['phone'];
  $bank_acc_no = $_POST['bank_acc_no'];

  // File upload handling
  $target_dir    = "profile/";
  $target_file   = $target_dir . basename($_FILES["profile_photo"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file);

  // prepare and execute the SQL query to insert the user into the database
  $stmt = $conn->prepare("INSERT INTO users (username, email, password, name, user_country, user_state, user_city, user_nophone, bank_acc, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssssssss", $username, $email, $password, $name, $country, $state, $city, $phone, $bank_acc_no, $target_file);
  $stmt->execute();


  // set the user as logged in
  $_SESSION['loggedin'] = true;
  $_SESSION['username'] = $username;
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
          <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form-signin"
            enctype="multipart/form-data">
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

            <div class="form-label-group">
              <input type="text" id="name" name="name" class="form-control" placeholder="Name" required>
            </div>

            <div class="form-label-group">
              <input type="text" id="country" name="country" class="form-control" placeholder="Country" required>
            </div>

            <div class="form-label-group">
              <input type="text" id="state" name="state" class="form-control" placeholder="State" required>
            </div>

            <div class="form-label-group">
              <input type="text" id="city" name="city" class="form-control" placeholder="City" required>
            </div>

            <div class="form-label-group">
              <input type="tel" id="phone" name="phone" class="form-control" placeholder="Phone" required>
            </div>

            <div class="form-label-group">
              <input type="text" id="bank_acc_no" name="bank_acc_no" class="form-control"
                placeholder="Bank Account Number" required>
            </div>

            <div class="form-label-group">
              <input type="file" id="profile_photo" name="profile_photo" class="form-control" required>
              <label for="profile_photo">Profile Photo</label>
            </div>

            <div class="form-group form-check">
              <div class="row">
                <input type="checkbox" class="form-check-input" id="terms" required>
                <h6 class="form-check-label" for="terms">I agree to Safe Paws <a target="_blank"
                    href="../terms_and_condition.php">terms and conditions</a></h6>
              </div>
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