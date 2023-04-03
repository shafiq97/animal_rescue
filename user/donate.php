<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// start the session
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

// check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $donation_amount = $_POST['donation_amount'];
  $animal_id       = $_POST['animal_id'];

  // update the donation amount in the database
  $sql    = "UPDATE animals SET donation_amount = donation_amount - {$donation_amount} WHERE id = {$animal_id}";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    // redirect to the dashboard page
    header('Location: dashboard.php');
    exit;
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}

// Close the database connection
mysqli_close($conn);
?>
