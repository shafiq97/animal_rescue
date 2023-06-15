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
$sql = "SELECT *, animals.name as animal_name FROM medical_funds 
inner join animals on medical_funds.animal_id = animals.id
inner join users on medical_funds.user_id = users.id";

if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
  $startDate = mysqli_real_escape_string($conn, $_GET['startDate']);
  $endDate = mysqli_real_escape_string($conn, $_GET['endDate']);

  // modify the SQL query to filter by the date range
  $sql .= " WHERE date_approval_status BETWEEN '$startDate' AND '$endDate'";
}

$result = mysqli_query($conn, $sql);

// close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Medical Fund</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
  <style>
    .amount-approved {
      color: red;
    }

    .amount-pending {
      color: green;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Medical Fund List</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    include('header.php');
    ?>
  </nav>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <h2>Medical Fund List</h2>

        <!-- Add this form for date filtering -->
        <form method="GET" class="mb-3" action="transcation_history.php">
          <label for="startDate">Start Date:</label>
          <input type="date" id="startDate" name="startDate">

          <label for="endDate">End Date:</label>
          <input type="date" id="endDate" name="endDate">

          <button type="submit">Filter</button>
        </form>

        <?php if (mysqli_num_rows($result) > 0) : ?>
          <table id="donationTable" class="table table-striped table-bordered mt-4" style="width:100%">
            <thead>
              <tr>
                <th scope="col">User</th>
                <th scope="col">Amount Donated</th>
                <th scope="col">Pet Name</th>
                <th scope="col">Date</th>
                <!-- <th scope="col">Category</th> -->
                <!-- <th scope="col">Admin Approval</th> -->
                <th scope="col">Receipt</th>
                <th scope="col">Auto Deduction</th>
                <!-- <th scope="col">Action</th> -->
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                  <td>
                    <?php echo $row['username']; ?>
                  </td>
                  <td>
                    <span class="amount-pending"> + RM
                      <?php echo $row['total_amount']; ?>
                    </span>
                  </td>
                  <td>
                    <?php echo $row['animal_name']; ?>
                  </td>
                  <td>
                    <?php echo $row['receive_date']; ?>
                  </td>
                  <td><a href="../user/<?php echo $row['receipt_path']; ?>" target="_blank">View</a></td>
                  <td>Auto deduct here</td>
                  <!-- <td>
                    <?php if ($row['admin_approval'] != "approved") : ?>
                      <a class="btn btn-primary" href="update_donation_status.php?id=<?php echo $row['donation_id']; ?>">Approve</a>
                    <?php endif; ?>
                  </td> -->
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else : ?>
          <p>No Medical Funds Donation has been made</p>
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
    $(document).ready(function() {
      $('#donationTable').DataTable();
      $('#donationTable2').DataTable();
    });
  </script>
  <script>
    document.getElementById("logout-btn").addEventListener("click", function(event) {
      event.preventDefault();
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
      }
    });
  </script>

</body>

</html>