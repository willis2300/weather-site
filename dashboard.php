<?php
session_start();
require "url.php";
require "database.php";
require "auth.php";
require "navbar.php";

$conn = getDB();

if (isset($_GET["location"])) {
    $_SESSION["location"] = $_GET["location"];
}

// Retrieve location from session (default to Gateshead)
$location = isset($_SESSION["location"]) ? $_SESSION["location"] : "Gateshead";

$days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link rel="stylesheet" href="bulma.css">
  <link rel="stylesheet" href="animate.css">
  <style>
    .tab-content { display: none; }
    .tab-content.is-active { display: block; }
    .card { margin: 1rem 0; }
  </style>
</head>
<body>
  <section class="hero is-primary has-background" style="background-image: url('map.jpg'); background-size: cover; background-position: center;">
    <div class="hero-body">
      <div class="container has-text-centered">
        <h1 class="title has-text-light animate__animated animate__fadeInDown">Environmental Dashboard</h1>
        <p class="subtitle has-text-light animate__animated animate__fadeIn" id="location-title">
          Weather information for <?= htmlspecialchars($location) ?>
        </p>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="tabs is-centered is-boxed is-medium">
        <ul>
          <?php foreach ($days as $index => $day): ?>
            <li class="<?= $index === 0 ? 'is-active' : '' ?>" data-tab="<?= $day ?>"><a><?= ucfirst($day) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <?php foreach ($days as $index => $day): ?>
        <div class="tab-content <?= $index === 0 ? 'is-active' : '' ?>" id="<?= $day ?>">
          <div class="columns is-multiline">
            <?php foreach (["Morning", "Afternoon", "Evening"] as $time): ?>
              <div class="column is-one-third">
                <div class="card">
                  <div class="card-content">
                    <p class="title"><?= $time ?></p>
                    <p>Temperature: <span id="<?= $day . '-' . strtolower($time) ?>-temp">-</span>°C</p>
                    <p>Humidity: <span id="<?= $day . '-' . strtolower($time) ?>-humidity">-</span>%</p>
                    <p>Wind Speed: <span id="<?= $day . '-' . strtolower($time) ?>-wind">-</span> km/h</p>
                    <p>UV Index: <span id="<?= $day . '-' . strtolower($time) ?>-uv">-</span></p>
                    <p>Visibility: <span id="<?= $day . '-' . strtolower($time) ?>-visibility">-</span> km</p>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <div class="field has-text-centered">
    <a class="button is-primary is-centered" href="advice.php"><strong>Get Advice</strong></a>
  </div>

  <script>
    const locationName = "<?= htmlspecialchars($location) ?>";
  </script>
  <script src="dashboard.js"></script>
  <?php require "footer.php"; ?>
</body>
</html>
