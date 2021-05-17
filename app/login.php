<?php
require_once('private/database.php');
include_once('private/endpoints.php');
require_once('private/util/generate_token.php');

global $database;
$username = $_POST['username'];
$password = $_POST['password'];

if ($username == null) die(json_encode(array("error" => "Please enter a username")));
if ($password == null) die(json_encode(array("error" => "Please enter a password")));

try {
    pg_prepare($database, "query_user_$username",
        'select id, username, password, create_date from users where username = $1');
    $result = pg_execute($database, "query_user_$username", array($username));
    if (pg_num_rows($result) == 0) die(json_encode(array("error" => "Incorrect credentials")));
    $user = pg_fetch_row($result);

    $token = generate_token();
    pg_prepare($database, "update_token_$username", "update users set token = $1 where id = $2");
    $code = pg_execute($database, "update_token_$username", array($token, $user[0]));
    if ($code === false) throw new ErrorException();

    if (password_verify($password, $user[2])) {
        echo json_encode(array(
            "error" => false,
            "body" => array(
                "id" => $user[0],
                "username" => $user[1],
                "create_date" => $user[3],
                "token" => $token)));
    } else {
        die(json_encode(array('error' => 'Incorrect credentials')));
    }
} catch (Exception $e) {

    http_response_code(500);
    die(json_encode(array('error' => 'Unknown error occurred. Please try again')));
}