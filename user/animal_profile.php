<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

    p {
      font-size: 18px;
      line-height: 1.5;
      color: #4d4d4d;
      margin-bottom: 20px;
    }

    form {
      text-align: center;
    }

    label {
      display: block;
      text-align: left;
      font-size: 16px;
      color: #4d4d4d;
      margin: 10px auto;
      width: 80%;
    }

    input[type="text"],
    select,
    textarea {
      display: block;
      width: 80%;
      margin: 0 auto;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
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
    <!-- <a class="navbar-brand" href="#">User Dashboard</a> -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    include('header.php');
    ?>
  </nav>
  <h1>
    <?php echo $animal['name']; ?> - Animal Profile
  </h1>
  <div>
    <img src="<?php echo $animal['image_path']; ?>" alt="<?php echo $animal['name']; ?>"
      style="width: auto; height: 900px; margin-bottom: 50px">
    <form action="update_animal.php" method="post">
      <input type="hidden" name="id" value="<?php echo $animal['id']; ?>">

      <label for="name">Name:</label>
      <input type="text" name="name" id="name" value="<?php echo $animal['name']; ?>">

      <label for="description">Description:</label>
      <textarea name="description" id="description" rows="5"><?php echo $animal['description']; ?></textarea>

      <label for="age">Age (month/year):</label>
      <input type="text" name="age" id="age" value="<?php echo $animal['age']; ?>">

      <label for="location" class="">Location</label>
      <select name="location" class="" id="location-select">
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
      <label for="age">Age (month/year):</label>
      <input type="text" name="age" id="age" value="<?php echo $animal['age']; ?>">

      <label for="health">Health:</label>
      <select name="health" id="">
        <option selected value="<?php echo $animal['health']; ?>"><?php echo $animal['health']; ?></option>
        <option value="good">Good</option>
        <option value="bad">Bad</option>
      </select>

      <label for="gender">Gender:</label>
      <input type="text" name="gender" id="gender" value="<?php echo $animal['gender']; ?>">

      <label for="breed">Breed:</label>
      <input type="text" name="breed" id="breed" value="<?php echo $animal['breed']; ?>">

      <label for="maturing_size">Maturing Size:</label>
      <input type="text" name="maturing_size" id="maturing_size" value="<?php echo $animal['maturing_size']; ?>">

      <label for="admin_approval">Admin Approval:</label>
      <input disabled type="text" name="admin_approval" id="admin_approval" value="<?php echo $animal['approval']; ?>">

      <label for="vaccinated">Vaccinated:</label>
      <input type="text" name="vaccinated" id="vaccinated" value="<?php echo $animal['vaccinated'] ? 'Yes' : 'No'; ?>">

      <label for="medical_adopt_fee">Donation Amount:</label>
      <input class="mb-3" type="text" name="medical_adopt_fee" id="medical_adopt_fee"
        value="<?php echo $animal['medical_adopt_fee']; ?>">
      <input type="submit" value="Update" class="btn btn-success">
      <a class="btn btn-warning" href="dashboard.php">Back</a>
    </form>
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
          <?php foreach ($medical_funds as $fund): ?>
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
    $(document).ready(function () {
      $('#medical_funds_table').DataTable();
    });
  </script>
</body>
</html>