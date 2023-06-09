<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

?>

<div class="collapse navbar-collapse" id="navbarNav">
  <?php
  if (isset($_SESSION['loggedin'])) {
    ?>
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="dashboard.php">View Animals</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="category_donation.php">Category donation</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="donation_list.php">Donation List</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Questionnaire
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="questionnaire.php">Add Question</a>
          <!-- <a class="dropdown-item" href="view_answer.php">View User Answer</a> -->
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Report
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="medical_fund_report.php">Medical Fund</a>
          <a class="dropdown-item" href="donation_report.php">Donation</a>
          <a class="dropdown-item" href="animal_report.php">Animal</a>
          <a class="dropdown-item" href="user_report.php">User Report</a>
          <!-- <a class="dropdown-item" href="view_answer.php">View User Answer</a> -->
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="user_settings.php">User Settings</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="transaction_history.php">Transactions History</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <button id="logout-btn" class="btn btn-outline-danger my-2 my-sm-0" type="submit">Logout</button>
      </li>
    </ul>
    <?php
  } else {
    ?>
    <li class="nav-item">
      <a class="nav-link" href="#">Welcome!</a>
    </li>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item mr-3">
        <a href="user/login.php" onclick="return confirm('Are you sure?')" class='btn btn-primary'>Donate</a>
      </li>
    </ul>
    <?php
  }
  ?>
</div>

<script>
  document.getElementById("logout-btn").addEventListener("click", function (event) {
    event.preventDefault();
    if (confirm("Are you sure you want to logout?")) {
      window.location.href = "logout.php";
    }
  });
</script>