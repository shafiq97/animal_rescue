<?php
// start the session
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

// check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  // redirect to the login page
  header('Location: login.php');
  exit;
}

// Database configuration
$host     = 'localhost';
$username = 'root';
$password = '';
$dbname   = 'animal_rescue';

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die('Connection failed: ' . mysqli_connect_error());
}

// Retrieve images from the database
// Retrieve animals from the database
$sql     = 'SELECT * FROM animals';
$result  = mysqli_query($conn, $sql);
$animals = mysqli_fetch_all($result, MYSQLI_ASSOC);


// Close the database connection
// mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">User Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    include('header.php');
    ?>
  </nav>

  <div class="container" style="height: 700px; overflow-y: scroll;">
    <div class="row mt-5">
      <div class="col-md-12">
        <form method="GET" action="">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search images by description" name="search">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
          </div>
        </form>
        <h2>Featured Animals</h2>
        <div class="card-deck mt-4">
          <?php
          if (isset($_GET['search'])) {
            $search_term = mysqli_real_escape_string($conn, $_GET['search']);
            $sql         = "SELECT * FROM animals WHERE description LIKE '%{$search_term}%'";
            $result      = mysqli_query($conn, $sql);
            $animals     = mysqli_fetch_all($result, MYSQLI_ASSOC);
          }
          foreach ($animals as $animal): ?>
            <div class="card">
              <img src="<?php echo $animal['image_path']; ?>" class="card-img-top" alt="<?php echo $animal['name']; ?>">
              <div class="card-body">
                <h5 class="card-title">
                  <?php echo $animal['name']; ?>
                </h5>
                <p class="card-text">
                  <?php echo $animal['description']; ?>
                </p>
                <p class="card-text">
                  Age:
                  <?php echo $animal['age']; ?> years old
                </p>
                <p class="card-text">
                  Gender:
                  <?php echo $animal['gender']; ?>
                </p>
                <p class="card-text">
                  Breed:
                  <?php echo $animal['breed']; ?>
                </p>
                <p class="card-text">
                  Maturing Size:
                  <?php echo $animal['maturing_size']; ?>
                </p>
                <p class="card-text">
                  Vaccinated:
                  <?php echo $animal['vaccinated'] ? 'Yes' : 'No'; ?>
                </p>
                <p class="card-text">
                  Donation Amount:
                  <?php echo $animal['medical_adopt_fee']; ?>
                </p>
                <form action="donate.php">
                  <div class="form-group">
                    <input type="hidden" name='animal_id' value="<?php echo $animal['id'] ?>">
                    <a href="donate.php?id=<?php echo $animal['id'] ?>" onclick="return confirm('Are you sure?')"
                      class='btn btn-primary'>Donate</a>
                  </div>
                </form>
                <form action="medical_fund.php">
                  <div class="form-group">
                    <input type="hidden" name='animal_id' value="<?php echo $animal['id'] ?>">
                    <a href="medical_fund.php?id=<?php echo $animal['id'] ?>" onclick="return confirm('Are you sure?')"
                      class='btn btn-warning'>Medical Fund</a>
                  </div>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <!-- Logout confirmation dialog -->
  <script>
    document.getElementById("logout-btn").addEventListener("click", function (event) {
      event.preventDefault();
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
      }
    });
  </script>
</body>
</html>