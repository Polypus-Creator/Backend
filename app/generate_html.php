<?php
require_once('private/secure_endpoint.php');
require_once('private/util/files.php');
require_once('private/util/header_parser.php');
require_once('private/util/shell_parser.php');
require_once('private/util/body_parser.php');

const structure_dir = "private/blocks/structure";
const body_dir = "private/blocks/body";
const header_dir = "private/blocks/header";

$structure = scan_directory(structure_dir);
$body = scan_directory(body_dir);
$header = scan_directory(header_dir);

$web_name = $_GET["WebName"];
$elements = $_GET["ListaJSON"];
//add first element of HTML
$html = parse_top_shell(structure_dir, $web_name);
foreach ($elements as $element) {
    $element_name = $element["Element"];
    if (in_array($element_name, $header)) {
        $html = $html . parse_header($element, header_dir);
    } else {
        $html = $html . parse_body($element);
    }
}

$html = $html . file_get_contents(structure_dir . "/BottomStructure.html");

echo $html;