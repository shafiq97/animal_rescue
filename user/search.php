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

// connect to the database
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "animal_rescue";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// get the logged in user's ID
$user_id = $_SESSION['id'];

// prepare and execute the SQL query to retrieve the user's donations
$sql    = "SELECT * FROM animals";
$result = mysqli_query($conn, $sql);

$sql2    = "SELECT * FROM animals";
$result2 = mysqli_query($conn, $sql2);

// close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Animal List</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
</head>
</head>
<body>
      <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
      <img class="navbar-brand" height="60px" src="../images/logo-header.png" alt="">
    <a class="navbar-brand" href="#">Donation List</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    include('header.php');
    ?>
  </nav>

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <h2>Your Medical Fund Donations</h2>
        <?php if (mysqli_num_rows($result2) > 0): ?>
          <table id="animalTable" class="table table-striped table-bordered mt-4" style="width:100%">
            <thead>
              <tr>
                <th scope="col">Animal Id</th>
                <th scope="col">Name</th>
                <th scope="col">Type</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($result2)): ?>
                <tr>
                  <td>
                    <?php echo $row['id']; ?>
                  </td>
                  <td>
                    <?php echo $row['name']; ?>
                  </td>
                  <td>
                    <?php echo $row['type']; ?>
                  </td>
                  <td>
                    <?php echo $row['status']; ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>You haven't made any donations yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>


  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#animalTable').DataTable({
        initComplete: function () {
          this.api().columns().every(function () {
            var column = this;
            var header = $(column.header());
            var input = $('<input type="text" placeholder="Search"/>')
              .appendTo(header)
              .on('keyup', function () {
                var value = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(value).draw();
              });
          });
        }
      });
    });

  </script>

</body>
</html>