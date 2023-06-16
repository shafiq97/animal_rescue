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
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

  <script>
    function confirmDeactivation(animalId) {
      Swal.fire({
        title: 'Confirm Deactivation',
        text: 'Are you sure you want to deactivate this animal?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Deactivate',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          // User clicked "Deactivate", perform the deletion
          window.location.href = 'delete_animal.php?id=' + animalId;
        }
      });
    }
  </script>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <!-- SweetAlert CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.min.css">

  <!-- SweetAlert JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.all.min.js"></script>

  <style>
    .card-img-top {
      width: 100%;
      height: 15vw;
      object-fit: cover;
    }

    .animal-image {
      object-fit: cover;
      height: 200px;
      /* Adjust as needed */
      width: 100%;
    }
  </style>
</head>
<!-- ... -->

<body>
  <?php if (isset($_GET['message']) && $_GET['message'] === 'success') : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Record deleted successfully.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  <?php endif; ?>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">User Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <?php
      include('header.php');
      ?>
    </div>
  </nav>

  <!-- Display animals in a card layout -->
  <div class="container">
    <div class="row">
      <?php foreach ($animals as $animal) : ?>
        <div class="col-12">
          <div class="card mb-3">
            <div class="row g-0">
              <div class="col-md-4">
                <img src="<?php echo $animal['image_path']; ?>" alt="<?php echo $animal['name']; ?>" class="animal-image img-fluid rounded-start">
              </div>
              <div class="col-md-8">
                <div class="card-body">
                  <div class="d-flex justify-content-between">
                    <div>
                      <h5 class="card-title">
                        <?php echo $animal['name']; ?>
                      </h5>
                      <p class="card-text">Name:
                        <?php echo $animal['name']; ?>
                      </p>
                      <p class="card-text">Breed:
                        <?php echo $animal['breed']; ?>
                      </p>
                      <p class="card-text">Status:
                        <?php echo $animal['status']; ?>
                      </p>
                      <p class="card-text">Gender:
                        <?php echo $animal['gender']; ?>
                      </p>
                      <p class="card-text">Age:
                        <?php echo $animal['age']; ?>
                      </p>
                      <p class="card-text">Posted On:
                        <?php echo $animal['created_at']; ?>
                      </p>
                    </div>
                    <div>
                      <a href="animal_profile.php?id=<?php echo $animal['id'] ?>" class="btn btn-primary d-block mb-2">Edit</a>
                      <a href="#" class="btn btn-danger d-block" onclick="confirmDeactivation(<?php echo $animal['id']; ?>)">Deactivate</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Logout confirmation dialog -->
  <script>
    document.getElementById("logout-btn").addEventListener("click", function(event) {
      event.preventDefault();
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
      }
    });
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>