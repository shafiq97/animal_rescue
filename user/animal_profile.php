<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
// Connect to the database
$host     = 'localhost';
$user     = 'root';
$password = '';
$dbname   = 'animal_rescue';
$conn     = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
  die('Could not connect: ' . mysqli_connect_error());
}

// Get the animal ID from the URL
if (isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  echo 'Animal ID not specified.';
  exit;
}

// Retrieve the animal data from the database
$query  = "SELECT * FROM animals WHERE id = '$id'";
$result = mysqli_query($conn, $query);
if (!$result) {
  die('Error: ' . mysqli_error($conn));
}
$animal = mysqli_fetch_assoc($result);
if (!$animal) {
  echo 'Animal not found.';
  exit;
}

// Retrieve the medical funds data from the database
$query_funds  = "SELECT * FROM medical_funds inner join users on users.id = medical_funds.user_id  WHERE animal_id = '$id'";
$result_funds = mysqli_query($conn, $query_funds);
if (!$result_funds) {
  die('Error: ' . mysqli_error($conn));
}
$medical_funds = mysqli_fetch_all($result_funds, MYSQLI_ASSOC);

$canEdit = "SELECT * FROM animals WHERE id = '$id'";
$result = mysqli_query($conn, $canEdit);
$edit_animal = mysqli_fetch_assoc($result);
$edit_animal_user_id = $edit_animal['user_id'];

$logged_in_user_id = $_SESSION['id'];



// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>

<head>
  <title>
    <?php echo $animal['name']; ?> - Animal Profile
  </title>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    h1 {
      text-align: center;
      margin-top: 40px;
      margin-bottom: 30px;
      color: #4d4d4d;
    }

    img {
      display: block;
      margin: 0 auto;
      max-width: 100%;
      height: auto;
    }

    /* .btn {
      background-color: #FFC107;
      color: #4d4d4d;
      border-radius: 4px;
      font-size: 18px;
      padding: 10px 20px;
      text-decoration: none;
      margin-top: 20px;
      display: inline-block;
    }

    .btn:hover {
      background-color: #FFA000;
    } */
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">User Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    include('header.php');
    ?>
  </nav>

  <div>
    <div class="container mb-3">
      <?php if (isset($_SESSION['animal_updated']) && $_SESSION['animal_updated']) : ?>
        <div class="alert alert-success" role="alert">
          Animal profile has been successfully updated!
        </div>
      <?php
        // Reset the value
        $_SESSION['animal_updated'] = false;
      endif;
      ?>
      <div class="card">
        <div class="card-header">
          <h1>
            <?php echo $animal['name']; ?> - Animal Profile
          </h1>
        </div>
        <div class="card-body">
          <img style="height: 400px;" src="<?php echo $animal['image_path']; ?>" alt="<?php echo $animal['name']; ?>" style="width: auto; height: 900px; margin-bottom: 50px">
        </div>
      </div>
    </div>
    <div class="container">
      <form action="update_animal.php" method="post" class="needs-validation" novalidate>
        <input type="hidden" name="id" value="<?php echo $animal['id']; ?>">

        <div class="form-group">
          <label for="name">Name:
            <?php echo $animal['name']; ?>
          </label>
          <input value="<?php echo $animal['name']; ?>" type="text" class="form-control" name="name" id="name" required>
          <div class="invalid-feedback">Please enter the name.</div>
        </div>

        <div class="form-group">
          <label for="description">Description: </label>
          <textarea class="form-control" name="description" id="description" rows="5" required><?php echo $animal['description']; ?></textarea>
          <div class="invalid-feedback">Please enter the description.</div>
        </div>

        <div class="form-group">
          <label for="age">Age (month/year):
            <?php echo $animal['age']; ?>
          </label>
          <input value="<?php echo $animal['age']; ?>" type="text" class="form-control" name="age" id="age" value="" required>
          <div class="invalid-feedback">Please enter the age.</div>
        </div>

        <div class="form-group">
          <label for="location">Location:
            <?php echo $animal['location'] ?>
          </label>
          <select class="form-control" name="location" id="location-select" required>
            <option selected value="<?php echo $animal['location'] ?>"><?php echo $animal['location'] ?></option>
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
          <div class="invalid-feedback">Please select a location.</div>
        </div>

        <div class="form-group">
          <label for="health">Health:</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="health" id="good" value="good" <?php echo $animal['health'] == 'good' ? 'checked' : ''; ?>>
            <label class="form-check-label" for="good">Good</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="health" id="bad" value="bad" <?php echo $animal['health'] == 'bad' ? 'checked' : ''; ?>>
            <label class="form-check-label" for="bad">Bad</label>
          </div>
        </div>

        <div class="form-group">
          <label for="gender">Gender:</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" id="male" value="male" <?php echo $animal['gender'] == 'male' ? 'checked' : ''; ?>>
            <label class="form-check-label" for="male">Male</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="gender" id="female" value="female" <?php echo $animal['gender'] == 'female' ? 'checked' : ''; ?>>
            <label class="form-check-label" for="female">Female</label>
          </div>
        </div>


        <div class="form-group">
          <label for="breed">Breed:</label>
          <input type="text" class="form-control" name="breed" id="breed" value="<?php echo $animal['breed']; ?>" required>
          <div class="invalid-feedback">Please enter the breed.</div>
        </div>

        <div class="form-group">
          <label for="maturing_size">Maturing Size:</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="maturing_size" id="large" value="large" <?php echo $animal['maturing_size'] == 'large' ? 'checked' : ''; ?>>
            <label class="form-check-label" for="large">Large</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="maturing_size" id="medium" value="medium" <?php echo $animal['maturing_size'] == 'medium' ? 'checked' : ''; ?>>
            <label class="form-check-label" for="medium">Medium</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="maturing_size" id="small" value="small" <?php echo $animal['maturing_size'] == 'small' ? 'checked' : ''; ?>>
            <label class="form-check-label" for="small">Small</label>
          </div>
        </div>


        <div class="form-group">
          <label for="admin_approval">Admin Approval:</label>
          <input disabled type="text" class="form-control" name="admin_approval" id="admin_approval" value="<?php echo $animal['approval']; ?>">
        </div>

        <div class="form-group">
          <label for="vaccinated">Vaccinated:</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="vaccinated" id="yes" value="yes" <?php echo $animal['vaccinated'] == '0' ? 'checked' : ''; ?>>
            <label class="form-check-label" for="yes">Yes</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="vaccinated" id="no" value="no" <?php echo $animal['vaccinated'] == '1' ? 'checked' : ''; ?>>
            <label class="form-check-label" for="no">No</label>
          </div>
        </div>

        <div class="form-group">
          <label for="medical_adopt_fee">Donation Amount:
            <?php echo $animal['medical_adopt_fee']; ?>
          </label>
          <input class="form-control mb-3 <?php echo isset($_POST['medical_adopt_fee']) && empty($_POST['medical_adopt_fee']) ? 'is-invalid' : ''; ?>" type="text" name="medical_adopt_fee" id="medical_adopt_fee" value="<?php echo $animal['medical_adopt_fee']; ?>" required>
          <div class="invalid-feedback">Please enter the donation amount.</div>
        </div>
        <?php if ($logged_in_user_id == $_SESSION['id']) : ?>
          <input type="submit" value="Update" class="btn btn-success">
        <?php endif; ?>
        <a class="btn btn-warning" href="dashboard.php">Back</a>
      </form>
    </div>
  </div>
  <div class="container">
    <div class="col">
      <h2 class="mt-3">Medical Funds Record</h2>
      <table id="medical_funds_table" border="1" cellpadding="10" cellspacing="0">
        <thead>
          <tr>
            <!-- <th>ID</th> -->
            <th>Donor Name</th>
            <th>Amount(RM)</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($medical_funds as $fund) : ?>
            <tr>
              <!-- <td>
                <?php echo $fund['id']; ?>
              </td> -->
              <td>
                <?php echo $fund['name']; ?>
              </td>
              <td>
                <?php echo $fund['total_amount']; ?>
              </td>
              <td>
                <?php echo $fund['receive_date']; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      $('#medical_funds_table').DataTable();
    });
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>