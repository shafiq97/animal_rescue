<?php
// start the session
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

// check if the user is logged in
// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//   // redirect to the login page
//   header('Location: login.php');
//   exit;
// }

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
if (isset($_POST['search'])) {
  $pet_type = $_POST['pet-type'];
  $location = $_POST['location'];
  $sql      = "SELECT * FROM animals 
  WHERE isMedical = 0 
  AND approval    = 'approved' 
  AND type        = '$pet_type'
  AND location = '$location'
  ";
} else {
  $sql = "SELECT * FROM animals WHERE isMedical = 0 AND approval = 'approved'";
}
$result  = mysqli_query($conn, $sql);
$animals = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql2      = 'SELECT *, SUM(total_amount) AS total_donations
FROM medical_funds
GROUP BY animal_id
ORDER BY total_donations DESC';
$result2   = mysqli_query($conn, $sql2);
$donations = mysqli_fetch_all($result2, MYSQLI_ASSOC);




// Close the database connection
// mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <!-- Bootstrap CSS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>
<body>
  </head>

  <style>
    .fill-image {
      object-fit: cover;
      height: 100%;
      width: 100%;
    }
  </style>
  </head>
  <body>
    <!-- Modal -->
    <div class="modal fade" id="animalModal" tabindex="-1" role="dialog" aria-labelledby="animalModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="animalModalLabel">Animal Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h3>Contact information</h3>
            <p id="animalId"></p>
            <p id="animalName"></p>
            <p id="animalDescription"></p>
            <p id="userContact"></p>
            <p id="userEmail"></p>
            <p id="userName"></p>
            <p id="userPhone"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
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

    <form id="searcing-form" action="" method="POST">
      <div class="container mt-3">
        <div class="col card">
          <div class="row">
            <div class="col col-sm-5">
              <h3>Refine your search</h3>
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2" for="pet-type">Pet Type: </label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="pet-type" id="dog" value="dog">
              <label class="form-check-label" for="dog">Dog</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="pet-type" id="cat" value="cat">
              <label class="form-check-label" for="cat">Cat</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="pet-type" id="rabbit" value="rabbit">
              <label class="form-check-label" for="rabbit">Rabbit</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="pet-type" id="hamster" value="hamster">
              <label class="form-check-label" for="hamster">Hamster</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="pet-type" id="fish" value="fish">
              <label class="form-check-label" for="fish">Fish</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="pet-type" id="bird" value="bird">
              <label class="form-check-label" for="bird">Bird</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="pet-type" id="reptiles" value="reptiles">
              <label class="form-check-label" for="reptiles">Reptiles</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="pet-type" id="small-and-furry" value="small-and-furry">
              <label class="form-check-label" for="small-and-furry">Small and Furry</label>
            </div>
          </div>
          <div class="form-group row">
            <label for="breed" class="col-sm-2 col-form-label">Breed</label>
            <div class="col-sm-10">
              <select name="breed" id="breed" class="form-control">
                <option value="">-- Select Breed --</option>
                <option value="Labrador Retriever">Labrador Retriever</option>
                <option value="German Shepherd">German Shepherd</option>
                <option value="Golden Retriever">Golden Retriever</option>
                <option value="Bulldog">Bulldog</option>
                <option value="Beagle">Beagle</option>
                <option value="Poodle">Poodle</option>
                <option value="Rottweiler">Rottweiler</option>
                <option value="Yorkshire Terrier">Yorkshire Terrier</option>
              </select>
            </div>
          </div>
          <div class="row">
            <label for="pet_status" class="col-sm-2 col-form-label">Pet status</label>
            <div class="col-sm-10">
              <select name="pet_status" class="form-control" name="" id="">
                <option value="adoption">For Adoption</option>
                <option value="lost">Lost</option>
                <option value="found">Found</option>
                <option value="owners_pet">Owner's pet</option>
              </select>
            </div>
          </div>
          <div class="row">
            <label for="location" class="col-sm-2 col-form-label">Location</label>
            <div class="col-sm-10">
              <select name="location" class="form-control" id="location-select">
                <option value="johor">Johor</option>
                <option value="kedah">Kedah</option>
                <option value="kelantan">Kelantan</option>
                <option value="melaka">Melaka</option>
                <option value="negeri sembilan">Negeri Sembilan</option>
                <option value="pahang">Pahang</option>
                <option value="perak">Perak</option>
                <option value="perlis">Perlis</option>
                <option value="penang">Penang</option>
                <option value="sabah">Sabah</option>
                <option value="sarawak">Sarawak</option>
                <option value="selangor">Selangor</option>
                <option value="terengganu">Terengganu</option>
                <option value="wilayah persekutuan kuala lumpur">Wilayah Persekutuan Kuala Lumpur</option>
                <option value="wilayah persekutuan labuan">Wilayah Persekutuan Labuan</option>
                <option value="wilayah persekutuan putrajaya">Wilayah Persekutuan Putrajaya</option>
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col col-sm-2 ">
              <button class="btn btn-primary" name="search" type="submit">Search</button>
            </div>
          </div>
        </div>
      </div>
    </form>

    <div class="container" style="height: 100%; overflow-y: scroll;">
      <div class="row mt-5">
        <div class="col-md-12">
          <form method="GET" action="">
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Search animal" name="search">
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
              $sql         = "SELECT * FROM animals WHERE name LIKE '%{$search_term}%' or description LIKE '%{$search_term}%'";
              $result      = mysqli_query($conn, $sql);
              $animals     = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }

            $count = 0;
            foreach ($animals as $animal):
              if ($count % 2 == 0) {
                echo "<div class='row mt-4'>";
              }
              ?>
              <div class="col-md-6">

                <div class="card" style="width: 30vw;">
                  <img style="width: 30vw; height: 50vh;" class="card-image-top animal-img"
                    src="<?php echo $animal['image_path']; ?>" alt="<?php echo $animal['name']; ?>"
                    data-animal-id="<?php echo $animal['id']; ?>">
                  <div class="card-body">
                    <h5 class="card-title">
                      <?php echo $animal['name']; ?>
                    </h5>
                    <!-- <p class="card-text">
                      <?php echo $animal['description']; ?>
                    </p> -->
                    <p class="card-text">
                      Age (month/year):
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
                      Adoption Fee:
                      <?php echo $animal['medical_adopt_fee']; ?>
                    </p>
                    <!-- <form action="medical_fund.php">
                      <div class="form-group">
                        <input type="hidden" name='animal_id' value="<?php echo $animal['id'] ?>">
                        <a href="medical_fund.php?id=<?php echo $animal['id'] ?>"
                          onclick="return confirm('Are you sure?')" class='btn btn-warning'>Medical Fund</a>
                      </div>
                    </form> -->

                    <!-- <?php
                    $found = false;
                    foreach ($donations as $donation) {
                      if ($donation['animal_id'] == $animal['id']) {
                        $found = true;
                        ?>
                        <div class="progress mt-4">
                          <div class="progress-bar bg-success" role="progressbar"
                            style="width: <?php echo (double) $donation['total_amount'] / (double) $animal['medical_adopt_fee'] * 100 ?>%;"
                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?php echo round((double) $donation['total_amount'] / (double) $animal['medical_adopt_fee'] * 100) ?>%</div>
                        </div>
                        <?php
                      }
                    }
                    if (!$found) {
                      ?>
                      <div class="progress mt-4">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="25"
                          aria-valuemin="0" aria-valuemax="100">0%</div>
                      </div>
                      <?php

                    }
                    ?> -->
                  </div>
                </div>
              </div>
              <?php
              $count++;
              if ($count % 2 == 0) {
                echo "</div>";
              }
            endforeach;
            if ($count % 2 != 0) {
              echo "</div>";
            }
            ?>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap JS -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script> -->
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
    <script>
      $(document).ready(function () {
        $('#location-select').select2();
      });
    </script>
    <script>
      $(document).on('click', '.animal-img', function () {
        var animalId = $(this).data('animal-id');
        console.log(animalId);
        $.ajax({
          url: 'get_animal.php',
          type: 'GET',
          data: { id: animalId },
          success: function (data) {
            // Update the modal with the animal information
            console.log(data.animal_name);
            $('#animalModalLabel').text(data.animal_name);
            $('#animalId').text(data.id);
            $('#animalName').text(data.animal_names);
            $('#animalDescription').text(data.description);
            $('#userContact').text(data.username);
            $('#userEmail').text(data.email);
            $('#userPhone').text(data.user_nophone);
            $('#animalModal').modal('show');
          },
          error: function (xhr, status, error) {
            console.log(error);
          }
        });
      });

    </script>
  </body>
</html>