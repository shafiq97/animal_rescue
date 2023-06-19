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
  $location = $_POST['location'];

  // TODO: check isset and not empty for each search field and concatenate the sql query
  // the field: pet-type, location, breed, pet_status
  $sql = "SELECT * FROM animals 
  WHERE isMedical = 0
  AND approval    = 'approved' ";

  if (isset($_POST['pet-type']) && !empty($_POST['pet-type'])) {
    $pet_type = $_POST['pet-type'];
    $sql .= " AND type like '%$pet_type%' ";
  }

  if (isset($_POST['location']) && !empty($_POST['location'])) {
    $location = $_POST['location'];
    $sql .= " AND location like '%$location%' ";
  }

  if (isset($_POST['breed']) && !empty($_POST['breed'])) {
    $breed = $_POST['breed'];
    $sql .= " AND breed like '%$breed%' ";
  }

  if (isset($_POST['pet_status']) && !empty($_POST['pet_status'])) {
    $pet_status = $_POST['pet_status'];
    $sql .= " AND status like '%$pet_status%' ";
  }
} else if (isset($_GET['search'])) {
  $search = $_GET['search'];

  $sql = "SELECT * FROM animals WHERE isMedical = 0 AND approval = 'approved'";

  // Build the WHERE clause based on the search parameters
  $conditions = [];

  if (isset($_GET['pet-type']) && is_array($_GET['pet-type'])) {
    $petTypes = array_map(function ($value) use ($conn) {
      return mysqli_real_escape_string($conn, $value);
    }, $_GET['pet-type']);
    $petTypes = implode("', '", $petTypes);
    $conditions[] = "type IN ('$petTypes')";
  }

  if (isset($_GET['states']) && is_array($_GET['states'])) {
    $states = array_map(function ($value) use ($conn) {
      return mysqli_real_escape_string($conn, $value);
    }, $_GET['states']);
    $states = implode("', '", $states);
    $conditions[] = "location IN ('$states')";
  }

  // Append the conditions to the query if there are any
  if (!empty($conditions)) {
    $whereClause = implode(" AND ", $conditions);
    $sql .= " AND $whereClause";
  }

  // Execute the SQL query
  $result = mysqli_query($conn, $sql);

  if ($result) {
    $animals = mysqli_fetch_all($result, MYSQLI_ASSOC);
  } else {
    echo 'Error executing the query: ' . mysqli_error($conn);
    $animals = [];
  }
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
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body>
  <style>
    .fill-image {
      object-fit: cover;
      height: 100%;
      width: 100%;
    }


    .fixed-form {
      position: fixed;
      left: 0px;
      top: 130px;
      width: 200px;
      height: 100%;
      overflow: auto;
      box-sizing: border-box;
    }

    .navbar {
      background-color: #7B3F00;
    }

    .navbar .nav-link {
      color: white !important;
    }


    .wrapper {
      display: block;
      position: absolute;
      top: 50%;
      left: 50%;
      -webkit-transform: translate(-50%, -50%);
    }

    h1 a {
      color: #222;
      font-size: 2em;
      text-decoration: none;
      display: inline-block;
      position: relative;
      font-family: 'Dosis', sans-serif;
    }

    /*effect-underline*/
    a.effect-underline:after {
      content: '';
      position: absolute;
      left: 0;
      display: inline-block;
      height: 1em;
      width: 100%;
      border-bottom: 1px solid;
      margin-top: 10px;
      opacity: 0;
      transition: opacity 0.35s, transform 0.35s;
      transform: scale(0, 1);
    }

    a.effect-underline:hover:after {
      opacity: 1;
      transform: scale(1);
    }

    /*effect-box*/
    a.effect-box:after,
    a.effect-box:before {
      content: '';
      position: absolute;
      left: 0;
      display: inline-block;
      height: 1em;
      width: 100%;
      margin-top: 10px;
      opacity: 0;
      transition: opacity 0.35s, transform 0.35s;

    }

    a.effect-box:before {
      border-left: 1px solid;
      border-right: 1px solid;
      transform: scale(1, 0);
    }

    a.effect-box:after {
      border-bottom: 1px solid;
      border-top: 1px solid;
      transform: scale(0, 1);
    }

    a.effect-box:hover:after,
    a.effect-box:hover:before {
      opacity: 1;
      transform: scale(1);
    }

    /* effect-shine */
    a.effect-shine:hover {
      -webkit-mask-image: linear-gradient(-75deg, rgba(0, 0, 0, .6) 30%, #000 50%, rgba(0, 0, 0, .6) 70%);
      -webkit-mask-size: 200%;
      -webkit-animation: shine 2s infinite;
      animation: shine 2s infinite;
    }

    @-webkit-keyframes shine {
      from {
        -webkit-mask-position: 150%;
      }

      to {
        -webkit-mask-position: -50%;
      }
    }
  </style>
  </head>

  <body>
    <!-- Modal -->
    <div class="modal fade" id="animalModal" tabindex="-1" role="dialog" aria-labelledby="animalModalLabel" aria-hidden="true">
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
    <nav class="navbar navbar-expand-lg navbar-light">
      <div class="container">
        <img class="navbar-brand" height="100px" src="images/logo-header.png" alt="">
        <a class="navbar-brand" style="color: white" href="#">Animal Rescue</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar">
          <ul class="navbar-nav mr-auto text-right justify-content-between w-100">
            <li class="nav-item flex-fill">
              <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item flex-fill">
              <a class="nav-link" href="index2.php">Find a Pet</a>
            </li>
            <li class="nav-item flex-fill">
              <a class="nav-link" href="user/login.php">Manage Pet</a>
            </li>
            <li class="nav-item flex-fill">
              <a class="nav-link" href="user/login.php">List of Animal</a>
            </li>
            <li class="nav-item flex-fill">
              <a class="nav-link" href="user/login.php">Donate</a>
            </li>
            <li class="nav-item dropdown flex-fill">
              <a class="nav-link dropdown-toggle" href="#" id="petsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Pets
              </a>
              <div class="dropdown-menu" aria-labelledby="petsDropdown">
                <a class="dropdown-item" href="#">Dogs</a>
                <a class="dropdown-item" href="#">Cats</a>
                <a class="dropdown-item" href="#">Birds</a>
              </div>
            </li>
            <li class="nav-item dropdown flex-fill">
              <a class="nav-link dropdown-toggle" href="#" id="adoptionDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Adoption
              </a>
              <div class="dropdown-menu" aria-labelledby="adoptionDropdown">
                <a class="dropdown-item" href="#">Process</a>
                <a class="dropdown-item" href="#">Requirements</a>
                <a class="dropdown-item" href="#">Fees</a>
              </div>
            </li>
            <li class="nav-item flex-fill">
              <a class="btn btn-warning nav-link" href="user/login.php" style="margin-left: auto;">Login</a>
            </li>
          </ul>
        </div>
      </div>
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
              </select>
            </div>
          </div>
          <div class="row">
            <label for="pet_status" class="col-sm-2 col-form-label">Pet status</label>
            <div class="col-sm-10">
              <select name="pet_status" class="form-control" name="" id="">
                <option value="">Select Pet Status</option>
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
                <option value="">Select Location</option>
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
                <option value="wilayah persekutuan kuala lumpur">Kuala Lumpur</option>
                <option value="wilayah persekutuan labuan">Labuan</option>
                <option value="wilayah persekutuan putrajaya">Putrajaya</option>
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
        <div class="col-12">
          <form method="GET" action="">
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Search animal" name="search">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
              </div>
            </div>
            <div class="fixed-form">
              <div class="col">
                <div class="form-group">
                  <label for="pet-type">Pet Type:</label>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="pet-type[]" id="dog" value="dog">
                    <label class="form-check-label" for="dog">Dog</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="pet-type[]" id="cat" value="cat">
                    <label class="form-check-label" for="cat">Cat</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="pet-type[]" id="rabbit" value="rabbit">
                    <label class="form-check-label" for="rabbit">Rabbit</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="pet-type[]" id="hamster" value="hamster">
                    <label class="form-check-label" for="hamster">Hamster</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="pet-type[]" id="fish" value="fish">
                    <label class="form-check-label" for="fish">Fish</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="pet-type[]" id="bird" value="bird">
                    <label class="form-check-label" for="bird">Bird</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="pet-type[]" id="reptiles" value="reptiles">
                    <label class="form-check-label" for="reptiles">Reptiles</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="pet-type[]" id="small-and-furry" value="small-and-furry">
                    <label class="form-check-label" for="small-and-furry">Small and Furry</label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="states">States in Malaysia:</label>
                  <div class="row">
                    <div class="col">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="johor" value="johor">
                        <label class="form-check-label" for="johor">Johor</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="kedah" value="kedah">
                        <label class="form-check-label" for="kedah">Kedah</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="kelantan" value="kelantan">
                        <label class="form-check-label" for="kelantan">Kelantan</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="melaka" value="melaka">
                        <label class="form-check-label" for="melaka">Melaka</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="negeri-sembilan" value="negeri-sembilan">
                        <label class="form-check-label" for="negeri-sembilan">Negeri Sembilan</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="pahang" value="pahang">
                        <label class="form-check-label" for="pahang">Pahang</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="perak" value="perak">
                        <label class="form-check-label" for="perak">Perak</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="wilayah-putrajaya" value="wilayah-putrajaya">
                        <label class="form-check-label" for="wilayah-putrajaya">Putrajaya</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="perlis" value="perlis">
                        <label class="form-check-label" for="perlis">Perlis</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="penang" value="penang">
                        <label class="form-check-label" for="penang">Penang</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="sabah" value="sabah">
                        <label class="form-check-label" for="sabah">Sabah</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="sarawak" value="sarawak">
                        <label class="form-check-label" for="sarawak">Sarawak</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="selangor" value="selangor">
                        <label class="form-check-label" for="selangor">Selangor</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="terengganu" value="terengganu">
                        <label class="form-check-label" for="terengganu">Terengganu</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="wilayah-kuala-lumpur" value="wilayah-kuala-lumpur">
                        <label class="form-check-label" for="wilayah-kuala-lumpur">Kuala Lumpur</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="states[]" id="wilayah-labuan" value="wilayah-labuan">
                        <label class="form-check-label" for="wilayah-labuan">Labuan</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <button class="btn btn-primary">
                    Filter
                  </button>
                </div>
              </div>
            </div>

          </form>
          <h2>Featured Animals</h2>
          <?php
          // Set pagination variables
          $items_per_page = 6; // Number of animals per page
          $total_items    = count($animals); // Total number of animals
          $total_pages    = ceil($total_items / $items_per_page); // Calculate total number of pages

          if (isset($_GET['page']) && is_numeric($_GET['page'])) {
            $current_page = max(1, min($_GET['page'], $total_pages)); // Get current page from query parameter
          } else {
            $current_page = 1; // Default to the first page
          }
          $offset            = ($current_page - 1) * $items_per_page; // Calculate offset for database query
          $paginated_animals = array_slice($animals, $offset, $items_per_page); // Get the animals for the current page

          $column = 0;
          foreach ($paginated_animals as $animal) :
            if ($column == 0) {
              echo '<div class="row mt-4">';
            }
          ?>
            <div class="col-md-6 p-3">
              <div class="card" style="background-color: blanchedalmond;">
                <div class="row no-gutters">
                  <div class="col-md-6">
                    <img height="300px" src="<?php echo 'user/' . $animal['image_path']; ?>" class="card-img" alt="<?php echo $animal['name']; ?>">
                  </div>
                  <div class="col-md-6">
                    <div class="card-body">
                      <h5 class="card-title">
                        <?php echo $animal['name']; ?>
                      </h5>
                      <p class="card-text">Age (month/year):
                        <?php echo $animal['age']; ?> years old
                      </p>
                      <p class="card-text">Gender:
                        <?php echo $animal['gender']; ?>
                      </p>
                      <p class="card-text">Breed:
                        <?php echo $animal['breed']; ?>
                      </p>
                      <p class="card-text">Maturing Size:
                        <?php echo $animal['maturing_size']; ?>
                      </p>
                      <p class="card-text">Vaccinated:
                        <?php echo $animal['vaccinated'] ? 'Yes' : 'No'; ?>
                      </p>
                      <p class="card-text">Adoption Fee:
                        <?php echo $animal['medical_adopt_fee']; ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php
            $column++;
            if ($column == 2) {
              echo '</div>';
              $column = 0;
            }
          endforeach;
          if ($column == 1) {
            echo '</div>';
          }
          ?>

          <!-- Pagination links -->
          <nav aria-label="Animal Pagination" class="mt-4">
            <ul class="pagination justify-content-center">
              <?php if ($current_page > 1) : ?>
                <li class="page-item">
                  <a class="page-link" href="?page=<?php echo $current_page - 1; ?>">Previous</a>
                </li>
              <?php endif; ?>

              <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                  <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
              <?php endfor; ?>

              <?php if ($current_page < $total_pages) : ?>
                <li class="page-item">
                  <a class="page-link" href="?page=<?php echo $current_page + 1; ?>">Next</a>
                </li>
              <?php endif; ?>
            </ul>
          </nav>
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
      document.getElementById("logout-btn").addEventListener("click", function(event) {
        event.preventDefault();
        if (confirm("Are you sure you want to logout?")) {
          window.location.href = "logout.php";
        }
      });
    </script>
    <script>
      $(document).on('click', '.animal-img', function() {
        var animalId = $(this).data('animal-id');
        console.log(animalId);
        $.ajax({
          url: 'get_animal.php',
          type: 'GET',
          data: {
            id: animalId
          },
          success: function(data) {
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
          error: function(xhr, status, error) {
            console.log(error);
          }
        });
      });
    </script>
    <script>
      const breedSelect = document.getElementById('breed');

      const radioButtons = document.querySelectorAll('input[type="radio"][name="pet-type"]');
      radioButtons.forEach(radioButton => {
        radioButton.addEventListener('change', function() {
          const breedSelect = document.getElementById('breed');
          breedSelect.innerHTML = ''; // clear all options
          if (this.value === 'dog') {
            addOption('', 'Select dog breed');
            addOption('Mixed Breed', 'Mixed Breed');
            addOption('Affenpinscher', 'Affenpinscher');
            addOption('Afghan Hound', 'Afghan Hound');
            addOption('Airedale Terrier', 'Airedale Terrier');
            addOption('Akbash', 'Akbash');
            addOption('Akita', 'Akita');
            addOption('Alaskan Malamute', 'Alaskan Malamute');
            addOption('American Bulldog', 'American Bulldog');
            addOption('American Eskimo Dog', 'American Eskimo Dog');
            addOption('American Hairless Terrier', 'American Hairless Terrier');
            addOption('American Staffordshire Terrier', 'American Staffordshire Terrier');
            addOption('American Water Spaniel', 'American Water Spaniel');
            addOption('Anatolian Shepherd', 'Anatolian Shepherd');
            addOption('Appenzell Mountain Dog', 'Appenzell Mountain Dog');
            addOption('Australian Cattle Dog/Blue Heeler', 'Australian Cattle Dog/Blue Heeler');
            addOption('Australian Kelpie', 'Australian Kelpie');
            addOption('Australian Shepherd', 'Australian Shepherd');
            addOption('Australian Terrier', 'Australian Terrier');
          } else if (this.value === 'cat') {
            addOption('', 'Select cat breed');
            addOption('Domestic Short Hair', 'Domestic Short Hair');
            addOption('Domestic Medium Hair', 'Domestic Medium Hair');
            addOption('Domestic Long Hair', 'Domestic Long Hair');
            addOption('Abyssinian', 'Abyssinian');
            addOption('American Curl', 'American Curl');
            addOption('American Shorthair', 'American Shorthair');
            addOption('American Wirehair', 'American Wirehair');
            addOption('Applehead Siamese', 'Applehead Siamese');
            addOption('Balinese', 'Balinese');
            addOption('Bengal', 'Bengal');
            addOption('Birman', 'Birman');
            addOption('Bobtail', 'Bobtail');
            addOption('Bombay', 'Bombay');
            addOption('British Shorthair', 'British Shorthair');
            addOption('Burmese', 'Burmese');
            addOption('Burmilla', 'Burmilla');
            addOption('Calico', 'Calico');
            addOption('Canadian Hairless', 'Canadian Hairless');
            addOption('Chartreux', 'Chartreux');
          } else if (this.value === 'rabbit') {
            addOption('', 'Select Rabbit breed');
            addOption('American', 'American');
            addOption('American Fuzzy Lop', 'American Fuzzy Lop');
            addOption('American Sable', 'American Sable');
            addOption('Angora Rabbit', 'Angora Rabbit');
            addOption('Belgian Hare', 'Belgian Hare');
            addOption('Beveren', 'Beveren');
            addOption('Britannia Petite', 'Britannia Petite');
            addOption('Bunny Rabbit', 'Bunny Rabbit');
            addOption('Californian', 'Californian');
            addOption('Champagne DArgent', 'Champagne DArgent');
            addOption('Checkered Giant', 'Checkered Giant');
            addOption('Chinchilla', 'Chinchilla');
            addOption('Cinnamon', 'Cinnamon');
            addOption('Dutch', 'Dutch');
            addOption('Dwarf', 'Dwarf');
            addOption('New Zealand', 'New Zealand');
            addOption('Mini Rex', 'Mini Rex');
            addOption('Rex', 'Rex');
            addOption('Rhinelander', 'Rhinelander');
          } else if (this.value === 'hamster') {
            addOption('', 'Select hamster breed');
            addOption('Chinese Hamster', 'Chinese Hamster');
            addOption('Eversmanns Hamster', 'Eversmanns Hamster');
            addOption('Long-Tailed Hamster', 'Long-Tailed Hamster');
            addOption('Migratory Hamster', 'Migratory Hamster');
            addOption('Mouse-Like Hamster', 'Mouse-Like Hamster');
            addOption('Rat Hamster', 'Rat Hamster');
            addOption('Roborovskys Hamster', 'Roborovskys Hamster');
            addOption('Rummanian Hamster', 'Rummanian Hamster');
            addOption('Short Dwarf Hamster', 'Short Dwarf Hamster');
            addOption('Striped Hairy Foot Russian Hamster', 'Striped Hairy Foot Russian Hamster');
            addOption('Striped Hamster', 'Striped Hamster');
            addOption('Syrian / Golden Hamster', 'Syrian / Golden Hamster');
            addOption('Tibetan Hamsterham', 'Tibetan Hamsterham');

          } else if (this.value === 'fish') {
            addOption('', 'Select fish breed');
            addOption('Arowanas', 'Arowanas');
            addOption('Botia', 'Botia');
            addOption('Catfish', 'Catfish');
            addOption('Characins', 'Characins');
            addOption('Cichlids', 'Cichlids');
            addOption('Cyprinds', 'Cyprinds');
            addOption('Goldfish', 'Goldfish');
            addOption('Killifish', 'Killifish');
            addOption('Koi', 'Koi');
            addOption('Labyrinth Fish', 'Labyrinth Fish');
            addOption('Livebearers', 'Livebearers');
            addOption('Loaches', 'Loaches');
            addOption('Perches', 'Perches');
            addOption('Rainbowfish', 'Rainbowfish');

          } else if (this.value === 'reptiles') {
            addOption('', 'Select reptile type');
            addOption('Frog', 'Frog');
            addOption('Gecko', 'Gecko');
            addOption('Hermit Crab', 'Hermit Crab');
            addOption('Iguana', 'Iguana');
            addOption('Lizard', 'Lizard');
            addOption('Snake', 'Snake');
            addOption('Tortoise', 'Tortoise');
            addOption('Turtle', 'Turtle');
          } else if (this.value === 'small-and-furry') {
            addOption('', 'Select small and furry animal');
            addOption('Chinchilla', 'Chinchilla');
            addOption('Degu', 'Degu');
            addOption('Ferret', 'Ferret');
            addOption('Gerbil', 'Gerbil');
            addOption('Guinea Pig', 'Guinea Pig');
            addOption('Hamster', 'Hamster');
            addOption('Hedgehog', 'Hedgehog');
            addOption('Mouse', 'Mouse');
            addOption('Prairie Dog', 'Prairie Dog');
            addOption('Racoon', 'Racoon');
            addOption('Rat', 'Rat');
            addOption('Skunk', 'Skunk');
            addOption('Sugar Glider', 'Sugar Glider');
            addOption('Tarantula', 'Tarantula');
          } else if (this.value === 'bird') {
            addOption('', 'Select bird species');
            addOption('African Grey', 'African Grey');
            addOption('Amazon', 'Amazon');
            addOption('Brotogeris', 'Brotogeris');
            addOption('Budgie/Budgerigar', 'Budgie/Budgerigar');
            addOption('Button Quail', 'Button Quail');
            addOption('Caique', 'Caique');
            addOption('Canary', 'Canary');
            addOption('Chicken', 'Chicken');
            addOption('Cockatiel', 'Cockatiel');
            addOption('Cockatoo', 'Cockatoo');
            addOption('Conure', 'Conure');
            addOption('Dove', 'Dove');
            addOption('Duck', 'Duck');
            addOption('Eclectus', 'Eclectus');
            addOption('Emu', 'Emu');
            addOption('Finch', 'Finch');
            addOption('Goose', 'Goose');
            addOption('Guinea Fowl', 'Guinea Fowl');
            addOption('Kakariki', 'Kakariki');
            addOption('Lory/Lorikeet', 'Lory/Lorikeet');
            addOption('Lovebird', 'Lovebird');
            addOption('Macaw', 'Macaw');
            addOption('Ostrich', 'Ostrich');
            addOption('Parakeet', 'Parakeet');
            addOption('Parrot', 'Parrot');
            addOption('Parrotlet', 'Parrotlet');
            addOption('Peacock/Pea Fowl', 'Peacock/Pea Fowl');
            addOption('Pheasant', 'Pheasant');
            addOption('Pigeon', 'Pigeon');
            addOption('Pionus', 'Pionus');
            addOption('Poicephalus/Senegal', 'Poicephalus/Senegal');
            addOption('Quail', 'Quail');
            addOption('Quaker Parakeet', 'Quaker Parakeet');
            addOption('Rhea', 'Rhea');
            addOption('Ringneck/Psittacula', 'Ringneck/Psittacula');
            addOption('Rosella', 'Rosella');
            addOption('Softbill (Other)', 'Softbill (Other)');
            addOption('Swan', 'Swan');
          }
        });
      });

      function addOption(value, text) {
        const option = document.createElement('option');
        option.value = value;
        option.textContent = text;
        breedSelect.appendChild(option);
      }
    </script>
  </body>

</html>