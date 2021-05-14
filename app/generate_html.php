<?php
include_once('private/endpoints.php');
include_once('private/util/files.php');

const structure_dir = "private/blocks/structure";
const body_dir = "private/blocks/body";
const header_dir = "private/blocks/header";

$structure = scan_directory(structure_dir);
$body = scan_directory(body_dir);
$header = scan_directory(header_dir);

$web_name = $_GET["WebName"];
//add first element of HTML
$html = parse_top_shell(structure_dir, $web_name);


foreach ($_GET["body"] as $element) {
    $html = $html . parse_header($element);
}

$html = $html . file_get_contents(body_dir . "BottomStructure.html");

echo $html;