<?php
function parse_header($data) : string
{
    $color = $data["a"];
    return file_get_contents(body_dir . "/" . $data . ".html");
}

