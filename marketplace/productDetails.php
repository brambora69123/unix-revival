<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$assetId = (int) ($_GET['assetId'] ?? null);
$productId = (int) ($_GET['productId'] ?? null);

if ($assetId === 0 && $productId === 0) {
    die(json_encode(["message" => "Unable to process request. Please provide either assetId or productId."]));
}

if ($assetId === 0 && $productId !== 0) {
    $assetId = $productId;
}

$GetAssetInfo = $MainDB->prepare("SELECT id, name, moreinfo, creatorid, creatorname, createdon, updatedon, rsprice FROM asset WHERE id = :pid");
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
        "AssetId" => $AssetInfo2['id'],
        "ProductId" => $AssetInfo2['id'],
        "Name" => $AssetInfo2['name'],
    ];

    die(json_encode($AssetJSON, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}


$AssetJSON = [
    "AssetId" => $AssetInfo['id'],
    "ProductId" => $AssetInfo['id'],
    "Name" => $AssetInfo['name'],
    "Description" => $AssetInfo['moreinfo'],
    "AssetTypeId" => 0,
    "Creator" => [
        "Id" => $AssetInfo['creatorid'],
        "Name" => $AssetInfo['creatorname'],
    ],
    "IconImageAssetId" => $AssetInfo['id'],
    "Created" => $AssetInfo['createdon'],
    "Updated" => $AssetInfo['updatedon'],
    "PriceInRobux" => $AssetInfo['rsprice'],
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
?>
