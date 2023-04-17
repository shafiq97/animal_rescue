<!DOCTYPE html>
<html>
<head>
  <title>Answered Questions</title>
  <!-- Add Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <!-- Add DataTables CSS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

  <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
  <div class="container">
    <h1>Answered Questions</h1>
    <canvas id="chart"></canvas>
    <table id="answered-questions" class="table">
      <thead>
        <tr>
          <th>Question</th>
          <th>Answer</th>
        </tr>
      </thead>
      <tbody>
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

        // Get the list of answered questions from the database
        $sql    = "SELECT questionnaire.question, answers.answer, COUNT(*) AS count 
        FROM questionnaire 
        INNER JOIN answers ON questionnaire.id=answers.question_id 
        GROUP BY questionnaire.question, answers.answer 
        ORDER BY questionnaire.question
        ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            // Display each answered question
            echo '<tr>';
            echo '<td>' . $row["question"] . '</td>';
            echo '<td>' . $row["answer"] . '</td>';
            echo '</tr>';
          }
        } else {
          echo "No answered questions found.";
        }

        // Close the database connection
        $conn->close();
        ?>
      </tbody>
    </table>
  </div>
  <!-- Add Bootstrap JS -->
  <script>
    $(document).ready(function () {
      // Initialize DataTable
      var table = $('#answered-questions').DataTable({
        dom: 'Bfrtip',
        buttons: [
          'excelHtml5',
          'pdfHtml5'
        ]
      });

      // Extract data for first question
      var data = {
        labels: [],
        values: []
      };
      table.rows().every(function (rowIdx, tableLoop, rowLoop) {
        var dataRow = this.data();
        if (dataRow[0] === 'Why do you want to adopt a pet? (choose only one)') {
          data.labels.push(dataRow[1]);
          data.values.push(1);
        }
      });

      // Generate chart
      var ctx = document.getElementById('chart').getContext('2d');
      var chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            data: data.values,
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true
        }
      });
    });


  </script>
</body>
</html>