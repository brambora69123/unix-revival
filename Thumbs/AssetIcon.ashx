<?php
header('Content-type: image/png');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$errimg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/Images/IDE/not-approved.png");
$penimg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/Images/IDE/pending.png");

$id = (int) ($_GET['id'] ?? null);
if ($id === 0) {
    $id = (int) ($_GET['AssetId'] ?? null);
}

if ($id === 0) {
    die($errimg);
}

$request = ($_GET['request'] ?? null);
$renderedImagePath = $_SERVER['DOCUMENT_ROOT'] . "/renderedicons/" . $id . ".png";

if (file_exists($renderedImagePath)) {
    $fileSize = filesize($renderedImagePath);
    if ($fileSize > 500) {
        die(file_get_contents($renderedImagePath));
    } else {
        die($penimg);
    }
} else {
    $ImageUrl = "https://unixfr.xyz/asset/?id=" . $id;
    
    $ImageData = file_get_contents($ImageUrl);

    if ($ImageData !== false) {
        // Save the image data to the file
        file_put_contents($renderedImagePath, $ImageData);
        
        // Output the image data
        die($ImageData);
    } else {
        die($penimg);
    }
}
?>
