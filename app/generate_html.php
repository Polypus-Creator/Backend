<?php
require_once('private/secure_endpoint.php');
require_once('private/util/shell_parser.php');
require_once('private/util/element_parser.php');
require_once('private/util/util.php');
require_once('private/util/header_footer_parser.php');

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

$elements = $_GET["ListaJSON"];
//add first element of HTML
$html = parse_top_shell($details);
$parser = new ElementParser($details);
$hf_parser = new HeaderFooterParser($details);

//parse header
$html = $html . $hf_parser->parse_header($_GET["Header"]);

//parse each element
foreach ($elements as $element) {
    $element_name = $element["Element"];
    $html = $html . $parser->parse_element($element);
}

//parse footer
$html = $html . $hf_parser->parse_footer($_GET["Footer"]);
//add the body and html closing tags
$html = $html . file_get_contents(structure_dir . "BottomStructure.html");

//todo integration test with css
//if the main colour is illegible with white, switch to dark theme
if (lum_diff_hex($details["primary_colour"], "#000000") < 5) {
    $html = str_replace("light-theme", "dark-theme", $html);
    $html = str_replace("navbar-light", "navbar-dark", $html);
}

$css = file_get_contents(structure_dir . "stylesheet.css");

//echo $html;
$prim2 = darken_color($details["primary_colour"], 2);
echo str_replace(["#primary", "#secondary", "#prim2"], [$details["primary_colour"], $details["secondary_colour"], $prim2], $css);
