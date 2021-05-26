<?php
require_once('header_parser.php');

function parse_element($element): string
{
    $name = $element["Elemento"];
    switch ($name) {
        case "Logo&ItemsNav":
            return parse_header_items($element);
        case "Separator":
            return parse_separator();
        case "CallToActionMiddle": //todo check new name
            return parse_cta($element);
        case "Image Text":
            return parse_image_text($element);
        case "Footer": //todo check name
            return parse_footer($element);
    }
    return "";
}

function parse_separator(): string
{
    return file_get_contents(body_dir . "Divider.html");
}

function parse_cta($element): string
{
    $file_types = array("Left" => "CallToActionLeft.html", "Middle" => "CallToActionMiddle.html",
        "Right" => "CallToActionRight.html");
    if (isset($file_types[$element["Orientation"]])) {
        $file = $file_types[$element["Orientation"]];
    } else die(array("error" => "Unrecognised value for orientation '${$element["Orientation"]}'"));

    return str_replace(["%title", "%content", "%buttonText", "%buttonLink"],
        [$element["Title"] ?? "", $element["Text"] ?? "", $element["ButtonText"] ?? "", $element["ButtonUrl"] ?? "#"],
        file_get_contents(body_dir . $file));
}

function parse_image_text($element): string
{
    $file = $element["Orientaton"] === "Left" ? "ImageAndTextLeft.html" : "ImageAndTextRight.html";
    return str_replace(["%title", "%content", "%imageUrl"],
        [$element["Title"], $element["Text"], $element["Image"]],
        file_get_contents(body_dir . $file));
}

function parse_footer($element): string
{
    return str_replace("%footerText",
        $element["Text"],
        file_get_contents(footer_dir . "SimpleFooter.html"));
}
