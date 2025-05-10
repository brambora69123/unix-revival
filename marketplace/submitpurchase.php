<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header("Content-Type: application/json");
header('Content-Length: 86');
header('Keep-Alive: timeout=5, max=100');
header('Connection: Keep-Alive');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $assetId = (int)($_GET['productId'] ?? null);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assetId = (int)($_POST['productId'] ?? null);
} else {
    die(json_encode(['message' => 'Invalid request method.']));
}

$GetAssetInfo = $MainDB->prepare("SELECT id, name, description, creatorid, creatorname, placeid,  robux FROM passes WHERE id = :pid");
$GetAssetInfo->execute([':pid' => $assetId]);
$AssetInfo = $GetAssetInfo->fetch(PDO::FETCH_ASSOC);
		$receiptString = generateRandomString(30);
switch (true) {
    case (!$AssetInfo):
        die(json_encode(['message' => 'Unable to load info.']));
        break;
}

if (isset($_COOKIE['ROBLOSECURITY'])) {
    $userToken = $_COOKIE['ROBLOSECURITY'];
    $GetUserInfo = $MainDB->prepare("SELECT id, name, robux FROM users WHERE token = :token");
    $GetUserInfo->execute([':token' => $userToken]);
    $UserInfo = $GetUserInfo->fetch(PDO::FETCH_ASSOC);

    if ($UserInfo) {
        $rsprice = $AssetInfo['robux'];
        $updatedRobux = $UserInfo['robux'] - $rsprice;

        if ($updatedRobux >= 0) {
            $UpdateUserRobux = $MainDB->prepare("UPDATE users SET robux = :robux WHERE id = :userid");
            $UpdateUserRobux->execute([':robux' => $updatedRobux, ':userid' => $UserInfo['id']]);
        }
		


$InsertDevProduct = $MainDB->prepare("INSERT INTO devproduct (pid, plrid, reciept, productId, unitPrice) VALUES (:placeid, :playerid, :receipt, :product, :unit)");
$InsertDevProduct->execute([':placeid' => $AssetInfo['placeid'], ':playerid' => $UserInfo['id'], ':receipt' => $receiptString, ':product' => $assetId, ':unit' => $AssetInfo['robux']]);
    }
}





$AssetJSON = array(
    "success" => true,
    "status" => "Bought",
    "receipt" => $receiptString,
    "message" => (object) array()
);


die(json_encode($AssetJSON, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>
