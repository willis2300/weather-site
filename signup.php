<?php
session_start();
require "url.php";
require "auth.php";
require "database.php";

if (isLoggedIn()) {
    redirect("/");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        redirect("/login.php");
        exit;
    }

    $conn = getDB();

    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password2'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            echo "Error preparing statement: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $error = "Username is taken.";
            } elseif ($password !== $password2) {
                $error = "Passwords do not match.";
            } else {
                $sql = "INSERT INTO user (username, password) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $sql);

                if ($stmt === false) {
                    echo "Error preparing statement: " . mysqli_error($conn);
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "ss", $username, $hash);
                    $result = mysqli_stmt_execute($stmt);

                    if ($result) {
                        echo "User registered successfully.";
                        redirect("/login.php");
                        exit;
                    } else {
                        echo "Error executing statement: " . mysqli_stmt_error($stmt);
                    }
                }
            }
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="bulma.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('map.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
        }
        .hero-logo {
            display: block;
            margin: 0 auto -3rem;
            max-width: 300px;
        }
        .section {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 3rem 1.5rem;
            border-radius: 8px;
            width: 50%;
            max-width: 500px;
        }
        .back-button {
    position: fixed;
    bottom: 20px;
    left: 20px;
    padding: 10px 15px;
    font-size: 1rem;
    border-radius: 5px;
    text-decoration: none;
}
        @media (max-width: 768px) {
  .section {
    width: 90% !important;
    padding: 1.5rem !important;
  }
}
    </style>
</head>
<body>

    <?php include "header.php"; ?>

    <section class="section">
        <div class="container has-text-centered">
            <img src="file.png" alt="Logo" class="hero-logo">
            <h2 class="title has-text-white">Sign Up</h2>

            <form method="post">
                <div class="field">
                    <label class="label has-text-light" for="username">Username</label>
                    <div class="control">
                        <input class="input" type="text" name="username" id="username" required maxlength="20">
                    </div>
                </div>

                <div class="field">
                    <label class="label has-text-light" for="password">Password</label>
                    <div class="control">
                        <input class="input" type="password" name="password" id="password" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="password2">Confirm Password</label>
                    <div class="control">
                        <input class="input" type="password" name="password2" id="password2" required maxlength="300">
                    </div>
                </div>

                <div class="field is-grouped is-grouped-centered">
                    <div class="control">
                        <button class="button is-primary" type="submit">Sign Up</button>
                    </div>
                    <div class="control">
                        <button class="button is-primary" type="submit" name="login">Already have an account? Log In</button>
                    </div>
                </div>
            </form>

            <?php if (!empty($error)): ?>
                <p class="has-text-danger"><?= $error; ?></p>
            <?php endif; ?>
        </div>
    </section>
    <a href="index.php" class="back-button button is-light">← Back</a>

</body>
</html>
