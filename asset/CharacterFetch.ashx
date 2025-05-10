<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
$UserId = (int)($_GET['id'] ?? die(json_encode(['message' => 'Cannot fetch request at this time.'])));
$WardrobeSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id AND itemtype != 'model' AND wearing = '1' AND itemtype != 'advertisement' AND itemtype != 'decal' AND itemtype != 'audio' ORDER BY id");
$WardrobeSrh->execute([":id" => $UserId]);
$ReWDS = $WardrobeSrh->fetchAll();

if (!$ReWDS) {
    die($baseUrl . "/Asset/BodyColors.ashx?userId=" . $UserId . ";");
} else {
    echo $baseUrl . "/Asset/BodyColors.ashx?userId=" . $UserId . ";";
    foreach ($ReWDS as $AssetInfo) {
        if ($AssetInfo['itemtype'] == "Package") {
            $packageId = $AssetInfo['boughtid'];
            $packageQuery = $MainDB->prepare("SELECT * FROM package WHERE packageid = :packageId");
            $packageQuery->execute([':packageId' => $packageId]);
            $package = $packageQuery->fetch(PDO::FETCH_ASSOC);

            if ($package) {
                for ($i = 1; $i <= 5; $i++) {
                    $itemId = $package['id' . $i];
                    if (!empty($itemId)) {
                        echo "http://www.unixfr.xyz/asset/?id=" . $itemId . ";";
                    }
                }
            }
        } else {
            echo $baseUrl . "/asset/?id=" . $AssetInfo['boughtid'] . ";";
        }
    }
}
?>
