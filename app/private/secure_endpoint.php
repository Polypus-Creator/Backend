<?php
require_once('database.php');
require_once('endpoints.php');
global $database;

//get token
$headers = getallheaders();
$token = str_replace("Bearer ", "", $headers["Authorization"] . $headers["authorization"]);
if ($token == "") die(json_encode(["error" => "Incorrect token"]));

pg_prepare($database, "query_token_$token",
    'select id, username, password, create_date from users where token = $1');
$result = pg_execute($database, "query_token_$token", [$token]);
if (pg_num_rows($result) == 0) die(json_encode(["error" => "Incorrect token"]));
$user = pg_fetch_assoc($result);
