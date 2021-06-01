<?php
/**
 * This function uses the luminosity to calculate the difference between two colours, in RGB.
 * @return float The returned value should be bigger than 5 for best readability.
 * @link https://www.splitbrain.org/blog/2008-09/18-calculating_color_contrast_with_php
 */
function lum_diff(int $R1, int $G1, int $B1, int $R2, int $G2, int $B2): float
{
    $L1 = 0.2126 * pow($R1 / 255, 2.2) +
        0.7152 * pow($G1 / 255, 2.2) +
        0.0722 * pow($B1 / 255, 2.2);
    $L2 = 0.2126 * pow($R2 / 255, 2.2) +
        0.7152 * pow($G2 / 255, 2.2) +
        0.0722 * pow($B2 / 255, 2.2);

    if ($L1 > $L2) return ($L1 + 0.05) / ($L2 + 0.05);
    else return ($L2 + 0.05) / ($L1 + 0.05);
}

function lum_diff_hex(string $h1, string $h2): float
{
    $h1 = str_split(ltrim($h1, "#"), 2);
    $h2 = str_split(ltrim($h2, "#"), 2);
    return lum_diff(
        hexdec($h1[0]),
        hexdec($h1[1]),
        hexdec($h1[2]),
        hexdec($h2[0]),
        hexdec($h2[1]),
        hexdec($h2[2]),
    );
}
