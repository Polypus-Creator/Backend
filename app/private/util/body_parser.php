<?php

function parse_body($element): string
{
    $name = $element["Elemento"];
    switch ($name) {
        case "Separator":
            return parse_separator();
        case "CallToActionMiddle":
            return parse_cta_middle($element);
        case "CallToActionLeft":
            return parse_cta_left($element);
    }
    return "";
}

function parse_separator(): string
{
    return file_get_contents(body_dir . "/Divider.html");
}

function parse_cta_middle($element): string
{
    return str_replace(["%title", "%content", "%button1text", "%button2text", "#1", "#2"],
        [$element["Title"], $element["Text"], $element["ButtonText"], "TODO", $element["ButtonUrl"], "#"],
        file_get_contents(body_dir . "/CallToActionMiddle.html"));
}
function parse_cta_left($element): string
{
    return str_replace(["%title", "%content", "%buttonText", "#1"],
        [$element["Title"], $element["Text"], $element["ButtonText"], $element["ButtonUrl"]],
        file_get_contents(body_dir . "/CallToActionLeft.html"));
}