<?php
// start the session
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
$userId = $_SESSION['id'];
// Retrieve animals from the database
$sql     = 'SELECT * FROM animals WHERE user_id=' . $userId . '';
$result  = mysqli_query($conn, $sql);
$animals = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
  <!-- Display animals in a table -->
  <div class="container" style="width: 100%">
    <table id="animals-table" class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Age (month/year) (Month/Year)</th>
          <th>Image</th>
          <!-- <th>Donation Amount</th> -->
          <th>Status</th>
          <th>Created At</th>
          <th>User ID</th>
          <th>Type</th>
          <th>Gender</th>
          <th>Breed</th>
          <th>Category ID</th>
          <th>Color</th>
          <th>Animal Condition</th>
          <th>Location</th>
          <th>Health</th>
          <th>Maturing Size</th>
          <th>Vaccinated</th>
          <th>Medical Adoption Fee</th>
          <th>Role</th>
          <th>Is Medical</th>
          <th>Approval</th>
          <th>Manage</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($animals as $animal): ?>
          <tr>
            <td>
              <?php echo $animal['id']; ?>
            </td>
            <td>
              <?php echo $animal['name']; ?>
            </td>
            <td>
              <?php echo $animal['age']; ?>
            </td>
            <td><img src="<?php echo $animal['image_path']; ?>" alt="<?php echo $animal['name']; ?>" class="fill-image">
            </td>
            <!-- <td>
              <?php echo $animal['donation_amount']; ?>
            </td> -->
            <td>
              <?php echo $animal['status']; ?>
            </td>
            <td>
              <?php echo $animal['created_at']; ?>
            </td>
            <td>
              <?php echo $animal['user_id']; ?>
            </td>
            <td>
              <?php echo $animal['type']; ?>
            </td>
            <td>
              <?php echo $animal['gender']; ?>
            </td>
            <td>
              <?php echo $animal['breed']; ?>
            </td>
            <td>
              <?php echo $animal['category_id']; ?>
            </td>
            <td>
              <?php echo $animal['color']; ?>
            </td>
            <td>
              <?php echo $animal['animal_condition']; ?>
            </td>
            <td>
              <?php echo $animal['location']; ?>
            </td>
            <td>
              <?php echo $animal['health']; ?>
            </td>
            <td>
              <?php echo $animal['maturing_size']; ?>
            </td>
            <td>
              <?php echo $animal['vaccinated']; ?>
            </td>
            <td>
              <?php echo $animal['medical_adopt_fee']; ?>
            </td>
            <td>
              <?php echo $animal['role']; ?>
            </td>
            <td>
              <?php echo $animal['isMedical']; ?>
            </td>
            <td>
              <?php echo $animal['approval']; ?>
            </td>
            <td>
              <a class="btn btn-primary" href="animal_profile.php?id=<?php echo $animal['id'] ?>">Edit</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  </div>

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