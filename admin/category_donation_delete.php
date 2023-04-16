<?php
// Check if the request method is DELETE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the ID of the row to be deleted
  $id = $_POST["id"];

  // Connect to the database
  $servername = "localhost";
  $username   = "root";
  $password   = "";
  $dbname     = "animal_rescue";

  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare and execute the DELETE statement
  $stmt = $conn->prepare("DELETE FROM category_donation WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  // Check if the statement was executed successfully
  if ($stmt->affected_rows > 0) {
    // Return a success response
    http_response_code(204); // No Content
  } else {
    // Return an error response
    http_response_code(400); // Bad Request
    echo "Error: " . $stmt->error;
    echo $stmt;
  }

  // Close the statement and connection
  $stmt->close();
  $conn->close();
} else {
  // Return an error response
  http_response_code(405); // Method Not Allowed
  echo "Error: Invalid request method.";
}
?>