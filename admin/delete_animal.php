<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
// Check if the question ID is set in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Connect to the database
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "animal_rescue";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check the connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL query to delete the question
    $question_id = $_GET['id'];
    $sql = "DELETE FROM animals WHERE id = ?";
    
    // Prepare a statement and bind the parameters
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        $param_id = $question_id;
        
        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the view_questions.php page after successful deletion
            header("location: dashboard.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
    
    // Close the statement
    mysqli_stmt_close($stmt);
    
    // Close the database connection
    mysqli_close($conn);
} else {
    // Redirect to the view_questions.php page if the question ID is not set
    header("location: view_questions.php");
    exit;
}
