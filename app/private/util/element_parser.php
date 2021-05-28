<?php
require_once('header_parser.php');

class ElementParser
{
    private $details;

    public function __construct(array $details)
    {
        $this->details = $details;
    }


    function parse_element($element): string
    {
        $name = $element["Elemento"];
        switch ($name) {
            case "Logo&ItemsNav":
                return parse_header_items($element, $this->details);
            case "Separator":
                return $this->parse_separator();
            case "CallToActionMiddle": //todo check new name
                return $this->parse_cta($element);
            case "Image Text":
                return $this->parse_image_text($element);
            case "Footer": //todo check name
                return $this->parse_footer($element);
            case "Image":
                return $this->parse_images($element);
        }
        return "";
    }

    private function parse_separator(): string
    {
        return file_get_contents(body_dir . "Divider.html");
    }

    private function parse_cta($element): string
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

    private function parse_image_text($element): string
    {
        $file = $element["Orientaton"] === "Left" ? "ImageAndTextLeft.html" : "ImageAndTextRight.html";
        return str_replace(["%title", "%content", "%imageUrl"],
            [$element["Title"], $element["Text"], $element["Image"]],
            file_get_contents(body_dir . $file));
    }

    private function parse_footer($element): string
    {
        return str_replace("%footerText",
            $element["Text"],
            file_get_contents(footer_dir . "SimpleFooter.html"));
    }

    private function parse_images($element): string
    {
        $length = sizeof($element["RutasImages"]);
        if ($length == 0) return "";
        elseif ($length == 1) $file = "Image.html";
        elseif ($length == 2) $file = "2Images.html";
        else                  $file = "3Images.html";

        $result = file_get_contents(body_dir . $file);
        for ($i = 0; $i < $length; $i++) {
            $result = str_replace("%photo" . ($i + 1), $element["RutasImages"][$i], $result);
        }
        return $result;
    }
}