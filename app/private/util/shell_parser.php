<?php
function parse_top_shell($structure_dir, $json)
{
    return str_replace("websiteTitle",
        $json["WebName"],
        file_get_contents($structure_dir . "/TopStructure.html"));
}