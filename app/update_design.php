<?php
require_once('private/secure_endpoint.php');

global $user;
global $database;

$stmt_name = "update_design_${$user["id"]}";
$stmt = pg_prepare($database, $stmt_name, 'update webs set website_name = $1, description = $2, category = $3, '
    . 'primary_colour = $4, secondary_colour = $5, font = $6 where user_id = $7');
$result = pg_execute($database, $stmt_name, array(
    $_GET["website_name"],
    $_GET["description"],
    $_GET["category"],
    $_GET["primary_colour"],
    $_GET["secondary_colour"],
    $_GET["font"],
    $user["id"]
));

if ($result !== false && pg_affected_rows($result) > 0) {
    echo json_encode(array("error" => false));
} else {
    http_response_code(500);
    die(json_encode(array("error" => "Unknown error, please try again later")));
}
