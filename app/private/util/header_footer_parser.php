<?php

class HeaderFooterParser
{
    private $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    function parse_header($element): string
    {
        //separate the file into parts to iterate and repeat chunks
        $parts = explode("<!--end-->", file_get_contents(header_dir . "Logo&ItemsNav.html"));

        $result = str_replace("logo.png", "Images" . $element["LogoUrl"], $parts[0]);
        $result = str_replace("title", $this->details["website_name"], $result);
        $first = true;
        foreach ($element["NavItems"] as $item) {
            // check it's not an empty item
            if ($item[0] != "") {
                $item_text = str_replace("title", $item[0], $parts[1]);
                if ($item[1] != null) $item_text = str_replace("#", $item[1], $item_text);
                if (!$first) $item_text = str_replace(" active", "", $item_text);
                $first = false;
                $result = $result . $item_text;
            }
        }
        return $result . $parts[2];
    }

    function parse_footer($element): string
    {
        switch ($element["Elemento"]) {
            case "SimpleFooter":
                return $this->parse_simple_footer($element);
            case "ComplexFooter":
                return $this->parse_complex_footer($element);
        }
        return "";
    }

    private function parse_simple_footer($element): string
    {
        return str_replace("%footerText",
            $element["Text"],
            file_get_contents(footer_dir . "SimpleFooter.html"));
    }

    public function parse_complex_footer($element): string
    {
        $parts = explode("<!--end-->", file_get_contents(footer_dir . "ComplexFooter.html"));
        $result = str_replace(["%title", "%email", "%phone", "%schedule", "%website"],
            [
                $element["Title"],
                $element["Correo"],
                $element["Telefono"],
                $element["Horari"],
                $element["Web"],
            ],
            $parts[0]);
        $media = [
            $element["LinkTw"],
            $element["LinkLk"],
            $element["LinkIn"],
            $element["LinkFb"],
        ];
        for ($i = 0; $i < sizeof($media); $i++) {
            if ($media[$i] != null) {
                $result = $result . str_replace("%link", $media[$i], $parts[$i + 1]);
            }
        }
        return $result . $parts[sizeof($parts) - 1];
    }
}
