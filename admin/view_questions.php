<?php
// Connect to the database
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "animal_rescue";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Retrieve the questions
$sql    = "SELECT * FROM questionnaire";
$result = mysqli_query($conn, $sql);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>View Questions</title>
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
    <h1>View Questions</h1>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Question</th>
          <th>Choice 1</th>
          <th>Choice 2</th>
          <th>Choice 3</th>
          <th>Choice 4</th>
          <th>Answer</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td>
              <?php echo $row['question']; ?>
            </td>
            <td>
              <?php echo $row['choice_1']; ?>
            </td>
            <td>
              <?php echo $row['choice_2']; ?>
            </td>
            <td>
              <?php echo $row['choice_3']; ?>
            </td>
            <td>
              <?php echo $row['choice_4']; ?>
            </td>
            <td>
              <?php echo $row['answer']; ?>
            </td>
            <td>
              <a class="btn btn-info" href="update_question.php?id=<?php echo $row['id']; ?>">Update</a>
              <a class="btn btn-danger" href="delete_question.php?id=<?php echo $row['id']; ?>">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <script>
    document.getElementById("logout-btn").addEventListener("click", function (event) {
      event.preventDefault();
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
      }
    });
  </script>
  <!-- Add Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>