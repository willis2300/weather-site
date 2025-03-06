<?php
session_start();
require 'url.php';
require 'auth.php';
require 'database.php';

if (isLoggedIn()) {
    redirect("/");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['signup'])) {
        redirect("/signup.php");
        exit;
    }

    $conn = getDB();

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        echo mysqli_error($conn);
    } else {
        mysqli_stmt_bind_param($stmt, "s", $username);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['is_logged_in'] = true;
                $_SESSION['username'] = $user['username'];
                if ($user['is_admin']) {
                    $_SESSION['admin_logged_in'] = true;
                }
                redirect("/");
                exit;
            } else {
                $error = "Incorrect username or password.";
            }
        } else {
            echo mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log In</title>
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


    <section class="section">
        <div class="container has-text-centered ">
            <img src="file.png" alt="Logo" class="hero-logo">
            <h2 class="title has-text-white">Log In</h2>

            <form method="post">
                <div class="field" >
                    <label class="label has-text-light" for="username">Username</label>
                    <div class="control">
                        <input class="input" type="text" name="username" id="username" maxlength="20" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label has-text-light" for="password">Password</label>
                    <div class="control">
                        <input class="input" type="password" name="password" id="password" maxlengh="100" required>
                    </div>
                </div>

                <div class="field is-grouped is-grouped-centered">
                    <div class="control">
                        <button class="button is-primary" type="submit">Log In</button>
                    </div>
                    <div class="control">
                        <button class="button is-primary" type="submit" name="signup">Don't have an account? Sign Up</button>
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
