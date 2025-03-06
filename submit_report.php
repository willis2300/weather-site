<?php
session_start();
require "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = getDB();

    $name = trim($_POST["name"]);
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);
    $agreed_to_terms = isset($_POST["agreed_to_terms"]) ? 1 : 0;

    if (empty($name) || empty($username) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO report_issues (name, username, email, subject, message, agreed_to_terms) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $name, $username, $email, $subject, $message, $agreed_to_terms);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Your report has been submitted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
