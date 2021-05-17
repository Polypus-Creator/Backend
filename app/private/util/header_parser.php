<?php
function parse_header($data, $header_dir, $header_type): string
{
    $files = [];
    for ($i = 0; $i < 3; $i++) {
        $file_path = $header_type;
        $files[$i] = file_get_contents($header_dir . "/" . str_replace("%", $i, $file_path));
    }
    $response = str_replace("logo.png", $data["LogoUrl"], $files[0]);
    $response = str_replace("title", $data["WebName"], $response);
    $first = true;
    foreach ($data["NavItems"] as $item) {
        // check it's not an empty item
        if ($item[0] != "") {
            $item_text = str_replace("title", $item[0], $files[1]);
            if ($item[1] != null) $item_text = str_replace("#", $item[1], $item_text);
            if (!$first) $item_text = str_replace(" active", "", $item_text);
            $first = false;
            $response = $response . $item_text;
        }
    }
    return $response . $files[2];
}

