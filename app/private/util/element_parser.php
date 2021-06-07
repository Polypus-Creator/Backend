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
            case "Price Card":
                return $this->parse_cards($element);
        }
        return "";
    }

    private function parse_separator(): string
    {
        return file_get_contents(body_dir . "Divider.html");
    }

    private function parse_cta($element): string
    {
        $file_types = ["Left" => "CallToActionLeft.html", "Middle" => "CallToActionMiddle.html",
            "Right" => "CallToActionRight.html"];
        if (isset($file_types[$element["Orientation"]])) {
            $file = $file_types[$element["Orientation"]];
        } else die(["error" => "Unrecognised value for orientation '${$element["Orientation"]}'"]);

        return str_replace(["%title", "%content", "%buttonText", "%buttonLink"],
            [$element["Title"] ?? "", $element["Text"] ?? "", $element["ButtonText"] ?? "", $element["ButtonUrl"] ?? "#"],
            file_get_contents(body_dir . $file));
    }

    private function parse_image_text($element): string
    {
        $file = $element["Orientaton"] === "Left" ? "ImageAndTextLeft.html" : "ImageAndTextRight.html";
        return str_replace(["%title", "%content", "%imageUrl"],
            [$element["Title"], $element["Text"], "Images" . $element["Image"]],
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
            $result = str_replace("%photo" . ($i + 1),
                "Images" . $element["RutasImages"][$i],
                $result);
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
        $columns = [$element["VText1"], $element["VText2"], $element["VText3"]];
        //if all columns are empty, return "";
        if (empty(array_filter($columns, function ($a) {
            return $a !== null;
        }))) return "";
        $parts = explode("<!--end-->", file_get_contents(body_dir . "TextCenter.html"));
        $result = $parts[0];
        foreach ($columns as $text) {
            if ($text != null) {
                $result = $result . str_replace("%text", $text, $parts[1]);
            }
        }
        return $result . $parts[2];
    }

    private function parse_map($element): string
    {
        $result = str_replace(["<!--iframe-->",
            "%title",
            "%mail",
            "%phone",
            "%schedule",
            "%website"],
            [$element["Iframe"],
                $element["Title"],
                $element["Correo"],
                $element["Phone"],
                $element["Hora"],
                $element["Web"]],
            file_get_contents(body_dir . "ContactUs.html"));
        return str_replace(["<iframe"], ["<iframe class=\"map\""], $result);
    }

    private function parse_cards($element): string
    {
        $numbers = ["one", "two", "three"];
        //the block has 3 parts: top, each card, bottom
        $parts = explode("<!--card-->", file_get_contents(body_dir . "3PriceCards.html"));
        $result = $parts[0];
        //for each card in the json
        for ($i = 0; $i < sizeof($element["Contenido"]); $i++) {
            $card_json = $element["Contenido"][$i];
            //each card has 3 parts: top, each advantage, bottom
            $card_parts = explode("<!--adv-->", $parts[1]);
            $advantages = explode("\r\n", $card_json[3]); //todo check separator
            $card = str_replace(
                ["%cardNumber", "%plan", "%price", "%currency"],
                [
                    $numbers[$i] ?? $numbers[sizeof($numbers) - 1], //used to style the background colour
                    $card_json[0], //free
                    $card_json[1], //0
                    $card_json[2]  //€
                ],
                $card_parts[0]); //replace on this

            foreach ($advantages as $advantage) {
                $card = $card . str_replace("%advantage", $advantage, $card_parts[1]);
            }
            $card = $card . $card_parts[2];
            $result = $result . $card;
        }
        return $result . $parts[2];
    }
}
