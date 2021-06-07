<?php
$server = "db";
$database_name = getenv("POSTGRES_DB");
$id = getenv("POSTGRES_USER");
$password = getenv("POSTGRES_PASSWORD");
$database = pg_connect("host=$server dbname=$database_name user=$id password=$password");

if (!$database) {
    http_response_code(500);
    die(json_encode(["error" => "cannot connect to database"]));
}
