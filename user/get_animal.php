<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// establish database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "animal_rescue";
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// retrieve animal data from database
$sql = "SELECT animals.*, users.*, animals.name as animal_name FROM animals inner join users on animals.user_id = users.id where animals.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $animal_id);
$animal_id = $_GET["id"];
$stmt->execute();
$result = $stmt->get_result();
$animal = $result->fetch_assoc();

// return animal data as JSON object
header('Content-Type: application/json');
echo json_encode($animal);

// close database connection
$stmt->close();
$conn->close();

?>
