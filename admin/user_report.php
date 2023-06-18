<?php
$host     = "localhost";
$username = "root";
$password = "";
$dbname   = "animal_rescue";

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Retrieve users from the database
$query  = "SELECT id, username, password, email, user_city, user_country, user_nophone, user_gender, profile_picture, bank_acc, name, user_state, role FROM users";
$result = mysqli_query($conn, $query);

// Delete user if ID is passed in URL
if (isset($_GET['delete_id'])) {
  $id    = $_GET['delete_id'];
  $query = "DELETE FROM users WHERE id=$id";
  mysqli_query($conn, $query);
  header("Location: user_settings.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>User List</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
      <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="#">User Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    include('header.php');
    ?>
  </nav>
  <div class="container mt-5">
    <h1 class="text-center mb-4">User List</h1>
    <?php
    // Query to get total number of users
    $query_count  = "SELECT COUNT(*) as total FROM users WHERE role = 'user'";
    $result_count = mysqli_query($conn, $query_count);
    $row_count    = mysqli_fetch_assoc($result_count);
    $total_users  = $row_count['total'];

    // Display total users
    echo "<h3>Total users: $total_users</h3>";
    ?>
    <table id="user-table" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th>Username</th>
          <th>Email</th>
          <th>City</th>
          <th>Country</th>
          <th>Phone Number</th>
          <th>Gender</th>
          <th>Profile Picture</th>
          <th>Bank Account</th>
          <th>Name</th>
          <th>State</th>
          <th>Role</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query  = "SELECT * FROM users where role = 'user'";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . $row['username'] . "</td>";
          echo "<td>" . $row['email'] . "</td>";
          echo "<td>" . $row['user_city'] . "</td>";
          echo "<td>" . $row['user_country'] . "</td>";
          echo "<td>" . $row['user_nophone'] . "</td>";
          echo "<td>" . $row['user_gender'] . "</td>";
          echo "<td><img src='../user/" . $row['profile_picture'] . "' alt='profile-picture' width='100'></td>";
          echo "<td>" . $row['bank_acc'] . "</td>";
          echo "<td>" . $row['name'] . "</td>";
          echo "<td>" . $row['user_state'] . "</td>";
          echo "<td>" . $row['role'] . "</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <!-- Correct order -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#user-table').DataTable();
    });
  </script>
</body>
</html>