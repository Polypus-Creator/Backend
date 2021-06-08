<?php
require_once('private/database.php');
include_once('private/endpoints.php');
require_once('private/util/generate_token.php');

global $database;
$username = $_POST['Username'];
$password = $_POST['Password'];

if ($username == null) die(json_encode(["error" => "Please enter a username"]));
if ($password == null) die(json_encode(["error" => "Please enter a password"]));

try {
    pg_prepare($database, "query_user_$username",
        'select id, username, password, email, create_date from users where username = $1');
    $result = pg_execute($database, "query_user_$username", [$username]);
    if (pg_num_rows($result) == 0) die(json_encode(["error" => "Incorrect credentials"]));
    $user = pg_fetch_assoc($result);

    if (password_verify($password, $user["password"])) {
        $token = generate_token();
        pg_prepare($database, "update_token_$username", "update users set token = $1 where id = $2");
        $code = pg_execute($database, "update_token_$username", [$token, $user["id"]]);
        if ($code === false) {
            http_response_code(500);
            die(json_encode(["error" => "Unknown error occurred. Please try again"]));
        }

        unset($user["password"]);
        $user["token"] = $token;
        echo json_encode([
            "error" => false,
            "body" => $user]);
    } else {
        http_response_code(400);
        die(json_encode(['error' => 'Incorrect credentials']));
    }
} catch (Exception $e) {

    http_response_code(500);
    die(json_encode(["error" => "Unknown error occurred. Please try again"]));
}
