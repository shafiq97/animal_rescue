<?php
// Connect to the MySQL database
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "animal_rescue";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the submitted answers from the form
$question_ids = $_POST["question_id"];
$answers = $_POST["answer"];

// Insert the answers into the database
foreach ($question_ids as $question_id) {
    $answer = $answers[$question_id];
    $sql = "INSERT INTO answers (question_id, answer) VALUES ('$question_id', '$answer')";
    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Get the correct answers from the database
$sql = "SELECT * FROM questionnaire";
$result = $conn->query($sql);
$correct_answers = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $correct_answers[$row["id"]] = $row["answer"];
    }
}

// Close the database connection
$conn->close();

// Display the user's answers and the correct answers
echo "<h2>Your answers:</h2>";
echo "<ul>";
foreach ($question_ids as $question_id) {
    $answer = $answers[$question_id];
    echo "<li>Question $question_id: $answer</li>";
}
echo "</ul>";

echo "<h2>Correct answers:</h2>";
echo "<ul>";
foreach ($question_ids as $question_id) {
    $correct_answer = $correct_answers[$question_id];
    echo "<li>Question $question_id: $correct_answer</li>";
}
echo "</ul>";
?>
