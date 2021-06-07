<?php
require_once('private/secure_endpoint.php');

global $database;
global $token;

pg_prepare($database, "logout_user_$token",
    "update users set token = null where token = $1");
$result = pg_execute($database, "logout_user_$token", [$token]);

if (pg_affected_rows($result) == 0) {
    http_response_code(500);
    die(json_encode(["error" => "Unexpected error, please try again"]));
} else {
    echo json_encode(["error" => false, "body" => "Logged out"]);
}
