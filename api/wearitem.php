<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

if ($RBXTICKET == null) {
    die(header('Location: ' . $baseUrl . '/'));
}

$WearItem = (int) ($_GET['id'] ?? die(header('Location: ' . $baseUrl . '/error')));

$itemQuery = $MainDB->prepare("SELECT itemtype FROM bought WHERE boughtid=?");
$itemQuery->execute([$WearItem]);
$item = $itemQuery->fetch();

if (!$item) {
    die(header('Location: ' . $baseUrl . '/error'));
}

$itemType = $item['itemtype'];

if (in_array($itemType, ['TShirt', 'Shirt', 'Pants', 'Gear'])) {
    $checkQuery = $MainDB->prepare("SELECT * FROM bought WHERE boughtby=? AND wearing='1' AND itemtype=?");
    $checkQuery->execute([$id, $itemType]);
    $alreadyWearing = $checkQuery->fetch();

    if ($alreadyWearing) {
        die(header('Location: ' . $baseUrl . '/avatar'));
    }
}

$UpdateDB = $MainDB->prepare("UPDATE bought SET wearing = '1' WHERE boughtid=? AND boughtby=?");
$UpdateDB->execute([$WearItem, $id]);

$url = "https://unixfr.xyz/soapy/unix/Roblox/render?id=" . urlencode($id);
$content = file_get_contents($url);

die(header('Location: ' . $baseUrl . '/avatar'));
?>
