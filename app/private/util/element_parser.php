<?php

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
            case "Separator":
                return $this->parse_separator();
            case "CallToAction":
                return $this->parse_cta($element);
            case "Image Text":
                return $this->parse_image_text($element);
            case "Image":
                return $this->parse_images($element);
            case "Title":
                return $this->parse_title($element);
            case "Text":
                return $this->parse_text($element);
            case "Mapa":
                return $this->parse_map($element);
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

    private function parse_title($element): string
    {
        $file = "TitleCenter.html";
        return str_replace("%text", $element["VTitle"], file_get_contents(body_dir . $file));
    }

    private function parse_text($element): string
    {
        $file = "TextCenter.html";
        return str_replace("%text", $element["VText"], file_get_contents(body_dir . $file));
    }

    private function parse_map($element): string
    {
        $result = str_replace(array("<!--iframe-->",
            "%title",
            "%mail",
            "%phone",
            "%schedule",
            "%website"),
            array($element["Iframe"],
                $element["Title"],
                $element["Correo"],
                $element["Phone"],
                $element["Hora"],
                $element["Web"]),
            file_get_contents(body_dir . "ContactUs.html"));
        return str_replace(array("<iframe"), array("<iframe class=\"map\""), $result);
    }
}