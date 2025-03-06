<?php
session_start();
require "url.php";
require "database.php";
require "auth.php";
require "navbar.php";

$conn = getDB();
?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About Us</title>
  <link rel="stylesheet" href="bulma.css">
  <style>
    body {
      background-color: #f5f5f5;
    }

    .hero-body {
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      padding: 5em 0;
    }

    .content-box {
      background-color: rgba(0, 0, 0, 0.6); ;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .team-image {
      border-radius: 50%;
      max-width: 150px;
      margin: 0 auto;
    }

    .team-card {
      margin: 1rem 0;
    }
  </style>
  </style>
</head>

<body>


<section class="hero is-primary has-background"
    style="background-image: url('map.jpg'); background-size: cover; background-position: center; padding: 5rem 0;">
    <div class="hero-head has-text-centered">
        <div class="container has-text-light content-box" style="max-width: 600px; margin: 0 auto;">
            <h1 class="title has-text-light" style="margin-bottom: 05.rem">About Us</h1>
            <p class="subtitle has-text-light">Who we are and what we stand for.</p>
        </div>
    </div>

    <div class="hero-body" style="padding-top: 1rem;">
        <div class="container">
            <div class="content-box has-text-light" 
                style="background-color: rgba(0, 0, 0, 0.6); padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3); color: white; max-width: 800px; margin: 0 auto;">
                <h2 class="title is-4 has-text-light">Our Mission</h2>
                <p>
                    At WeatherWell, our mission is to empower individuals with the knowledge and awareness they need to stay
                    prepared and safe, no matter the weather. We believe that informed decisions can make a big
                    difference in daily life, and hope that this website will help you stay informed and prepared. 
                </p>
                <br>

                <h2 class="title is-4 has-text-light">The Health and Advice Group</h2>
                <p>
                    This website has been created as a service of the Health Advice Group, a chairty with a team of people dedicated to serving the public with the necessary services to stay safe and healthy in different conditions. This website has been created to help pursue their goal of delivering quality services to protect the public, aiding against the environemnt. 
                </p>
            </div>
        </div>
    </div>
</section>



  <?php require "footer.php"; ?>
</body>

</html>
