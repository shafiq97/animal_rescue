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

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the form data and assign it to variables
  $id                = $_POST['id'];
  $name              = $_POST['name'];
  $description       = $_POST['description'];
  $age               = $_POST['age'];
  $gender            = $_POST['gender'];
  $breed             = $_POST['breed'];
  $location          = $_POST['location'];
  $health            = $_POST['health'];
  $maturing_size     = $_POST['maturing_size'];
  $vaccinated        = $_POST['vaccinated'] == 'Yes' ? 1 : 0;
  $medical_adopt_fee = $_POST['medical_adopt_fee'];


  // Update the animal data in the database using a raw SQL query
  $query = "UPDATE animals SET name='$name', description='$description', age='$age', gender='$gender', breed='$breed', maturing_size='$maturing_size', vaccinated='$vaccinated', medical_adopt_fee='$medical_adopt_fee', health='$health', location='$location' WHERE id='$id'";

  // Execute the query
  $result = mysqli_query($conn, $query);

  // Check for errors
  if (!$result) {
    die('Error: ' . mysqli_error($conn));
  } else {
    echo 'Animal data has been updated.';
    header("location: animal_profile.php?id=" . $id);
  }

  // Close the database connection
  mysqli_close($conn);
} else {
  echo 'No data received.';
}
?>