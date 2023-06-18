<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

$host     = 'localhost';
$username = 'root';
$password = '';
$dbname   = 'animal_rescue';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
  die('Connection failed: ' . mysqli_connect_error());
}

$sql     = 'SELECT * FROM animals where isMedical = 1 and approval = "approved"';
$result  = mysqli_query($conn, $sql);
$animals = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql2      = 'SELECT *, SUM(total_amount) AS total_donations
FROM medical_funds
GROUP BY animal_id
ORDER BY total_donations DESC';
$result2   = mysqli_query($conn, $sql2);
$donations = mysqli_fetch_all($result2, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
  <title>User Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <style>
    .fill-image {
      object-fit: cover;
      height: 100%;
      width: 100%;
    }

    .card {
      background-color: blanchedalmond;
      height: 100%;
      display: flex;
      flex-direction: column;
      margin-bottom: 20px;
      /* Add margin between rows */
    }

    .card-body {
      padding: 10px;
      flex-grow: 1;
    }

    .center-vertically {
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
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
        <div class="text-center">
          <h2>Medical fund</h2>
        </div>
        <div class="row mt-4">
          <?php
          $count = 0;
          foreach ($animals as $animal) :
          ?>
            <div class="col-md-12">
              <div class="card shadow clickable-card">
                <div class="row no-gutters" onclick="window.location.href='animal_profile.php?id=<?php echo $animal['id']; ?>';">
                  <div class="col-md-4">
                    <div style="
                      background-image: url('<?php echo $animal["image_path"]; ?>'); 
                      height: 100%; 
                      background-size: cover; 
                      background-position: center;
                      background-repeat: no-repeat;
                      display: flex;
                      align-items: center;
                      justify-content: center;">
                      <img src="<?php echo $animal["image_path"]; ?>" alt="<?php echo $animal["name"]; ?>" class="fill-image">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card-body">
                      <h5 class="card-title"><?php echo $animal['name']; ?></h5>
                      <p class="card-text"><?php echo $animal['description']; ?></p>
                      <p class="card-text">Age (month/year): <?php echo $animal['age']; ?> years old</p>
                      <p class="card-text">Gender: <?php echo $animal['gender']; ?></p>
                      <p class="card-text">Breed: <?php echo $animal['breed']; ?></p>
                      <p class="card-text">Maturing Size: <?php echo $animal['maturing_size']; ?></p>
                      <p class="card-text">Vaccinated: <?php echo $animal['vaccinated'] ? 'Yes' : 'No'; ?></p>
                      <p class="card-text">Medical Fee: <?php echo $animal['medical_adopt_fee']; ?></p>
                    </div>
                  </div>
                  <div class="col-md-4 text-center center-vertically pr-3">
                    <?php
                    $donationPercentage = 0;
                    $found = false;
                    foreach ($donations as $donation) {
                      if ($donation['animal_id'] == $animal['id']) {
                        $found = true;
                        $donationPercentage = (float) $donation['total_donations'] / (float) $animal['medical_adopt_fee'] * 100;
                        break;
                      }
                    }
                    ?>
                    <form action="medical_fund.php">
                      <div class="form-group">
                        <input type="hidden" name='animal_id' value="<?php echo $animal['id'] ?>">
                        <a href="<?php echo $donationPercentage >= 100 ? '#' : 'medical_fund.php?id=' . $animal['id'] ?>" class='<?php echo $donationPercentage >= 100 ? 'btn btn-success disabled' : 'btn btn-danger' ?>' onclick="<?php echo $donationPercentage < 100 ? 'return confirm(\'Are you sure?\')' : '' ?>" <?php echo $donationPercentage >= 100 ? 'disabled' : '' ?>>
                          <?php echo $donationPercentage >= 100 ? 'Case Completed' : 'Need Help' ?>
                        </a>
                      </div>
                    </form>
                    <?php
                    if ($found) {
                    ?>
                      <div class="progress mt-4">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $donationPercentage ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?php echo round($donationPercentage) ?>%</div>
                      </div>
                    <?php
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          <?php

            echo '</div><div class="row mt-4">';

          endforeach;
          ?>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>