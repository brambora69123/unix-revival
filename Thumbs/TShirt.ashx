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

$idtobe = (int) ($_GET['idtobe'] ?? die("error"));

$request = ($_GET['request'] ?? null);
$renderedImagePath = $_SERVER['DOCUMENT_ROOT'] . "/renderedassets/" . $idtobe . ".png";
if (file_exists($renderedImagePath)) {
    die(file_get_contents($renderedImagePath));
} else {
    $imageUrl = "https://unixfr.xyz/asset/?id=" . $id;
    $xmlData = file_get_contents($imageUrl);

    if ($xmlData !== false) {
        $xml = simplexml_load_string($xmlData);
        $imageUrl = (string) $xml->Content->url;
die($imageUrl);
        if (!empty($imageUrl)) {
            $imageData = file_get_contents($imageUrl);

            if ($imageData !== false) {
                file_put_contents($renderedImagePath, $imageData);
                die($renderedImagePath);
            } else {
                die($penimg);
            }
        } else {
            die($penimg);
        }
    } else {
        die($penimg);
    }
}
?>
