<?php
function parse_top_shell($structure_dir, $web_name)
{
    $shell = file_get_contents($structure_dir . "/TopStructure.html");
    str_replace($shell, "websiteTitle", $web_name);
    return $shell;
}