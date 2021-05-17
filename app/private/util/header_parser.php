<?php
function parse_header($data, $header_dir, $header_type): string
{
    //separate the file into parts to iterate and repeat chunks
    $parts = explode("<!--end-->", file_get_contents($header_dir . "/" . $header_type));

    $result = str_replace("logo.png", $data["LogoUrl"], $parts[0]);
    $result = str_replace("title", $data["WebName"], $result);
    $first = true;
    foreach ($data["NavItems"] as $item) {
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

