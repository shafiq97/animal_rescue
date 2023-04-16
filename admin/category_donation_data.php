<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "animal_rescue";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Query the database
    $sql = "SELECT id, name FROM category_donation";
    $result = $conn->query($sql);
    
    // Convert the result to JSON format
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
    
    // Close the connection
    $conn->close();
?>
