<!DOCTYPE html>
<html>
<head>
  <title>Donate</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
      <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
      <img class="navbar-brand" height="60px" src="../images/logo-header.png" alt="">
    <a class="navbar-brand" href="#">Donate</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    include('header.php');
    ?>
  </nav>

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <h2>Make a Donation</h2>
        <form method="POST" action="process_donation.php" enctype="multipart/form-data">
          <!-- <input type="hidden" name='animal_id' value="<?php echo $_GET['id'] ?>"> -->
          <div class="form-group">
            <img src="../images/QRCODE.png" alt="QRCODE">
            <h4>Maybank</h4>
            <h4>155393382874</h4>
          </div>
          <div class="form-group">
            <label for="amount">Amount(RM):</label>
            <input type="number" class="form-control" id="amount" name="amount" required>
          </div>
          <div class="form-group">
            <label for="category">Category:</label>
            <select class="form-control" id="category" name="category">
              <?php
              // Connect to database
              $conn = mysqli_connect("localhost", "root", "", "animal_rescue");

              // Check connection
              if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
              }

              // Query the category_donation table
              $sql    = "SELECT * FROM category_donation";
              $result = mysqli_query($conn, $sql);

              // Generate the options
              if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                  echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
                }
              }

              // Close connection
              mysqli_close($conn);
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="receipt">Receipt:</label>
            <input type="file" class="form-control-file" id="receipt" name="receipt">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <!-- Logout confirmation dialog -->
  <script>
    document.getElementById("logout-btn").addEventListener("click", function (event) {
      event.preventDefault();
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
      }
    });
  </script>
</body>
</html>