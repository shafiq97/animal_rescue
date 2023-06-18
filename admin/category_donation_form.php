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
$id                = $_GET['id'];
$sql               = "SELECT * FROM category_donation where id = '$id'";
$result            = mysqli_query($conn, $sql);
$category_donation = mysqli_fetch_all($result, MYSQLI_ASSOC);


if (isset($_POST['update'])) {
  $category_name = $_POST['category_name'];
  $category_id   = $_POST['category_id'];

  $sql = "UPDATE category_donation SET name = '$category_name' WHERE id = '$category_id'";

  if (mysqli_query($conn, $sql)) {
    echo '<script>document.getElementById("alert").innerHTML = "Category updated successfully."; document.getElementById("alert").style.display = "block";</script>';
  } else {
    echo "Error updating category: " . mysqli_error($conn);
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">

  <!-- jQuery -->

  <!-- DataTables JS -->
  <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>

  <style>
    .fill-image {
      object-fit: cover;
      width: 200px;
      height: 200px;
    }
  </style>
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
  <!-- Display animals in a table -->
  <div class="container" style="width: 100%">
    <div class="col">
      <div id="alert" class="alert alert-danger" style="display: none;"></div>
      <form id="update-form" method="POST" action="category_donation_update.php">
        <div class="form-group">
          <label for="category-name">Category Name:</label>
          <input value="<?php echo $category_donation[0]['name'] ?>" type="text" class="form-control" id="category-name"
            name="category_name" required>
        </div>
        <div class="form-group">
          <input type="hidden" id="category-id" name="category_id" value="<?php echo $category_donation[0]['id'] ?>">
          <button type="submit" name="update" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>

  </div>
  <!-- Bootstrap JS -->
  <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script> -->
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>

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