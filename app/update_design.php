<?php
require_once('private/secure_endpoint.php');

global $user;
global $database;

$stmt_name = "update_design_${$user["id"]}";
$stmt = pg_prepare($database, $stmt_name, 'update webs set website_name = $1, description = $2, category = $3, '
    . 'primary_colour = $4, secondary_colour = $5, font = $6 where user_id = $7');
$result = pg_execute($database, $stmt_name, [
    $_GET["Website_name"],
    $_GET["Description"],
    $_GET["Category"],
    $_GET["Primary_colour"],
    $_GET["Secondary_colour"],
    $_GET["Font"],
    $user["id"]
]);

if ($result !== false && pg_affected_rows($result) > 0) {
    echo json_encode(["error" => false]);
} else {
    http_response_code(500);
    die(json_encode(["error" => "Unknown error, please try again later"]));
}
