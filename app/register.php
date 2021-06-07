<?php
require('private/database.php');
include('private/endpoints.php');

global $database;
$username = $_POST["Username"];
$password = $_POST["Password"];

if (strlen($password) < 8) {
    http_response_code(400);
    die(json_encode(["error" => "Password must be at least 8 characters long!"]));
}

$stmt = pg_prepare($database, "query_user_$username", "select * from users where username = $1");
$result = pg_execute($database, "query_user_$username", [$username]);

if (pg_num_rows($result) > 0) {
    http_response_code(400);
    die(json_encode(array("error" => "user already registered")));
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = pg_prepare($database, "insert_user_$username",
    "insert into users (username, password, email, security_question, security_answer) "
    . "VALUES ($1 , $2, $3, $4, $5)");
$result = pg_execute($database, "insert_user_$username",
    [
        $username,
        $password_hash,
        $_POST["Email"],
        $_POST["PreguntaSeguridad"],
        $_POST["RespuestaSeguridad"],
    ]
);

$stmt = pg_prepare($database, "insert_user_website_$username",
    "insert into webs (user_id) VALUES ((select id from users where username = $1))");
$result = pg_execute($database, "insert_user_website_$username", [$username]);


if ($result) {
    echo json_encode(["error" => false, "body" => "Success!"]);
} else {
    http_response_code(500);
    die(json_encode(["error" => "Unknown error occurred. Please try again"]));
}
