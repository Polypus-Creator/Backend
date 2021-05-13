<?php
$server = "db";
$database_name = getenv("POSTGRES_DB");
$username = getenv("POSTGRES_USER");
$password = getenv("POSTGRES_PASSWORD");
$database = pg_connect("host=$server dbname=$database_name user=$username password=$password");

if (!$database) {
    http_response_code(500);
    die(json_encode(array("error" => "cannot connect to database")));
}
