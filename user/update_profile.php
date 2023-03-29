<?php
// start the session
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

  // Prepare the SQL statement to update the user's profile information
  $sql = 'UPDATE users SET username=?, email=?, user_city=?, user_country=?, user_nophone=?, user_gender=?, bank_acc=? WHERE id=?';

  // Prepare the statement
  $stmt = mysqli_prepare($conn, $sql);

  // Bind the parameters to the statement
  mysqli_stmt_bind_param($stmt, 'sssssssi', $username, $email, $user_city, $user_country, $user_nophone, $user_gender, $bank_acc, $_SESSION['id']);

  // Get the form data and assign it to variables
  $username     = $_POST['username'];
  $email        = $_POST['email'];
  $user_city    = $_POST['user_city'];
  $user_country = $_POST['user_country'];
  $user_nophone = $_POST['user_nophone'];
  $user_gender  = $_POST['user_gender'];
  $bank_acc     = $_POST['bank_acc'];

  // Execute the statement
  if (mysqli_stmt_execute($stmt)) {
    // Update the session variables with the new values
    $_SESSION['username']     = $username;
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
    echo 'Error updating profile information: ' . mysqli_stmt_error($stmt);
  }

  // Close the statement
  mysqli_stmt_close($stmt);

  // Close the database connection
  mysqli_close($conn);
} else {
  // Redirect to the profile page
  header('Location: profile.php');
  exit;
}
?>