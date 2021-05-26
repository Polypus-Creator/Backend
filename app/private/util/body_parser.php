<?php

function parse_body($element): string
{
    $name = $element["Elemento"];
    switch ($name) {
        case "Separator":
            return parse_separator();
        case "CallToAction": //todo check new name
            return parse_cta($element);
        case "Image Text":
            return parse_image_text($element);
    }
    return "";
}

function parse_separator(): string
{
    return file_get_contents(body_dir . "/Divider.html");
}

function parse_cta($element): string
{
    $file_types = array("Left" => "/CallToActionLeft.html", "Middle" => "/CallToActionMiddle.html",
        "Right" => "/CallToActionRight.html");
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
        file_get_contents(body_dir . "/$file"));
}