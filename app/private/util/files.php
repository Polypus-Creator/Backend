<?php
function scan_directory($dir): array
{
    $result = [];
    // files without . / ..
    $files = array_values(array_diff(scandir($dir), array('..', '.')));
    foreach ($files as $file) {
        // remove extension
        $no_extension = substr($file, 0, strpos($file, ".") - 1);
        $result[$no_extension] = $file;
    }
    return $result;
}
