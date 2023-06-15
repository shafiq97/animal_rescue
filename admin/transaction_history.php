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

$sql = "SELECT *, animals.name as animal_name, animals.id as animal_id, users.username as username, medical_funds.total_amount as donation FROM medical_funds 
inner join animals on medical_funds.animal_id = animals.id
inner join users on medical_funds.user_id = users.id
ORDER BY medical_funds.id";


if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
  $startDate = mysqli_real_escape_string($conn, $_GET['startDate']);
  $endDate = mysqli_real_escape_string($conn, $_GET['endDate']);

  // modify the SQL query to filter by the date range
  $sql = "SELECT *, animals.name as animal_name, animals.id as animal_id, users.username as username, SUM(medical_funds.total_amount) as total_donation FROM medical_funds 
  inner join animals on medical_funds.animal_id = animals.id
  inner join users on medical_funds.user_id = users.id
  WHERE DATE(receive_date) BETWEEN '$startDate' AND '$endDate'
  GROUP BY animals.id, users.id";
}


$result = mysqli_query($conn, $sql);

// Query for the total donations
$sql_total_donations = "SELECT SUM(total_amount) as total_donations FROM medical_funds";
$result_total_donations = mysqli_query($conn, $sql_total_donations);
$row_total_donations = mysqli_fetch_assoc($result_total_donations);
$total_donations = $row_total_donations['total_donations'];

// Query for the total auto deductions (i.e., medical fees)
$sql_total_medical_fees = "SELECT SUM(medical_adopt_fee) as total_medical_fees FROM animals WHERE id IN (SELECT DISTINCT animal_id FROM medical_funds)";
$result_total_medical_fees = mysqli_query($conn, $sql_total_medical_fees);
$row_total_medical_fees = mysqli_fetch_assoc($result_total_medical_fees);
$total_medical_fees = $row_total_medical_fees['total_medical_fees'];

$balance = $total_donations - $total_medical_fees;


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
        <h1 class="nav-link">Balance: RM<?php echo number_format($balance, 2); ?></h1>
        <!-- Add this form for date filtering -->
        <form method="GET" class="mb-3" action="transaction_history.php">
          <label for="startDate">Start Date:</label>
          <input type="date" id="startDate" name="startDate" required>

          <label for="endDate">End Date:</label>
          <input type="date" id="endDate" name="endDate" required>

          <button class="btn btn-primary" type="submit">Filter</button>
          <button class="btn btn-secondary" type="reset" value="Reset" onclick="window.location.href='transaction_history.php'">Reset</button>
        </form>

        <?php if (mysqli_num_rows($result) > 0) : ?>
          <table id="donationTable" class="table table-striped table-bordered mt-4" style="width:100%">
            <thead>
              <tr>
                <th scope="col">User</th>
                <th scope="col">Amount Donated</th>
                <th scope="col">Pet Name</th>
                <th scope="col">Date</th>
                <th scope="col">Receipt</th>
                <th scope="col">Medical Adopt Fee</th>
                <th scope="col">Auto Deduction</th>
              </tr>
            </thead>
            <tbody><?php
                    // Define an array to keep track of the cumulative donation per animal and the user who fulfills the fee
                    $cumulative_donations = [];

                    // Iterate over the result set
                    while ($row = mysqli_fetch_assoc($result)) {
                      // Add current donation to cumulative total
                      $animal_id = $row['animal_id'];
                      if (!isset($cumulative_donations[$animal_id])) {
                        $cumulative_donations[$animal_id] = ['total' => 0, 'user_id' => null];
                      }
                      $cumulative_donations[$animal_id]['total'] += $row['total_amount'];

                      // Check if the cumulative total just reached or exceeded the medical adopt fee
                      if ($cumulative_donations[$animal_id]['total'] >= $row['medical_adopt_fee'] && $cumulative_donations[$animal_id]['user_id'] === null) {
                        // Record the user id and the date of donation
                        $cumulative_donations[$animal_id]['user_id'] = $row['user_id'];
                        $cumulative_donations[$animal_id]['date'] = $row['receive_date'];
                      }

                      // Print row, check if the user is the one who made the donation that fulfills the fee
                      echo "<tr>";
                      echo "<td>" . $row['username'] . "</td>";
                      echo "<td><span class='amount-pending'> + RM" . $row['total_amount'] . "</span></td>";
                      echo "<td>" . $row['animal_name'] . "</td>";
                      echo "<td>" . $row['receive_date'] . "</td>";
                      echo "<td><a href='../user/" . $row['receipt_path'] . "' target='_blank'>View</a></td>";
                      echo "<td>" . $row['medical_adopt_fee'] . "</td>";
                      echo "<td>";
                      if ($cumulative_donations[$animal_id]['user_id'] === $row['user_id'] && $cumulative_donations[$animal_id]['date'] === $row['receive_date']) {
                        echo "<span class='amount-approved'> - RM" . $row['medical_adopt_fee'] . "</span>";
                      } else {
                        echo '-';
                      }
                      echo "</td>";
                      echo "</tr>";
                    }
                    ?>
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

</body>

</html>