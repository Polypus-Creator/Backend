<?php
function parse_header_items($element): string
{
    //separate the file into parts to iterate and repeat chunks
    $parts = explode("<!--end-->", file_get_contents(header_dir . "Logo&ItemsNav.html"));

    $result = str_replace("logo.png", $element["LogoUrl"], $parts[0]);
    $result = str_replace("title", $element["WebName"], $result);
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

