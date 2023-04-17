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
$sql     = 'SELECT * FROM animals';
$result  = mysqli_query($conn, $sql);
$animals = mysqli_fetch_all($result, MYSQLI_ASSOC);




// Close the database connection
// mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">

  <!-- jQuery -->

  <!-- DataTables JS -->
  <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>

  <style>
    .fill-image {
      object-fit: cover;
      width: 200px;
      height: 200px;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">User Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    include('header.php');
    ?>
  </nav>
  <!-- Display animals in a table -->
  <div class="container" style="width: 100%">
    <div class="col">
      <a class="btn btn-primary m-3" href="category_donation_add.php">Add Category</a>
      <table id="categoryTable" class="display">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
      </table>

    </div>
  </div>

  </div>
  <!-- Bootstrap JS -->
  <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script> -->
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      var table = $('#categoryTable').DataTable({
        "ajax": {
          "url": "category_donation_data.php",
          "dataSrc": ""
        },
        "columns": [
          { "data": "id" },
          { "data": "name" },
          {
            "data": null,
            "render": function (data, type, row) {
              return '<button class="btn btn-primary edit-btn" data-id="' + row.id + '">Edit</button>';
            }
          },
          {
            "data": null,
            "render": function (data, type, row) {
              return '<button class="btn btn-danger delete-btn" data-id="' + row.id + '">Delete</button>';
            }
          }
        ]
      });

      // Handle the Edit button click event
      $('#categoryTable').on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        var confirmed = confirm("Are you sure you want to edit this row?");
        if (confirmed) {
          // $.ajax({
          //   url: "category_donation_form.php",
          //   method: "POST",
          //   data: { id: id },
          //   success: function (response) {
          //     // Reload the table data
          //     table.ajax.reload();
          //   },
          //   error: function (xhr, status, error) {
          //     console.error(xhr.responseText);
          //   }
          // });
          window.location.href = 'category_donation_form.php?id=' + id;
        }
      });

      // Handle the Delete button click event
      $('#categoryTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        var confirmed = confirm("Are you sure you want to delete this row?");
        if (confirmed) {
          $.ajax({
            url: "category_donation_delete.php",
            method: "POST",
            data: { id: id },
            success: function (response) {
              // Reload the table data
              table.ajax.reload();
            },
            error: function (xhr, status, error) {
              console.error(xhr.responseText);
            }
          });
        }
      });
    });
  </script>
  <!-- Logout confirmation dialog -->
  <script>
    document.getElementById("logout-btn").addEventListener("click", function (event) {
      event.preventDefault();
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
      }
    });
  </script>

</body>
</html>