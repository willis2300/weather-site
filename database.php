<?php

function getDB() {
    $db_host = "localhost";
    $db_name = "hagmock";
    $db_user = "localhost";
    $db_pass = "password";

    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    if (mysqli_connect_error()) {
        echo mysqli_connect_error();
        exit;
    }

    return $conn;
}