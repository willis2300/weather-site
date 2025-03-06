<?php
session_start();
require "url.php";
require "database.php";
require "auth.php";
require "navbar.php";

// Get location from session, default to "Gateshead"
$location = isset($_SESSION["location"]) ? $_SESSION["location"] : "Gateshead";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Advice</title>
    <link rel="stylesheet" href="bulma.css">
    <link rel="stylesheet" href="animate.css">
    <style>
        .tab-content {
            display: none;
        }

        .tab-content.is-active {
            display: block;
        }

        .card {
            margin: 1rem 0;
        }

        .hero {
            background: url('map.jpg') no-repeat center center;
            background-size: cover;
            min-height: 250px;
        }
    </style>
</head>

<body>
    <section class="hero is-primary has-background"
        style="background-image: url('map.jpg'); background-size: cover; background-position: center;">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title has-text-light animate__animated animate__fadeInDown">Advice Dashboard</h1>
                <p class="subtitle has-text-light animate__animated animate__fadeIn" id="location-title">
                    Advice for <?= htmlspecialchars($location) ?>
                </p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <!-- Tabs -->
            <div class="tabs is-centered is-boxed is-medium">
                <ul>
                    <?php
                    $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
                    foreach ($days as $index => $day): ?>
                        <li class="<?= $index === 0 ? 'is-active' : '' ?>" data-tab="<?= $day ?>">
                            <a><?= ucfirst($day) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php foreach ($days as $index => $day): ?>
                <div class="tab-content <?= $index === 0 ? 'is-active' : '' ?>" id="<?= $day ?>">
                    <div class="columns is-multiline">
                        <?php foreach (["morning", "afternoon", "evening"] as $time): ?>
                            <div class="column is-one-third">
                                <div class="card">
                                    <div class="card-content">
                                        <p class="title"><?= ucfirst($time) ?></p>
                                        <p id="<?= $day . '-' . $time ?>-advice">Loading...</p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <script>
        const locationName = "<?= htmlspecialchars($location) ?>";
    </script>
    <script src="advice.js?ver=<?php echo time(); ?>"></script>


    <?php require "footer.php"; ?>
</body>

</html>