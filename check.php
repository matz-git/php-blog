<?php
session_start();

// LOGIN
if (isset($_POST["user"]) && !isset($_SESSION["user"])) {
    $users = [
        "user" => '$2y$10$N93MtL09zd074Qf8xne8JOZv6viJz5/taukfUC2wJeReMdoO38EoO',
    ];

    if (isset($users[$_POST["user"]])) {
        if (password_verify($_POST["password"], $users[$_POST["user"]])) {
            $_SESSION["user"] = $_POST["user"];
        }
    }

    if (!isset($_SESSION["user"])) {$failed = true;}
}

// REDIRECT USER TO HOME PAGE IF SIGNED IN
if (isset($_SESSION["user"])) {
    $_SESSION["pw"] = $_POST["password"];

    $current_time = time();
    $year = date('Y', $current_time);
    $month = date('n');
    $location_str = "Location: index.php?y=" . $year . "&m=" . $month;
    header($location_str); 
    exit();
}