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

// check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // get the form data
  $amount = $_POST['amount'];
  $category = $_POST['category'];
  
  // handle the file upload
  $target_dir = "receipts/";
  $target_file = $target_dir . basename($_FILES["receipt"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  // Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["receipt"]["tmp_name"]);
  if($check !== false) {
    $uploadOk = 1;
  } else {
    $error = "File is not an image.";
    $uploadOk = 0;
  }
  // Check if file already exists
  if (file_exists($target_file)) {
    $error = "Sorry, file already exists.";
    $uploadOk = 0;
  }
  // Check file size
  if ($_FILES["receipt"]["size"] > 500000) {
    $error = "Sorry, your file is too large.";
    $uploadOk = 0;
  }
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }
  
  // if everything is ok, try to upload file
  if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["receipt"]["tmp_name"], $target_file)) {
      // file uploaded successfully, store the donation details in the database
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

      // get the animal id from the form data
      $user_id = $_SESSION['id'];
      // insert the donation details into the database
      $sql = "INSERT INTO donations (amount, category_id, receipt_path, user_id) VALUES ('$amount', '$category', '$target_file', '$user_id')";
      if (mysqli_query($conn, $sql)) {
        // donation details successfully added to the database
        header('Location: dashboard.php?success=true');
        exit;
      } else {
        $error = "Error: " . mysqli_error($conn);
      }

      // Close the database connection
      mysqli_close($conn);
    } else {
      $error = "Sorry, there was an error uploading your file.";
    }
  }
  else{
    echo $error;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Process Donation</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body
