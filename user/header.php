<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

?>

<style>
  .profile-image {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
  }

  .my-dropdown-menu {
    right: 0;
    left: auto;
  }
</style>

<div class="collapse navbar-collapse" id="navbarNav">
  <?php
  if (isset($_SESSION['loggedin'])) {
  ?>
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="dashboard.php">Dashboard</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="questionnaire.php">Questionnaire</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Settings</a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto d-flex align-items-center">
      <li class="nav-item mr-3">
        <a href="donate.php" onclick="return confirm('Are you sure?')" class='btn btn-primary'>Donate</a>
      </li>
      <li class="nav-item mr-3">
        <a href="medical_dashboard.php" onclick="return confirm('Are you sure?')" class='btn btn-warning'>Medical fund</a>
      </li>
      <li class="nav-item dropdown mr-3">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img class="profile-image" src="<?php echo $_SESSION['profile_picture'] ?>" alt="">
        </a>
        <div class="dropdown-menu my-dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="profile.php">View Profile</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="manage_animal.php">Manage Animal</a>
          <a class="dropdown-item" href="register_animal.php">Register Animal</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="donation_list.php">Donation List</a>
          <a id="logout-btn" href="logout.php" class="dropdown-item my-2 my-sm-0">Logout</a>
        </div>
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
      <li class="nav-item mr-3">
        <a href="user/login.php" onclick="return confirm('Are you sure?')" class='btn btn-light'>Login</a>
      </li>
    </ul>
  <?php
  }
  ?>
</div>
<script>
  document.getElementById("logout-btn").addEventListener("click", function(event) {
    event.preventDefault();
    if (confirm("Are you sure you want to logout?")) {
      window.location.href = "logout.php";
    }
  });
</script>