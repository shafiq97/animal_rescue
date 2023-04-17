<?php
// Connect to the database
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "animal_rescue";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Get the question ID from the URL
$question_id = $_GET['id'];

// Retrieve the question data from the database
$sql      = "SELECT * FROM questionnaire WHERE id = $question_id";
$result   = mysqli_query($conn, $sql);
$question = mysqli_fetch_assoc($result);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the updated data from the form
  $question_text = $_POST['question'];
  $choice_1      = $_POST['choice_1'];
  $choice_2      = $_POST['choice_2'];
  $choice_3      = $_POST['choice_3'];
  $choice_4      = $_POST['choice_4'];
  $answer        = $_POST['answer'];

  // Update the question data in the database
  $sql = "UPDATE questionnaire SET question = '$question_text', choice_1 = '$choice_1', choice_2 = '$choice_2', choice_3 = '$choice_3', choice_4 = '$choice_4', answer = '$answer' WHERE id = $question_id";
  mysqli_query($conn, $sql);

  // Redirect to the view_questions.php page
  header("Location: view_questions.php");
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Update Question</title>
  <!-- Add Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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
    <?php include 'header.php' ?>
    <h1>Update Question</h1>
    <form method="post" action="update_question.php?id=<?php echo $question_id; ?>">
      <div class="form-group">
        <label>Question:</label>
        <input type="text" class="form-control" name="question" value="<?php echo $question['question']; ?>" required>
      </div>
      <div class="form-group">
        <label>Choice 1:</label>
        <input type="text" class="form-control" name="choice_1" value="<?php echo $question['choice_1']; ?>" required>
      </div>
      <div class="form-group">
        <label>Choice 2:</label>
        <input type="text" class="form-control" name="choice_2" value="<?php echo $question['choice_2']; ?>" required>
      </div>
      <div class="form-group">
        <label>Choice 3:</label>
        <input type="text" class="form-control" name="choice_3" value="<?php echo $question['choice_3']; ?>" required>
      </div>
      <div class="form-group">
        <label>Choice 4:</label>
        <input type="text" class="form-control" name="choice_4" value="<?php echo $question['choice_4']; ?>" required>
      </div>
      <div class="form-group">
        <label>Answer:</label>
        <input type="text" class="form-control" name="answer" value="<?php echo $question['answer']; ?>" required>
      </div>
      <button type="submit" class="btn btn-primary">Update Question</button>
    </form>
  </div>
  <!-- Add Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>
</html>