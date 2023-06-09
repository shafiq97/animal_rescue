<!DOCTYPE html>
<html>

<head>
  <title>Answer Questionnaire</title>
  <!-- Add Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <img class="navbar-brand" height="60px" src="../images/logo-header.png" alt="">
    <a class="navbar-brand" href="#">Questionnaire</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    include('header.php');
    ?>
  </nav>
  <div class="container">
    <h1>Answer Questionnaire</h1>
    <div class="progress  mb-3" style="position: sticky; top: 80px; z-index:99999">
      <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
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
          echo '<div class="card mb-3 shadow">';
          echo '<div class="card-body">';
          echo '<h3>' . $row["question"] . '</h3>';
          echo '<div class="form-group">';
          echo '<input type="hidden" name="question_id[]" value="' . $row["id"] . '">';

          $allChoicesAreNull = true;
          for ($i = 1; $i <= 7; $i++) {
            if (!is_null($row["choice_" . $i])) {
              echo '<label class="radio-inline"><input type="radio" name="answer[' . $row["id"] . ']" value="' . $row["choice_" . $i] . '" required> ' . $row["choice_" . $i] . '</label><br>';
              $allChoicesAreNull = false;
            }
          }

          // If all choices are null, show an input box
          if ($allChoicesAreNull) {
            echo '<input type="text" name="open_answer[' . $row["id"] . ']" class="form-control" placeholder="Your answer" required>';
          }

          echo '</div>';
          echo '</div>'; // Close card-body
          echo '</div>'; // Close card
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
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WOEAkfTelpYjyJQzeE/GCbPbXVoXNumE" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script>
    $(document).ready(function() {
      // Calculate the total number of questions
      var totalQuestions = $('input[type="radio"]').length / 7;

      // Update the progress bar whenever a radio button is selected
      $('input[type="radio"]').change(function() {
        var answeredQuestions = $('input[type="radio"]:checked').length;
        var progress = (answeredQuestions / totalQuestions) * 100;

        $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress);
      });
    });
  </script>
</body>

</html>