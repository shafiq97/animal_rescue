<?php
// Connect to the database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'animal_rescue';
$conn = mysqli_connect($host, $user, $password, $dbname);
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
$query = "SELECT * FROM animals WHERE id = '$id'";
$result = mysqli_query($conn, $query);
if (!$result) {
  die('Error: ' . mysqli_error($conn));
}
$animal = mysqli_fetch_assoc($result);
if (!$animal) {
  echo 'Animal not found.';
  exit;
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
  <title><?php echo $animal['name']; ?> - Animal Profile</title>
</head>

<body>
  <h1><?php echo $animal['name']; ?> - Animal Profile</h1>
  <img src="<?php echo $animal['image_path']; ?>" alt="<?php echo $animal['name']; ?>">
  <p>Description: <?php echo $animal['description']; ?></p>
  <p>Age: <?php echo $animal['age']; ?> years old</p>
  <p>Gender: <?php echo $animal['gender']; ?></p>
  <p>Breed: <?php echo $animal['breed']; ?></p>
  <p>Maturing Size: <?php echo $animal['maturing_size']; ?></p>
  <p>Vaccinated: <?php echo $animal['vaccinated'] ? 'Yes' : 'No'; ?></p>
  <p>Donation Amount: <?php echo $animal['medical_adopt_fee']; ?></p>
  <form action="medical_fund.php">
    <div class="form-group">
      <input type="hidden" name='animal_id' value="<?php echo $animal['id'] ?>">
      <a href="medical_fund.php?id=<?php echo $animal['id'] ?>" onclick="return confirm('Are you sure?')"
        class='btn btn-warning'>Medical Fund</a>
    </div>
  </form>
</body>

</html>
