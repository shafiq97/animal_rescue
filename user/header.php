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
        <a class="nav-link" href="register_animal.php">Register Animal</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="dashboard.php">Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="search.php">Search</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="donation_list.php">Donation List</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="questionnaire.php">Questionnaire</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Settings</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item mr-3">
        <a href="donate.php" onclick="return confirm('Are you sure?')" class='btn btn-primary'>Donate</a>
      </li>
      <li class="nav-item mr-3">
        <a href="medical_dashboard.php" onclick="return confirm('Are you sure?')" class='btn btn-warning'>Medical fund</a>
      </li>
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