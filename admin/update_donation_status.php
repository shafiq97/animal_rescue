<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
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

// Get the donation ID from the URL
if (isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  echo 'Donation ID not specified.';
  exit;
}

// die($id);

// Update the donation status in the database
$query  = "UPDATE donations SET admin_approval = 'approved' WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if (!$result) {
  die('Error: ' . mysqli_error($conn));
} else {
  echo 'Donation status has been updated.';
  header("location: donation_list.php");
}

// Close the database connection
mysqli_close($conn);
?>