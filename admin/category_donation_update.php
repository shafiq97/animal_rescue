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

// Retrieve images from the database
// Retrieve animals from the database
$id                = $_GET['id'];
$sql               = "SELECT * FROM category_donation where id = '$id'";
$result            = mysqli_query($conn, $sql);
$category_donation = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (isset($_POST['insert'])) {
  $category_name = $_POST['category_name'];

  $sql = "INSERT INTO category_donation (name) VALUES ('$category_name')";

  if (mysqli_query($conn, $sql)) {
    echo '<script>document.getElementById("alert").innerHTML = "Category inserted successfully."; document.getElementById("alert").style.display = "block";</script>';
    header("location: category_donation.php");
  } else {
    echo "Error inserting category: " . mysqli_error($conn);
  }
}


if (isset($_POST['update'])) {
  $category_name = $_POST['category_name'];
  $category_id   = $_POST['category_id'];

  $sql = "UPDATE category_donation SET name = '$category_name' WHERE id = '$category_id'";

  if (mysqli_query($conn, $sql)) {
    echo '<script>document.getElementById("alert").innerHTML = "Category updated successfully."; document.getElementById("alert").style.display = "block";</script>';
    header("location: category_donation.php");
  } else {
    echo "Error updating category: " . mysqli_error($conn);
  }
}

?>