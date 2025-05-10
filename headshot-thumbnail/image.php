<?php
header('Content-type: image/png');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$errimg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/Images/IDE/not-approved.png");
$penimg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/Images/IDE/pending.png");

$id = (int) ($_GET['userId'] ?? die($errimg));
$x = (int) ($_GET['width'] ?? 150);
$y = (int) ($_GET['height'] ?? 150);

$AssetFetch = $MainDB->prepare("SELECT * FROM users WHERE id = :id");
$AssetFetch->execute([':id' => $id]);
$Results = $AssetFetch->fetch(PDO::FETCH_ASSOC);




switch (true) {
    case (!$Results):
        die($errimg);
        break;
}

$filename = ($_SERVER['DOCUMENT_ROOT'] . "/renders/" . $id . ($x === 150 && $y === 150 ? "-closeup.png" : ".png"));
if (!file_exists($filename)) {
    if ($x === 150 && $y === 150) {
        $closeupImageData = @file_get_contents("https://unixfr.xyz/soapy/unix/Roblox/render?id=$id&redirect=false&closeup=true");
        
        if ($closeupImageData !== nil) {
            $image = imagecreatetruecolor($x, $y);
            $whiteColor = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $whiteColor);
            imagepng($image, $filename);
            imagedestroy($image);
            readfile($filename);
            exit();
        }
    } else {
        $image = imagecreatetruecolor($x, $y);
        $whiteColor = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $whiteColor);
        imagepng($image, $filename);
        imagedestroy($image);
    }
}

if (file_exists($filename)) {
    readfile($filename);
} else {
    die($errimg);
}
?>
