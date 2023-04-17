<?php
// start the session
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

// check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  // redirect to the login page
  header('Location: login.php');
  exit;
}

// check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Database configuration
  $host        = 'localhost';
  $db_username = 'root';
  $password    = '';
  $dbname      = 'animal_rescue';

  // Create connection
  $conn = mysqli_connect($host, $db_username, $password, $dbname);

  // Check connection
  if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  // Get the form data and assign it to variables
  $input_username = mysqli_real_escape_string($conn, $_POST['username']);
  $email          = mysqli_real_escape_string($conn, $_POST['email']);
  $user_city      = mysqli_real_escape_string($conn, $_POST['user_city']);
  $user_country   = mysqli_real_escape_string($conn, $_POST['user_country']);
  $user_nophone   = mysqli_real_escape_string($conn, $_POST['user_nophone']);
  $user_gender    = mysqli_real_escape_string($conn, $_POST['user_gender']);
  $bank_acc       = mysqli_real_escape_string($conn, $_POST['bank_acc']);

  // Prepare the SQL statement to update the user's profile information
  $sql = "UPDATE users SET username='$input_username', email='$email', user_city='$user_city', user_country='$user_country', user_nophone='$user_nophone', user_gender='$user_gender', bank_acc='$bank_acc' WHERE id={$_SESSION['id']}";
  // die($sql);
  // Execute the query
  if (mysqli_query($conn, $sql)) {
    // Update the session variables with the new values
    $_SESSION['username']     = $input_username;
    $_SESSION['email']        = $email;
    $_SESSION['user_city']    = $user_city;
    $_SESSION['user_country'] = $user_country;
    $_SESSION['user_nophone'] = $user_nophone;
    $_SESSION['user_gender']  = $user_gender;
    $_SESSION['bank_acc']     = $bank_acc;

    // Redirect to the profile page
    header('Location: profile.php');
    exit;
  } else {
    // Handle the error
    echo 'Error updating profile information: ' . mysqli_error($conn);
  }

  // Close the database connection
  mysqli_close($conn);
} else {
  // Redirect to the profile page
  header('Location: profile.php');
  exit;
}
?>