<?php
require_once('private/secure_endpoint.php');

global $user;
global $database;

$stmt_name = "get_design_${$user["id"]}";
$stmt = pg_prepare($database, $stmt_name, 'select * from webs where user_id = $1');
$result = pg_execute($database, $stmt_name, [$user["id"]]);
$details = pg_fetch_assoc($result);
unset($details["data"]);
echo json_encode(["error" => false, "body" => [
    "Website_name" => $details["website_name"],
    "Description" => $details["description"],
    "Category" => $details["category"],
    "Primary_colour" => $details["primary_colour"],
    "Secondary_colour" => $details["secondary_colour"],
    "Font" => $details["font"],
]]);
