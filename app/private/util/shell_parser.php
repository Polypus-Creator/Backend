<?php
function parse_top_shell(array $details)
{
    return str_replace(["websiteTitle", "%font"],
        [$details["website_name"], strtolower("font-" . $details["font"])],
        file_get_contents(structure_dir . "TopStructure.html"));
}