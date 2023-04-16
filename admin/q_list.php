<!DOCTYPE html>
<html>
<head>
  <title>Answer Questionnaire</title>
  <!-- Add Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <h1>Answer Questionnaire</h1>
    <form method="post" action="submit_answers.php">
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

      // Get the list of questions from the database
      $sql    = "SELECT * FROM questionnaire";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          // Display each question and its choices
          echo '<div class="form-group">';
          echo '<h3>' . $row["question"] . '</h3>';
          echo '<input type="hidden" name="question_id[]" value="' . $row["id"] . '">';
          echo '<label class="radio-inline"><input type="radio" name="answer[' . $row["id"] . ']" value="1" required> ' . $row["choice_1"] . '</label><br>';
          echo '<label class="radio-inline"><input type="radio" name="answer[' . $row["id"] . ']" value="2" required> ' . $row["choice_2"] . '</label><br>';
          echo '<label class="radio-inline"><input type="radio" name="answer[' . $row["id"] . ']" value="3" required> ' . $row["choice_3"] . '</label><br>';
          echo '<label class="radio-inline"><input type="radio" name="answer[' . $row["id"] . ']" value="4" required> ' . $row["choice_4"] . '</label><br>';
          echo '</div>';
        }
      } else {
        echo "No questions found.";
      }

      // Close the database connection
      $conn->close();
      ?>
      <button type="submit" class="btn btn-primary">Submit Answers</button>
    </form>
  </div>
  <!-- Add Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WOEAkfTelpYjyJQzeE/GCbPbXVoXNumE" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
</body>
</html>