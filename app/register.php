<?php
require('private/database.php');
include('private/endpoints.php');

global $database;
$username = $_POST["username"];
$password = $_POST["password"];

if (strlen($password) < 8) {
    http_response_code(400);
    die(json_encode(array("error" => "Password must be at least 8 characters long!")));
}

$stmt = pg_prepare($database, "query_user_$username", "select * from users where username = $1");
$result = pg_execute($database, "query_user_$username", array($username));

if (pg_num_rows($result) > 0) {
    http_response_code(400);
    die(json_encode(array("error" => "user already registered")));
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = pg_prepare($database, "insert_user_$username",
    "insert into users (username, password) VALUES ($1 , $2)");
$result = pg_execute($database, "insert_user_$username", array($username, $password_hash));

if ($result) {
    echo json_encode(array("error" => false, "body" => "Success!"));
} else {
    http_response_code(500);
    die(json_encode(array("error" => "Unknown error occurred. Please try again")));
}
