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

// Get the animal ID from the URL
if (isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  echo 'Animal ID not specified.';
  exit;
}

// Retrieve the animal data from the database
$query = "SELECT * FROM animals WHERE id = '$id'";
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
    <title><?php echo $animal['name']; ?> - Animal Profile</title>
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
    <h1><?php echo $animal['name']; ?> - Animal Profile</h1>
    <img src="<?php echo $animal['image_path']; ?>" alt="<?php echo $animal['name']; ?>">
    <p>Description: <?php echo $animal['description']; ?></p>
    <p>Age: <?php echo $animal['age']; ?> years old</p>
    <p>Gender: <?php echo $animal['gender']; ?></p>
    <p>Breed: <?php echo $animal['breed']; ?></p>
    <p>Maturing Size: <?php echo $animal['maturing_size']; ?></p>
    <p>Vaccinated: <?php echo $animal['vaccinated'] ? 'Yes' : 'No'; ?></p>
    <p>Donation Amount: <?php echo $animal['medical_adopt_fee']; ?></p>
    <!-- <form action="medical_fund.php">
      <div class="form-group">
        <input type="hidden" name='animal_id' value="<?php echo $animal['id'] ?>">
        <a href="medical_fund.php?id=<?php echo $animal['id'] ?>" onclick="return confirm('Are you sure?')"
          class='btn btn-warning'>Medical Fund</a>
      </div>
    </form> -->
  </body>
</html>

