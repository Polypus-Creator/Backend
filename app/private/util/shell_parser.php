<?php
function parse_top_shell($structure_dir, $name)
{
    return str_replace("websiteTitle",
        $name,
        file_get_contents($structure_dir . "/TopStructure.html"));
}