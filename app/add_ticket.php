<?php

require_once('private/secure_endpoint.php');

global $user;
global $database;

$id = $user["id"];

if ($_GET["title"] === null) die(["error" => "Title can't be null"]);
if ($_GET["description"] === null) die(["error" => "Description can't be null"]);

try {
    $stmt = pg_prepare($database, "insert_ticket_$id",
        "insert into tickets (user_id, title, description, urgent)
     VALUES ($1, $2, $3, $4)");
    $result = pg_execute($database, "insert_ticket_$id", [
        $id,
        $_GET["title"],
        $_GET["description"],
        $_GET["urgent"] ?? false
    ]);
    echo json_encode(["error" => false, "body" => "success"]);
} catch (Exception $e) {
    die(["error" => "Unexpected error, please try again."]);
}
