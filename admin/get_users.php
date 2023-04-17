<?php
// Set database credentials
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
$host     = "localhost";
$username = "root";
$password = "";
$dbname   = "animal_rescue";

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Retrieve data from users table
$query  = "SELECT id, username, email, user_city, user_country, user_nophone, user_gender, profile_picture, bank_acc, name, user_state, role FROM users";
$result = mysqli_query($conn, $query);

// Check if there are any results
if (mysqli_num_rows($result) > 0) {
  // Output data as JSON
  $users = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
  }
  header('Content-Type: application/json');
  echo json_encode($users);
} else {
  echo "No users found";
}

// Close connection
mysqli_close($conn);
?>