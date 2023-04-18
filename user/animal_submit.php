<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
// connect to database
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "animal_rescue";
$conn       = new mysqli($servername, $username, $password, $dbname);

// check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// escape user inputs for security
$animalName   = mysqli_real_escape_string($conn, $_POST['animal-name']);
$petStatus    = mysqli_real_escape_string($conn, $_POST['pet_status']);
$petType      = mysqli_real_escape_string($conn, $_POST['pet_type']);
$petGender    = mysqli_real_escape_string($conn, $_POST['pet_gender']);
$breed        = mysqli_real_escape_string($conn, $_POST['breed']);
$animalAge    = mysqli_real_escape_string($conn, $_POST['animal-age']);
$color        = mysqli_real_escape_string($conn, $_POST['color']);
$maturingSize = mysqli_real_escape_string($conn, $_POST['maturing_size']);
$vaccinated   = mysqli_real_escape_string($conn, $_POST['vaccinated']);

if (isset($_POST['code_category'])) {
  $codeCategory = mysqli_real_escape_string($conn, $_POST['code_category']);
} else {
  $codeCategory = 0;
}
$health = mysqli_real_escape_string($conn, $_POST['health']);

$animalDescription = mysqli_real_escape_string($conn, $_POST['animal-description']);
$medicalAdoptFee   = mysqli_real_escape_string($conn, $_POST['medical_adopt_fee']);
$role              = mysqli_real_escape_string($conn, $_POST['role']);
$location          = mysqli_real_escape_string($conn, $_POST['location']);
$isMedical         = 0;


// die($isMedical);

if (isset($_POST['isMedical'])) {
  $isMedical = 1;
}

// handle image upload
$targetDir     = "uploads/";
$targetFile    = $targetDir . basename($_FILES["animal-image"]["name"]);
$uploadOk      = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// check if image file is a actual image or fake image
if (isset($_POST["submit"])) {
  $check = getimagesize($_FILES["animal-image"]["tmp_name"]);
  if ($check !== false) {
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}

// check if file already exists
if (file_exists($targetFile)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// check file size
if ($_FILES["animal-image"]["size"] > 5000000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// allow certain file formats
if (
  $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif"
) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["animal-image"]["tmp_name"], $targetFile)) {
    echo "The file " . basename($_FILES["animal-image"]["name"]) . " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
$image   = $targetFile;
$user_id = $_SESSION['id'];
// insert data into database
$sql = "INSERT INTO animals (name, status, type, gender, breed, age, color, maturing_size, vaccinated, category_id, description, image_path, medical_adopt_fee, role, user_id, isMedical, location, health) VALUES ('$animalName', '$petStatus', '$petType', '$petGender', '$breed', '$animalAge', '$color', '$maturingSize', '$vaccinated', '$codeCategory', '$animalDescription', '$image', '$medicalAdoptFee', '$role', '$user_id', '$isMedical', '$location', '$health')";

// Execute the SQL query
if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
  header("location: dashboard.php");
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();