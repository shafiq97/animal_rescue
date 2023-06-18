<?php
// Start the session
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

// Check if the user is logged in
// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//   // Redirect to the login page
//   header('Location: login.php');
//   exit;
// }

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
if (isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  echo 'Animal ID not specified.';
  exit;
}
// Retrieve the medical funds data from the database
$query_funds  = "SELECT * FROM medical_funds inner join users on users.id = medical_funds.user_id  WHERE animal_id = '$id'";
$result_funds = mysqli_query($conn, $query_funds);
if (!$result_funds) {
  die('Error: ' . mysqli_error($conn));
}
$medical_funds = mysqli_fetch_all($result_funds, MYSQLI_ASSOC);

// Check if the animal ID is provided in the query parameters
if (isset($_GET['id'])) {
  $animal_id = mysqli_real_escape_string($conn, $_GET['id']);

  // Fetch the animal details from the database
  $sql    = "SELECT *, animals.name as animal_name FROM animals inner join users on animals.user_id = users.id WHERE animals.id = '$animal_id'";
  $result = mysqli_query($conn, $sql);
  $animal = mysqli_fetch_assoc($result);

  // Fetch the comments for the animal from the database
  $comments_sql    = "SELECT comments.*, users.* FROM comments INNER JOIN users ON comments.user_id = users.id WHERE comments.animal_id = '$animal_id' ORDER BY comments.created_at DESC";
  $comments_result = mysqli_query($conn, $comments_sql);
}

// Handle comment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Check if comment is submitted
  if (isset($_POST['comment'])) {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $user_id = $_SESSION['id'];
    // Insert comment into the comments table
    $insert_sql = "INSERT INTO comments (animal_id, comment, user_id) VALUES ('$animal_id', '$comment', '$user_id')";
    mysqli_query($conn, $insert_sql);
  }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Animal Details</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <!-- Font Awesome JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

  <style>
    .animal-image {
      width: 300px;
      height: 300px;
      object-fit: cover;
    }

    .vertical-center {
      min-height: 60vh;
      display: flex;
      /* align-items: center; */
      justify-content: center;
    }

    #medical_funds_table {
      width: 100%;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Animal Rescue</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Include the header file -->
    <?php include('header.php'); ?>
  </nav>

  <div class="container vertical-center mt-2">
    <?php if ($animal) : ?>
      <div class="card">
        <div class="card-title mt-1" style="text-align: center">
          <h2>
            <?php echo $animal['animal_name']; ?>
          </h2>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <img src="<?php echo $animal['image_path']; ?>" class="animal-image" alt="<?php echo $animal['name']; ?>">
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
              <p><strong>Age (month/year):</strong>
                <?php echo $animal['age']; ?> years old
              </p>
              <p><strong>Gender:</strong>
                <?php echo $animal['gender']; ?>
              </p>
              <p><strong>Breed:</strong>
                <?php echo $animal['breed']; ?>
              </p>
              <p><strong>Maturing Size:</strong>
                <?php echo $animal['maturing_size']; ?>
              </p>
              <p><strong>Vaccinated:</strong>
                <?php echo $animal['vaccinated'] ? 'Yes' : 'No'; ?>
              </p>
              <?php
              if ($animal['isMedical'] == 1) {
              ?>
                <p><strong>Medical Fee:</strong>
                <?php
              } else {
                ?>
                <p><strong>Adoption Fee:</strong>
                <?php
              }
                ?>
                <?php echo $animal['medical_adopt_fee']; ?>
                </p>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
              <button class="btn btn-warning">
                <img src="<?php echo $animal['profile_picture']; ?>" alt="" style="width: 24px; height: 24px;"> <?php echo $animal['username']; ?>
              </button>
              <button class="btn btn-secondary">
                <i class="fas fa-envelope"></i> <small>Send Email</small>
              </button>
              <button class="btn btn-success">
                <i class="fas fa-phone"></i>
                <?php echo $animal['user_nophone']; ?>
              </button>
              <button class="btn btn-primary" id="comment-button">
                <i class="fas fa-comment"></i> Write Comment
              </button>
              <?php
              if ($animal['isMedical'] == 1) {
              ?>
                <button class="btn btn-light" id="comment-button">
                  <i class="fas fa-comment"></i> Medical
                </button>
              <?php
              }
              ?>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-12">
              <p>
                <?php echo $animal['description']; ?>
              </p>
            </div>
          </div>
          <form action="" method="post" id="comment-form" style="display: none;">
            <div class="row mt-3">
              <div class="col-md-12">
                <input type="text" class="form-control" placeholder="Write your comment..." name="comment">
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Submit Comment</button>
              </div>
            </div>
          </form>
        </div>
      </div>



    <?php else : ?>
      <p>Animal not found.</p>
    <?php endif; ?>
  </div>

  <!-- Comments Section -->
  <div class="container mt-4" style="max-height: 300px; overflow-y: scroll;">
    <div class="card">
      <div class="card-header">
        Comments
      </div>
      <div class="card-body">
        <?php while ($comment = mysqli_fetch_assoc($comments_result)) : ?>
          <div class="media mb-3">
            <img src="<?php echo $comment['profile_picture']; ?>" alt="" class="mr-3 rounded-circle" style="width: 48px; height: 48px;">
            <div class="media-body">
              <h5 class="mt-0">
                <?php echo $comment['username']; ?>
              </h5>
              <?php echo $comment['comment']; ?>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
  <?php if ($animal['isMedical'] == 1) : ?>
    <div class="container">
      <div class="col-12">
        <h2 class="mt-3">Medical Funds Record</h2>
        <table id="medical_funds_table" border="1" cellpadding="10" cellspacing="0">
          <thead>
            <tr>
              <!-- <th>ID</th> -->
              <th>Donor Name</th>
              <th>Amount(RM)</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($medical_funds as $fund) : ?>
              <tr>
                <!-- <td>
                <?php echo $fund['id']; ?>
              </td> -->
                <td>
                  <?php echo $fund['name']; ?>
                </td>
                <td>
                  <?php echo $fund['total_amount']; ?>
                </td>
                <td>
                  <?php echo $fund['receive_date']; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>


  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#comment-button').click(function() {
        $('#comment-button').hide();
        $('#comment-form').show();
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      $('#medical_funds_table').DataTable();
    });
  </script>
</body>

</html>