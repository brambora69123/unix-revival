<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header('Content-Type: application/json');

// Parse the URL to get the asset ID and details part
$requestUri = $_SERVER['REQUEST_URI'];
$uriParts = explode('/', trim($requestUri, '/'));
$assetId = (int) ($uriParts[2] ?? die(json_encode(["message" => "Unable to process request."])));
$details = $uriParts[3] ?? '';

if ($details !== 'details') {
    die(json_encode(["message" => "Unable to process request."]));
}

$GetAssetInfo = $MainDB->prepare("SELECT id, name, moreinfo, creatorid, creatorname, createdon, updatedon, rsprice, tkprice, itemtype FROM asset WHERE id = :pid");
$GetAssetInfo->execute([':pid' => $assetId]);
$AssetInfo = $GetAssetInfo->fetch(PDO::FETCH_ASSOC);

if (!$AssetInfo) {
    $GetBadgeInfo = $MainDB->prepare("SELECT id, name FROM badges WHERE id = :pid");
    $GetBadgeInfo->execute([':pid' => $assetId]);
    $AssetInfo2 = $GetBadgeInfo->fetch(PDO::FETCH_ASSOC);

    if (!$AssetInfo2) {
        $CheckTableQuery = $MainDB->prepare("SELECT * FROM passes WHERE id = :id");
        $CheckTableQuery->execute([':id' => $assetId]);
        $CheckTableResult = $CheckTableQuery->fetch(PDO::FETCH_ASSOC);

        if (!$CheckTableResult) {
            die(json_encode(["message" => "Unable to process request."]));
        }

        $AssetJSON = [
            "AssetId" => $CheckTableResult['id'],
            "ProductId" => $CheckTableResult['id'],
            "Name" => $CheckTableResult['name'],
            "Description" => $CheckTableResult['description'],
            "AssetTypeId" => $CheckTableResult['assettype'],
            "ProductType" => "Pass",
            "Creator" => [
                "Id" => $CheckTableResult['creatorid'],
                "Name" => $CheckTableResult['creatorname'],
            ],
            "IconImageAssetId" => $CheckTableResult['imgid'],
            "Created" => "26/12/2023",
            "Updated" => "26/12/2023",
            "PriceInRobux" => $CheckTableResult['robux'],
            "PriceInTickets" => 0,
            "Sales" => 0,
            "IsNew" => true,
            "IsForSale" => true,
            "IsPublicDomain" => false,
            "IsLimited" => false,
            "IsLimitedUnique" => false,
            "Remaining" => 0,
            "MinimumMembershipLevel" => 0,
            "ContentRatingTypeId" => 0,
        ];

        die(json_encode($AssetJSON, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
    }

    $AssetJSON = [
        "TargetId" => $AssetInfo2['id'],
        "AssetId" => $AssetInfo2['id'],
        "ProductId" => $AssetInfo2['id'],
        "Name" => $AssetInfo2['name'],
        "Description" => "Hi",
        "AssetTypeId" => 21,
        "ProductType" => "User Product",
        "Creator" => [
            "Id" => 3,
            "Name" => "Previous",
        ],
        "IconImageAssetId" => 0,
        "Created" => "24/12/2023",
        "Updated" => "24/12/2023",
        "PriceInRobux" => 0,
        "PriceInTickets" => 0,
        "Sales" => 0,
        "IsNew" => true,
        "IsForSale" => true,
        "IsPublicDomain" => false,
        "IsLimited" => false,
        "IsLimitedUnique" => false,
        "Remaining" => 0,
        "MinimumMembershipLevel" => 0,
        "ContentRatingTypeId" => 0,
        "HasVerifiedBadge" => true
    ];

    die(json_encode($AssetJSON, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

$AssetJSON = [
    "AssetId" => $AssetInfo['id'],
    "ProductId" => $AssetInfo['id'],
    "Name" => $AssetInfo['name'],
    "Description" => $AssetInfo['moreinfo'],
    "AssetTypeId" => 0,
    "ProductType" => $AssetInfo['itemtype'],
    "Creator" => [
        "Id" => $AssetInfo['creatorid'],
        "Name" => $AssetInfo['creatorname'],
    ],
    "IconImageAssetId" => $AssetInfo['id'],
    "Created" => $AssetInfo['createdon'],
    "Updated" => $AssetInfo['updatedon'],
    "PriceInRobux" => $AssetInfo['rsprice'],
    "PriceInTickets" => $AssetInfo['tkprice'],
    "Sales" => 0,
    "IsNew" => true,
    "IsForSale" => true,
    "IsPublicDomain" => false,
    "IsLimited" => false,
    "IsLimitedUnique" => false,
    "Remaining" => 0,
    "MinimumMembershipLevel" => 0,
    "ContentRatingTypeId" => 0,
];

die(json_encode($AssetJSON, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
?>
