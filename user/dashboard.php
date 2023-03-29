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
$sql    = 'SELECT * FROM images';
$result = mysqli_query($conn, $sql);
$images = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Close the database connection
mysqli_close($conn);
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

  <div class="container">
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
            $sql         = "SELECT * FROM images WHERE description LIKE '%{$search_term}%'";
            $result      = mysqli_query($conn, $sql);
            $images      = mysqli_fetch_all($result, MYSQLI_ASSOC);
          }
          foreach ($images as $image): ?>
            <div class="card">
              <img src="<?php echo $image['url']; ?>" class="card-img-top" alt="<?php echo $image['alt']; ?>">
              <div class="card-body">
                <h5 class="card-title">
                  <?php echo $image['title']; ?>
                </h5>
                <p class="card-text">
                  <?php echo $image['description']; ?>
                </p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <h1>Welcome,
          <?php echo $_SESSION['username']; ?>!
        </h1>
        <p>This is your user dashboard. You can view your profile, change your settings, and log out from here.</p>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col-md-12">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <?php foreach ($images as $key => $image): ?>
              <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $key; ?>"
                class="<?php echo $key === 0 ? 'active' : ''; ?>"></li>
            <?php endforeach; ?>
          </ol>
          <div class="carousel-inner">
            <?php foreach ($images as $key => $image): ?>
              <div class="carousel-item <?php echo $key === 0 ? 'active' : ''; ?>">
                <img src="<?php echo $image['url']; ?>" height="500px" class="d-block w-100"
                  alt="<?php echo $image['alt']; ?>">
              </div>
            <?php endforeach; ?>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col-md-12">
        <h2>Featured Animals</h2>
        <div class="card-deck mt-4">
          <?php foreach ($images as $image): ?>
            <div class="card">
              <img src="<?php echo $image['url']; ?>" class="card-img-top" alt="<?php echo $image['alt']; ?>">
              <div class="card-body">
                <h5 class="card-title">
                  <?php echo $image['alt']; ?>
                </h5>
                <p class="card-text">
                  <?php echo $image['alt']; ?>
                </p>
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