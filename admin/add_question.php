<?php

// Connect to the database
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'animal_rescue';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the question data from the form
$question = $_POST['question'];
$choice_1 = $_POST['choice_1'];
$choice_2 = $_POST['choice_2'];
$choice_3 = $_POST['choice_3'];
$choice_4 = $_POST['choice_4'];
$answer = $_POST['answer'];

// Insert the question data into the database
$sql = "INSERT INTO questionnaire (question, choice_1, choice_2, choice_3, choice_4, answer) VALUES ('$question', '$choice_1', '$choice_2', '$choice_3', '$choice_4', '$answer')";

if (mysqli_query($conn, $sql)) {
    echo "Question added successfully";
    header("location: view_questions.php");
} else {
    echo "Error adding question: " . mysqli_error($conn);
}

mysqli_close($conn);

?>
