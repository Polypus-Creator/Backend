<?php
require_once('private/secure_endpoint.php');
require_once('private/util/shell_parser.php');
require_once('private/util/element_parser.php');
require_once('private/util/util.php');

const structure_dir = "private/blocks/structure/";
const body_dir = "private/blocks/body/";
const header_dir = "private/blocks/header/";
const footer_dir = "private/blocks/footer/";

global $database;
global $user;

$stmt_name = "query_website_details_${$user["id"]}";
pg_prepare($database, $stmt_name,
    'select * from webs where user_id = $1');
$result = pg_execute($database, $stmt_name, array($user["id"]));
if (pg_num_rows($result) == 0) die(json_encode(array("error" => "Website not found")));
$details = pg_fetch_assoc($result);

$web_name = $_GET["WebName"];
$elements = $_GET["ListaJSON"];
//add first element of HTML
$html = parse_top_shell($web_name);
$parser = new ElementParser($details);
//parse each element
foreach ($elements as $element) {
    $element_name = $element["Element"];
    $html = $html . $parser->parse_element($element);
}
//add the body and html closing tags
$html = $html . file_get_contents(structure_dir . "BottomStructure.html");

//todo integration test with css
//if the main colour is illegible with white, switch to dark theme
if (lum_diff_hex($details["primary_colour"], "#FFFFFF") < 5) {
    $html = str_replace("light-theme", "dark-theme", $html);
}
echo $html;