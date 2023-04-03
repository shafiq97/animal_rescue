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

// Get the form data
$name = $_POST['animal-name'];
$age = $_POST['animal-age'];
$description = $_POST['animal-description'];
$donation_amount = $_POST['donation-amount'];
$status = 'pending';

// Get the image data
$image_name = $_FILES['animal-image']['name'];
$image_tmp_name = $_FILES['animal-image']['tmp_name'];
$image_type = $_FILES['animal-image']['type'];
$image_size = $_FILES['animal-image']['size'];

// Validate the image file
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($image_type, $allowed_types)) {
  die('Error: Only JPG, PNG, and GIF files are allowed.');
}
if ($image_size > 5 * 1024 * 1024) {
  die('Error: Maximum file size is 5MB.');
}

// Move the image file to the uploads directory
$uploads_dir = 'uploads/';
$image_path = $uploads_dir . $image_name;
if (!move_uploaded_file($image_tmp_name, $image_path)) {
  die('Error: Failed to move uploaded file.');
}

// Insert the animal data into the database
$sql = "INSERT INTO animals (name, age, description, image_path, donation_amount, status) VALUES ('$name', $age, '$description', '$image_path', $donation_amount, '$status')";
if (mysqli_query($conn, $sql)) {
  echo "Animal registered successfully.";
} else {
  echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
