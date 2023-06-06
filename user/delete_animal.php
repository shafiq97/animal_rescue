<?php
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

// Check if the ID parameter is present in the URL
if (isset($_GET['id'])) {
  // Sanitize the ID to prevent SQL injection
  $animalId = mysqli_real_escape_string($conn, $_GET['id']);

  // Delete the record from the database
  $sql = "DELETE FROM animals WHERE id = $animalId";

  if (mysqli_query($conn, $sql)) {
    // Record deleted successfully
    echo "Record deleted successfully.";
    header("location: manage_animal.php?message=success");
  } else {
    // Failed to delete the record
    echo "Error deleting record: " . mysqli_error($conn);
  }
} else {
  // ID parameter not found, handle the error
  echo "Invalid ID parameter.";
}

// Close the database connection
mysqli_close($conn);
?>