<?php
session_start();
require "url.php";
require "database.php";
require "auth.php";
require "navbar.php";

if (!isLoggedIn()) {
    redirect("/login.php");
    exit;
}

$conn = getDB();
$username = $_SESSION['username'];

if (empty($username)) {
    echo "Error: No username found in session.";
    exit;
}

// Get user ID from the session username
$stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$user_id = $user_data['id'] ?? null;
$stmt->close();

if (!$user_id) {
    die("Error: User not found.");
}

// Fetch past scores
$stmt = $conn->prepare("SELECT week_start, environment_score, general_health_score, total_score FROM health_scores WHERE user_id = ? ORDER BY week_start DESC LIMIT 7");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$past_scores = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (!$past_scores) {
    $past_scores = [];
}

$date = date("Y-m-d");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limit values to reasonable ranges
    $exercise = min(max($_POST['exercise'] ?? 0, 0), 10);
    $hydration = min(max($_POST['hydration'] ?? 0, 0), 5);
    $sleep = min(max($_POST['sleep'] ?? 0, 0), 10);
    $nutrition = min(max($_POST['nutrition'] ?? 1, 1), 10);
    $air_quality = min(max($_POST['air_quality'] ?? 1, 1), 10);
    $sun_exposure = min(max($_POST['sun_exposure'] ?? 0, 0), 5);
    
    // Assign scores
    $environment_score = ($exercise * 1.5) + ($hydration * 1) + ($air_quality * 1);
    $general_health_score = ($sleep * 2) + ($nutrition * 2) + ($sun_exposure * 1);
    $total_score = $environment_score + $general_health_score;

    // Generate advice
    if ($environment_score < 10 && $general_health_score < 10) {
        $advice = "Your environmental and health habits need improvement. Consider increasing exercise, hydration, sleep, and nutrition.";
    } elseif ($environment_score < 10) {
        $advice = "Your environment score could use some work. Try getting more exercise, improving hydration, and managing air quality.";
    } elseif ($general_health_score < 10) {
        $advice = "Focus on improving your personal health: more sleep, better nutrition, and sun exposure would help.";
    } elseif ($total_score < 30) {
        $advice = "You're on the right track, but there's room for improvement in both your environment and health habits.";
    } else {
        $advice = "Great job! Your health and environmental habits are excellent.";
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO health_scores (user_id, week_start, environment_score, general_health_score, total_score, improvement_tips) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiis", $user_id, $date, $environment_score, $general_health_score, $total_score, $advice);
    $stmt->execute();
    $stmt->close();

    // Store in session to persist after redirect
    $_SESSION['advice'] = $advice;
    $_SESSION['total_score'] = $total_score;

    // Redirect to refresh the page
    header("Location: healthtrack.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Tracking</title>
    <link rel="stylesheet" href="bulma.css">
</head>
<body>
    <section class="hero is-primary has-background" style="background-image: url('map.jpg'); background-size: cover; background-position: center; padding: 5rem 0;">
        <div class="hero-body has-text-centered">
            <div class="container">
                <h1 class="title has-text-light">Personal Health Tracking</h1>
                <p class="subtitle has-text-light">Monitor your weekly health habits</p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="box">
                <form action="" method="post">
                    <div class="field"><label class="label">Exercise (0-10 hours per week)</label>
                        <input class="input" type="number" name="exercise" min="0" max="10" required>
                    </div>
                    <div class="field"><label class="label">Hydration (0-5 litres per day)</label>
                        <input class="input" type="number" name="hydration" step="0.1" min="0" max="5" required>
                    </div>
                    <div class="field"><label class="label">Sleep (0-10 hours per night)</label>
                        <input class="input" type="number" name="sleep" min="0" max="10" required>
                    </div>
                    <div class="field"><label class="label">Nutrition (1-10 scale)</label>
                        <input class="input" type="number" name="nutrition" min="1" max="10" required>
                    </div>
                    <div class="field"><label class="label">Air Quality Awareness (1-10 scale)</label>
                        <input class="input" type="number" name="air_quality" min="1" max="10" required>
                    </div>
                    <div class="field"><label class="label">Sun Exposure (0-5 hours per day)</label>
                        <input class="input" type="number" name="sun_exposure" min="0" max="5" required>
                    </div>
                    <div class="control">
                        <button class="button is-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>

            <?php if (isset($_SESSION['advice'])): ?>
                <div class="notification is-info">
                    <p><?= htmlspecialchars($_SESSION['advice']) ?> Total Score: <?= htmlspecialchars($_SESSION['total_score']) ?></p>
                </div>
                <?php 
                unset($_SESSION['advice']);
                unset($_SESSION['total_score']);
                ?>
            <?php endif; ?>

            <h2 class="title is-4">Previous Scores</h2>
            <table class="table is-striped is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Environment Score</th>
                        <th>General Health Score</th>
                        <th>Total Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($past_scores)): ?>
                        <?php foreach ($past_scores as $record): ?>
                            <tr>
                                <td><?= htmlspecialchars($record['week_start']) ?></td>
                                <td><?= htmlspecialchars($record['environment_score']) ?></td>
                                <td><?= htmlspecialchars($record['general_health_score']) ?></td>
                                <td><?= htmlspecialchars($record['total_score']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No previous scores available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php require "footer.php"; ?>
</body>
</html>
