<?php
header('Content-type: image/png');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$errimg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/Images/IDE/not-approved.png");
$penimg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/Images/IDE/pending.png");

$id = (int) ($_GET['userId'] ?? die($errimg));
$x = (int) ($_GET['x'] ?? 200);
$y = (int) ($_GET['y'] ?? 100);

$AssetFetch = $MainDB->prepare("SELECT * FROM users WHERE id = :id");
$AssetFetch->execute([':id' => $id]);
$Results = $AssetFetch->fetch(PDO::FETCH_ASSOC);

if (!$Results) {
    die($errimg);
}

$filename = $_SERVER['DOCUMENT_ROOT'] . "/renders/" . $id . ($x === 48 && $y === 48 ? "-closeup.png" : ".png");

if (!file_exists($filename)) {
    if ($x === 48 && $y === 48) {
        // Make a request using file_get_contents to retrieve the close-up image
        $closeupImageData = file_get_contents("https://unixfr.xyz/soapy/unix/Roblox/render?id=$id&redirect=false");

        if ($closeupImageData !== null) {
            // Create image from the fetched data and save
            $image = imagecreatetruecolor($x, $y);
            $whiteColor = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $whiteColor);
            imagepng($image, $filename);
            imagedestroy($image);
            readfile($filename);
            exit();
        }
    } else {
        // Generate the image with specified width (x) and height (y)
        $image = imagecreatetruecolor($x, $y);
        $whiteColor = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $whiteColor);
        imagepng($image, $filename);
        imagedestroy($image);
    }
}

// Output the image
if (file_exists($filename)) {
    readfile($filename);
} else {
    die($errimg);
}
