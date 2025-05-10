<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header('Content-Type: application/json');




$requestUri = $_SERVER['REQUEST_URI'];
$uriParts = explode('/', trim($requestUri, '/'));
$assetId = (int) ($uriParts[2] ?? die(json_encode(["message" => "cant process"])));
$details = $uriParts[3] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
	if (isset($_COOKIE['ROBLOSECURITY'])) {
    $roblosec = filter_var($_COOKIE['ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

    $usrquery = $MainDB->prepare("SELECT * FROM `users` WHERE `token` = :roblosec");
    $usrquery->execute(['roblosec' => $roblosec]);
    $usr = $usrquery->fetch(PDO::FETCH_ASSOC);

    if ($usr) {
        $logged = true;
        $uID = $usr['id'];
    } else {
		die(json_encode(["message" => "not authenticated"]));
	}
}

    $patchData = json_decode(file_get_contents('php://input'), true);
    
    if (!$patchData || (!isset($patchData['description']) && !isset($patchData['name']))) {
        http_response_code(400);
        echo json_encode(["error" => "haha"]);
        exit;
    }


    $query = "UPDATE `asset` SET ";
    $updates = [];

    $params = ['assetId' => $assetId];

    if (isset($patchData['name'])) {
        $updates[] = "`name` = :name";
        $params['name'] = $patchData['name'];
    }

    if (isset($patchData['description'])) {
        $updates[] = "`moreinfo` = :description";
        $params['description'] = $patchData['description'];
    }

    $query .= implode(", ", $updates);
    $query .= " WHERE `id` = :assetId";

    try {
        $stmt = $MainDB->prepare($query);
        $stmt->execute($params);

$query = $MainDB->prepare("SELECT * FROM `asset` WHERE `id` = :assetId");
$query->execute(['assetId' => $assetId]);
$asset = $query->fetch(PDO::FETCH_ASSOC);

if (!$asset) {
    die(json_encode(["message" => "Asset not found."]));
}

if ($asset['avatartype'] == "R6") {
    $avat = "MorphToR6";
} elseif ($asset['avatartype'] == "R15") {
    $avat = "MorphToR15";
} else {
    $avat = "PlayerChoice";
}

if ($asset['public'] == 1) {
    $eee = "Public";
} else {
    $eee = "Private";
}

$data = [
    "allowPrivateServers" => false,
    "privateServerPrice" => 0,
    "id" => $asset['id'],
    "name" => $asset['name'],
	"description" => $asset['moreinfo'],
    "universeAvatarType" => $avat,
    "universeScaleType" => "AllScales",
    "universeAnimationType" => "PlayerChoice",
    "universeCollisionType" => "OuterBox",
    "universeBodyType" => "Standard",
    "universeJointPositioningType" => "ArtistIntent",
    "isArchived" => false,
    "isFriendsOnly" => false,
    "genre" => "All",
    "playableDevices" => ["Computer", "Phone", "Tablet"],
    "permissions" => [
        "IsThirdPartyTeleportAllowed" => true,
        "IsThirdPartyAssetAllowed" => true,
        "IsThirdPartyPurchaseAllowed" => true
    ],
    "isForSale" => false,
    "price" => 0,
    "isStudioAccessToApisAllowed" => true,
    "privacyType" => $eee
];

$json = json_encode($data, JSON_PRETTY_PRINT);
echo $json;        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
        exit;
    }
}


if ($details !== 'configuration') {
    die(json_encode(["message" => "Unable to process request."]));
}

$query = $MainDB->prepare("SELECT * FROM `asset` WHERE `id` = :assetId");
$query->execute(['assetId' => $assetId]);
$asset = $query->fetch(PDO::FETCH_ASSOC);

if (!$asset) {
    die(json_encode(["message" => "Asset not found."]));
}

if ($asset['avatartype'] == "R6") {
    $avat = "MorphToR6";
} elseif ($asset['avatartype'] == "R15") {
    $avat = "MorphToR15";
} else {
    $avat = "PlayerChoice";
}

if ($asset['public'] == 1) {
    $eee = "Public";
} else {
    $eee = "Private";
}

$data = [
    "allowPrivateServers" => false,
    "privateServerPrice" => 0,
    "id" => $asset['id'],
    "name" => $asset['name'],
    "universeAvatarType" => $avat,
    "universeScaleType" => "AllScales",
    "universeAnimationType" => "PlayerChoice",
    "universeCollisionType" => "OuterBox",
    "universeBodyType" => "Standard",
    "universeJointPositioningType" => "ArtistIntent",
    "isArchived" => false,
    "isFriendsOnly" => false,
    "genre" => "All",
    "playableDevices" => ["Computer", "Phone", "Tablet"],
    "permissions" => [
        "IsThirdPartyTeleportAllowed" => true,
        "IsThirdPartyAssetAllowed" => true,
        "IsThirdPartyPurchaseAllowed" => true
    ],
    "isForSale" => false,
    "price" => 0,
    "isStudioAccessToApisAllowed" => true,
    "privacyType" => $eee
];

$json = json_encode($data, JSON_PRETTY_PRINT);
echo $json;
?>
