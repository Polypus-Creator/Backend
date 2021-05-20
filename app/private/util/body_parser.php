<?php

function parse_body($element): string
{
    $name = $element["Elemento"];
    switch ($name) {
        case "Separator":
            return parse_separator();
    }
    return "";
}

function parse_separator(): string
{
    return file_get_contents(body_dir . "/Divider.html");
}