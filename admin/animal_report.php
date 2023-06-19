<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Animal Report by Type</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
      <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
      <img class="navbar-brand" height="60px" src="../images/logo-header.png" alt="">
    <a class="navbar-brand" href="#">Admin Reporting</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    include('header.php');
    ?>
  </nav>
  <div class="container">
    <h1>Animal Count by Type</h1>
    <canvas id="chart"></canvas>
  </div>

  <?php
  // Connect to the MySQL database
  $servername = "localhost";
  $username   = "root";
  $password   = "";
  $dbname     = "animal_rescue";
  $conn       = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Get the count of each animal type
  $sql    = "SELECT type, COUNT(*) as count 
  FROM animals 
  GROUP BY type";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $data = [
      'labels' => [],
      'values' => []
    ];
    while ($row = $result->fetch_assoc()) {
      // Push type and count into the data array
      array_push($data['labels'], $row['type']);
      array_push($data['values'], $row['count']);
    }
  } else {
    echo "No animals found.";
  }

  // Close the database connection
  $conn->close();
  ?>

  <script>
    $(document).ready(function () {
      // Get the chart data from the PHP array
      var data = {
        labels: <?php echo json_encode($data['labels']); ?>,
        values: <?php echo json_encode($data['values']); ?>
      };

      // Generate chart
      var ctx = document.getElementById('chart').getContext('2d');
      var chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Count of Animals',
            data: data.values,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          scales: {
            x: {
              title: {
                display: true,
                text: 'Animal Type'
              }
            },
            y: {
              title: {
                display: true,
                text: 'Count'
              },
              beginAtZero: true
            }
          }
        }
      });

    });
  </script>
</body>
</html>