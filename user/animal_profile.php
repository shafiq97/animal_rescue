<?php
// Connect to the database
$host     = 'localhost';
$user     = 'root';
$password = '';
$dbname   = 'animal_rescue';
$conn     = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
  die('Could not connect: ' . mysqli_connect_error());
}

// Get the animal ID from the URL
if (isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  echo 'Animal ID not specified.';
  exit;
}

// Retrieve the animal data from the database
$query  = "SELECT * FROM animals WHERE id = '$id'";
$result = mysqli_query($conn, $query);
if (!$result) {
  die('Error: ' . mysqli_error($conn));
}
$animal = mysqli_fetch_assoc($result);
if (!$animal) {
  echo 'Animal not found.';
  exit;
}

// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
  <title>
    <?php echo $animal['name']; ?> - Animal Profile
  </title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    h1 {
      text-align: center;
      margin-top: 40px;
      margin-bottom: 30px;
      color: #4d4d4d;
    }

    img {
      display: block;
      margin: 0 auto;
      max-width: 100%;
      height: auto;
    }

    p {
      font-size: 18px;
      line-height: 1.5;
      color: #4d4d4d;
      margin-bottom: 20px;
    }

    form {
      text-align: center;
    }

    label {
      display: block;
      text-align: left;
      font-size: 16px;
      color: #4d4d4d;
      margin: 10px auto;
      width: 80%;
    }

    input[type="text"],
    textarea {
      display: block;
      width: 80%;
      margin: 0 auto;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
    }

    .btn {
      background-color: #FFC107;
      color: #4d4d4d;
      border-radius: 4px;
      font-size: 18px;
      padding: 10px 20px;
      text-decoration: none;
      margin-top: 20px;
      display: inline-block;
    }

    .btn:hover {
      background-color: #FFA000;
    }
  </style>
</head>

<body>
  <h1>
    <?php echo $animal['name']; ?> - Animal Profile
  </h1>
  <form action="update_animal.php" method="post">
    <img src="<?php echo $animal['image_path']; ?>" alt="<?php echo $animal['name']; ?>">
    <input type="hidden" name="id" value="<?php echo $animal['id']; ?>">

    <label for="name">Name:</label>
    <input type="text" name="name" id="name" value="<?php echo $animal['name']; ?>">

    <label for="description">Description:</label>
    <textarea name="description" id="description" rows="5"><?php echo $animal['description']; ?></textarea>

    <label for="age">Age:</label>
    <input type="text" name="age" id="age" value="<?php echo $animal['age']; ?>">

    <label for="gender">Gender:</label>
    <input type="text" name="gender" id="gender" value="<?php echo $animal['gender']; ?>">

    <label for="breed">Breed:</label>
    <input type="text" name="breed" id="breed" value="<?php echo $animal['breed']; ?>">

    <label for="maturing_size">Maturing Size:</label>
    <input type="text" name="maturing_size" id="maturing_size" value="<?php echo $animal['maturing_size']; ?>">

    <label for="admin_approval">Admin Approval:</label>
    <input disabled type="text" name="admin_approval" id="admin_approval" value="<?php echo $animal['approval']; ?>">

    <label for="vaccinated">Vaccinated:</label>
    <input type="text" name="vaccinated" id="v    accinated"
      value="<?php echo $animal['vaccinated'] ? 'Yes' : 'No'; ?>">

    <label for="medical_adopt_fee">Donation Amount:</label>
    <input type="text" name="medical_adopt_fee" id="medical_adopt_fee"
      value="<?php echo $animal['medical_adopt_fee']; ?>">

    <input type="submit" value="Update" class="btn">
    <a class="btn" href="dashboard.php">Back</a>

  </form>
</body>
</html>