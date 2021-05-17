<?php
require_once('private/secure_endpoint.php');
include_once('private/util/files.php');
include_once('private/util/header_parser.php');
include_once('private/util/shell_parser.php');

const structure_dir = "private/blocks/structure";
const body_dir = "private/blocks/body";
const header_dir = "private/blocks/header";

$structure = scan_directory(structure_dir);
$body = scan_directory(body_dir);
$header = scan_directory(header_dir);

$web_name = $_GET["WebName"];
//add first element of HTML
$html = parse_top_shell(structure_dir, $_GET);

$html = $html . parse_header($_GET, header_dir, "Logo&ItemsNav.html");

$html = $html . file_get_contents(structure_dir . "/BottomStructure.html");

echo $html;