<?php
require_once('private/secure_endpoint.php');
require_once('private/util/shell_parser.php');
require_once('private/util/element_parser.php');

const structure_dir = "private/blocks/structure/";
const body_dir = "private/blocks/body/";
const header_dir = "private/blocks/header/";
const footer_dir = "private/blocks/footer/";

$web_name = $_GET["WebName"];
$elements = $_GET["ListaJSON"];
//add first element of HTML
$html = parse_top_shell($web_name);
//parse each element
foreach ($elements as $element) {
    $element_name = $element["Element"];
    $html = $html . parse_element($element);
}
//add the body and html closing tags
$html = $html . file_get_contents(structure_dir . "BottomStructure.html");

echo $html;