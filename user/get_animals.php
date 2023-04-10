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

// Retrieve the animals data from the database
$query = "SELECT * FROM animals";
$result = mysqli_query($conn, $query);
if (!$result) {
  die('Error: ' . mysqli_error($conn));
}

// Fetch all rows and store them in an array
$animals = array();
while ($row = mysqli_fetch_assoc($result)) {
  $animals[] = $row;
}

// Close the database connection
mysqli_close($conn);

// Output the animals array as JSON
echo json_encode($animals);
?>