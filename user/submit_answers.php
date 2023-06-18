<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
    <div class="container">
        <?php
        // Connect to the MySQL database
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
        $servername = "localhost";
        $username   = "root";
        $password   = "";
        $dbname     = "animal_rescue";
        $conn       = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the submitted answers from the form
        $question_ids = $_POST["question_id"];
        $answers      = isset($_POST["answer"]) ? $_POST["answer"] : [];

        // Insert the answers into the database
        foreach ($question_ids as $question_id) {
            // Check if an answer exists for this question
            if (isset($answers[$question_id])) {
                $answer = $answers[$question_id];
                $sql    = "INSERT INTO answers (question_id, answer) VALUES ('$question_id', '$answer')";
                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }

        // Get the correct answers from the database
        $sql             = "SELECT * FROM questionnaire";
        $result          = $conn->query($sql);
        $correct_answers = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $correct_answers[$row["id"]] = $row["answer"];
            }
        }

        // Close the database connection
        $conn->close();
        ?>
        <h2 class="mt-3">Your answers:</h2>
        <ul class="list-unstyled">
            <?php foreach ($question_ids as $question_id): ?>
                <?php if (isset($answers[$question_id])): ?>
                    <li>Question
                        <?= $question_id ?>:
                        <?= $answers[$question_id] ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNVQ8ew"
            crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
    </div>
</body>
</html>