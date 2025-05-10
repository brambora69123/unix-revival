<?php
header('Content-type: image/png');
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
error_reporting(E_ERROR | E_PARSE);

$errimg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/Images/IDE/not-approved.png");
$penimg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/Images/IDE/pending.png");

$id = (int) ($_GET['id'] ?? null);
if ($id === 0) {
    $id = (int) ($_GET['assetId'] ?? null);
}

if ($id === 0) {
    die($errimg);
}

$request = ($_GET['request'] ?? null);

$x = (int) ($_GET['width'] ?? null);
$y = (int) ($_GET['height'] ?? null);

if ($x <= 0 || $y <= 0) {
    die($errimg);
}

$renderedImagePath = $_SERVER['DOCUMENT_ROOT'] . "/renderedassets/" . $id . ".png";

if (file_exists($renderedImagePath)) {
    if ($fileSize > 1) {
        die(file_get_contents($renderedImagePath));
    } else {
        die(file_get_contents($renderedImagePath));
    }
} else {
    $ImageUrl = "https://unixfr.xyz/asset/?id=" . $id;
    $ImageData = file_get_contents($ImageUrl);

    if ($ImageData !== false) {
        $image = imagecreatefromstring($ImageData);
        $resizedImage = imagescale($image, $x, $y);
        imagepng($resizedImage, $renderedImagePath);
        imagepng($resizedImage);
        imagedestroy($image);
        die();
    } else {
        die($penimg);
    }
}
?>
