<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$logged = false;
$asset = [];

if (isset($_COOKIE['ROBLOSECURITY'])) {
    $roblosec = filter_var($_COOKIE['ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

    $usrquery = $MainDB->prepare("SELECT * FROM `users` WHERE `token` = :roblosec");
    $usrquery->execute(['roblosec' => $roblosec]);
    $usr = $usrquery->fetch(PDO::FETCH_ASSOC);

    if ($usr) {
        $logged = true;
        $uID = $usr['id'];
    }
}

$assetId = null;

$queryParams = $_GET['q'] ?? null;

if ($queryParams !== null) {
    if (strpos($queryParams, "creator:User") !== false) {
        $assetId = str_replace("creator:User", "", $queryParams);
    } elseif (strpos($queryParams, "creator%3AUser") !== false) {
        $assetId = str_replace("creator%3AUser", "", $queryParams);
    }
}

    $query = $MainDB->prepare("SELECT * FROM `asset` WHERE `creatorid` = :assetId AND `itemtype` = 'Place'");
    $query->execute(['assetId' => $uID]);
    $asset = $query->fetchAll(PDO::FETCH_ASSOC);

$response = [
    "previousPageCursor" => null,
    "nextPageCursor" => null,
    "data" => []
];

foreach ($asset as $game) {
    $privacyType = ($game['public'] == 1) ? "Public" : "Private";

    $dataItem = [
        "id" => $game['id'],
        "name" => $game['name'],
        "description" => $game['moreinfo'],
        "isArchived" => false,
        "rootPlaceId" => $game['id'],
        "isActive" => true,
        "privacyType" => $privacyType,
        "creatorType" => "User",
        "creatorTargetId" => $game['creatorid'],
        "creatorName" => isset($usr['name']) ? $usr['name'] : '', 
        "created" => $game['createdon'], 
        "updated" => $game['updatedon']
    ];

    $response['data'][] = $dataItem;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
