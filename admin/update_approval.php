<?php
// start the session
session_start();

// check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  // redirect to the login page
  header('Location: login.php');
  exit;
}

// check if the form has been submitted
if (isset($_POST['id']) && isset($_POST['approval'])) {
  // sanitize input
  $id       = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
  $approval = filter_var($_POST['approval'], FILTER_SANITIZE_STRING);
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

  // Update animal approval status in the database
  $sql    = "UPDATE animals SET approval='$approval' WHERE id=$id";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    // success message
    $_SESSION['success'] = 'Approval status updated successfully.';
  } else {
    // error message
    $_SESSION['error'] = 'Unable to update approval status.';
  }

  // Close the database connection
  mysqli_close($conn);

  // redirect back to the animal list
  header('Location: dashboard.php');
  exit;
} else {
  echo $conn->error;
  // invalid request
  // header('Location: dashboard.php');
  exit;
}
