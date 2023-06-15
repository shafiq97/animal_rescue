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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="#">User Dashboard</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
        <div class="col-12">
          <form method="GET" action="">
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Search animal" name="search">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
              </div>
            </div>
          </form>
          <h2>Featured Animals</h2>
          <?php
          // Set pagination variables
          $items_per_page = 4; // Number of animals per page
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
              <div class="card shadow clickable-card" style="width: 100%; background-color: blanchedalmond; height: auto; overflow: hidden; background-clip: border-box;">
                <div class="row no-gutters">
                  <div class="col-md-6">
                    <div style="
                      background-image: url('<?php echo $animal["image_path"]; ?>'); 
                      height: 100%; 
                      background-size: cover; 
                      background-position: center;">
                    </div>
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

    </div>

    </div>

    </div>




    <!-- Bootstrap JS -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
      $(document).ready(function() {
        $('#location-select').select2();
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