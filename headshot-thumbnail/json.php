<?php
header("Content-Type: application/json");

require ($_SERVER['DOCUMENT_ROOT'].'/config.php');

$userId = isset($_GET['userId']) ? (int)$_GET['userId'] : 0;
$width = isset($_GET['width']) ? (int)$_GET['width'] : 0;
$height = isset($_GET['height']) ? (int)$_GET['height'] : 0;

if ($userId <= 0) {
    echo json_encode(["error" => "invalid userid"]);
    exit;
}

$url = "https://unixfr.xyz/headshot-thumbnail/image?userId=$userId&x=150&y=150";

$response = [
    "Url" => $url,
    "Final" => true,
    "SubstitutionType" => 0
];

echo json_encode($response, JSON_UNESCAPED_SLASHES);
?>
