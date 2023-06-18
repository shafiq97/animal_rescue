<?php
// start the session
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'animal_rescue';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
// Fetch user profile_picture from the database
// Fetch user information from the database
if (isset($_SESSION['username'])) {
  $query = "SELECT email, user_city, user_country, user_nophone, bank_acc, user_gender, profile_picture FROM users WHERE username = ?";
  $stmt  = $conn->prepare($query);
  $stmt->bind_param("s", $_SESSION['username']);
  $stmt->execute();
  $result = $stmt->get_result();
  $row    = $result->fetch_assoc();

  // Assigning fetched values to variables
  $email           = $row['email'];
  $user_city       = $row['user_city'];
  $user_country    = $row['user_country'];
  $user_nophone    = $row['user_nophone'];
  $bank_acc        = $row['bank_acc'];
  $user_gender     = $row['user_gender'];
  $profile_picture = $row['profile_picture'];
}


?>

<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
      <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="#">User Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    include('header.php');
    ?>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>Welcome,
          <?php echo $_SESSION['username']; ?>!
        </h1>
        <p>This is your user dashboard. You can view your profile, change your settings, and log out from here.</p>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col-md-6">
        <?php if (isset($profile_picture) && $profile_picture != ''): ?>
        <img style="height: 200px;" src="<?php echo $profile_picture; ?>" alt="Profile Picture"
          class="img-thumbnail mb-3">
        <?php endif; ?>
        <!-- ... -->
      </div>
    </div>
    <div class="row mt-5">
      <div class="col-md-6">
        <h2>Update Profile Information</h2>
        <form action="update_profile.php" method="post">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username"
              value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email"
              value="<?php echo isset($email) ? $email : ''; ?>" required>
          </div>
          <div class="form-group">
            <label for="user_city">City</label>
            <input type="text" class="form-control" id="user_city" name="user_city"
              value="<?php echo isset($user_city) ? $user_city : ''; ?>" required>
          </div>
          <div class="form-group">
            <label for="user_country">Country</label>
            <input type="text" class="form-control" id="user_country" name="user_country"
              value="<?php echo isset($user_country) ? $user_country : ''; ?>" required>
          </div>
          <div class="form-group">
            <label for="user_nophone">Phone Number</label>
            <input type="text" class="form-control" id="user_nophone" name="user_nophone"
              value="<?php echo isset($user_nophone) ? $user_nophone : ''; ?>" required>
          </div>
          <div class="form-group">
            <label for="bank_acc">Back Account</label>
            <input type="text" class="form-control" id="bank_acc" name="bank_acc"
              value="<?php echo isset($bank_acc) ? $bank_acc : ''; ?>" required>
          </div>
          <div class="form-group">
            <label for="user_gender">Gender</label>
            <select class="form-control" id="user_gender" name="user_gender" required>
              <option value="">Select gender</option>
              <option value="Male" <?php if (isset($user_gender) && $user_gender == 'Male') echo 'selected'; ?>>Male
              </option>
              <option value="Female" <?php if (isset($user_gender) && $user_gender == 'Female') echo 'selected'; ?>>
                Female</option>
              <option value="Other" <?php if (isset($user_gender) && $user_gender == 'Other') echo 'selected'; ?>>Other
              </option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>

  </div>
  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <!-- Logout confirmation dialog -->
  <script>
  document.getElementById("logout-btn").addEventListener("click", function(event) {
    event.preventDefault();
    if (confirm("Are you sure you want to logout?")) {
      window.location.href = "logout.php";
    }
  });
  </script>
</body>
</html>