<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>WeatherWell</title>
  <link rel="stylesheet" href="bulma.css">
  <link rel="stylesheet" href="animate.css">
  <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>
</head>

<body>
  <nav class="navbar animate__animated animate__slideInDown" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a class="navbar-item" href="index.php">
        <img src="file.png" width="112" height="112">
      </a>

      <!-- Fix: Add correct attributes and remove extra <span> -->
      <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
      <div class="navbar-start">
        <a class="navbar-item" href="index.php">Home</a>
        <a class="navbar-item" href="dashboard.php">Dashboard</a>
        <a class="navbar-item" href="advice.php">Advice</a>
        <a class="navbar-item" href="healthtrack.php">Health Tracking</a>
        <a class="navbar-item" href="aboutus.php">About Us</a>

        <div class="navbar-item has-dropdown is-hoverable">
          <a class="navbar-link">More</a>
          <div class="navbar-dropdown">
            <a class="navbar-item" href="contact.php">Contact Us</a>
            <a class="navbar-item" href="report.php">Report an issue</a>
          </div>
        </div>
      </div>
    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <?php if (isLoggedIn()): ?>
            <p class="is-flex is-align-items-center">Logged in as <?= htmlspecialchars($_SESSION['username']) ?>.</p>
            <a class="button is-primary" href="logout.php">
              <strong>Sign Out</strong>
            </a>
          <?php else: ?>
            <p class="is-flex is-align-items-center">You are not logged in.</p>
            <a class="button is-primary" href="signup.php">
              <strong>Sign up</strong>
            </a>
            <a class="button is-light" href="login.php">Log in</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Select elements
      const burger = document.querySelector(".navbar-burger");
      const menu = document.querySelector("#navbarBasicExample");

      // Add event listener
      burger.addEventListener("click", function () {
        burger.classList.toggle("is-active");
        menu.classList.toggle("is-active");
      });
    });
  </script>
</body>

</html>
