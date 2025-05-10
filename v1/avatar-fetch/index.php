<?php
ini_set('display_errors', 0);

function mapTipToNumber($tip) {
    $tipToNumberMapping = [
        "Place" => 9,
        "Hat" => 8,
        "Shirt" => 11,
        "Pants" => 12,
        "Audio" => 3,
        "Gear" => 19,
        "TShirt" => 2,
        "Face" => 18,
        "Torso" => 27,
        "RightArm" => 28,
        "LeftArm" => 29,
        "LeftLeg" => 30,
        "RightLeg" => 31,
		"BodyPart" => 27,
		"Head" => 17
    ];

    if (isset($tipToNumberMapping[$tip])) {
        return $tipToNumberMapping[$tip];
    } else {
        die("Unsupported asset type, please tell Previous to add its item type! Itemtype:".$tip);
    }
}





include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header("Content-Type: application/json");
$UserId = (int)($_GET['userId'] ?? die(json_encode(["message" => "Cannot process this request at this time."])));
$WardrobeSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id AND itemtype != 'model' AND wearing = '1' AND itemtype != 'advertisement' AND itemtype != 'decal' AND itemtype != 'audio' ORDER BY id");
$WardrobeSrh->execute([":id" => $UserId]);
$ReWDS = $WardrobeSrh->fetchAll();



$placeId = (int)($_GET['placeId'] ?? 0);

if ($placeId > 0) {
    $placeQuery = $MainDB->prepare("SELECT avatartype FROM asset WHERE id = :placeId");
    $placeQuery->execute([':placeId' => $placeId]);
    $place = $placeQuery->fetch(PDO::FETCH_ASSOC);

    if ($place) {
        if ($place['avatartype'] == 'R6') {
            $resolvedAvatarType = 'R6';
        } elseif ($place['avatartype'] == 'R15') {
            $resolvedAvatarType = 'R15';
        } elseif ($place['avatartype'] == 'Choice') {
            $userQuery = $MainDB->prepare("SELECT avatartype FROM users WHERE id = :userId");
            $userQuery->execute([':userId' => $UserId]);
            $user = $userQuery->fetch(PDO::FETCH_ASSOC);

            if ($user && isset($user['avatartype'])) {
                $resolvedAvatarType = $user['avatartype'];
            } else {
                $resolvedAvatarType = 'R6';
            }
        } else {
            $resolvedAvatarType = 'R6';
        }
    } else {
        $resolvedAvatarType = 'R6';
    }
} else {
    $userQuery = $MainDB->prepare("SELECT avatartype FROM users WHERE id = :userId");
    $userQuery->execute([':userId' => $UserId]);
    $user = $userQuery->fetch(PDO::FETCH_ASSOC);

    if ($user && isset($user['avatartype'])) {
        $resolvedAvatarType = $user['avatartype'];
    } else {
        $resolvedAvatarType = 'R6';
    }
}

$i = 0;
$rendering = ($_GET['rendering'] ?? false);

$accessoryVersionIds = array();
$equippedGearVersionIds = array();

foreach ($ReWDS as $item) {
    $tip = mapTipToNumber($item['itemtype']);
    $accessoryVersionIds[] = array("assetId" => (int) $item['boughtid'], "assetTypeId" => $tip);

    if ($item['itemtype'] == "Gear") {
        $equippedGearVersionIds[] = array("assetId" => (int) $item['boughtid'], "assetTypeId" => $tip);
    }
}

foreach ($ReWDS as $item) {
    $packageId = $item['boughtid'];
    $packageQuery = $MainDB->prepare("SELECT * FROM package WHERE packageid = :packageId");
    $packageQuery->execute([':packageId' => $packageId]);
    $package = $packageQuery->fetch(PDO::FETCH_ASSOC);

    if ($package && isset($package['boughtid'])) {
        $tip = mapTipToNumber($package['itemtype']);
        $accessoryVersionIds[] = array("assetId" => (int) $package['boughtid'], "assetTypeId" => $tip);

        if ($package['itemtype'] == "Gear") {
            $equippedGearVersionIds[] = array("assetId" => (int) $package['boughtid'], "assetTypeId" => $tip);
        }
    }
}

$bc = $MainDB->prepare("SELECT * FROM body_colours WHERE uid = :u");
$bc->bindParam(":u", $UserId, PDO::PARAM_INT);
$bc->execute();

if ($bc->rowCount() > 0) {
    $bc = $bc->fetch(PDO::FETCH_OBJ);
    $h = $bc->h;
    $la = $bc->la;
    $t = $bc->t;
    $ra = $bc->ra;
    $ll = $bc->ll;
    $rl = $bc->rl;
} else {
    $h = $la = $t = $ra = $ll = $rl = 5;
}

function arrayUniqueMulti($array) {
    $temp = [];
    foreach ($array as $item) {
        if (!in_array($item, $temp)) {
            $temp[] = $item;
        }
    }
    return $temp;
}

$accessoryVersionIds = arrayUniqueMulti($accessoryVersionIds);
$equippedGearVersionIds = array_unique($equippedGearVersionIds);
if ($rendering != true) {
    $backpackgears = [];
} else {
    $backpackgears = $equippedGearVersionIds;
}
$data = array(
    "resolvedAvatarType" => $resolvedAvatarType,
    "equippedGearVersionIds" => $equippedGearVersionIds,
    "backpackGearVersionIds" => $backpackgears,
    "assetAndAssetTypeIds" => $accessoryVersionIds,
    "animations" => (object) ["Run" => 969731563],
    "bodyColorsUrl" => "http://www.unixfr.xyz/asset/BodyColors.ashx?userId=".$UserId,
    "bodyColors" => [
        "headColorId" => $h,
        "torsoColorId" => $t,
        "leftArmColorId" => $la,
        "rightArmColorId" => $ra,
        "leftLegColorId" => $ll,
        "rightLegColorId" => $rl
    ],
    "scales" => array(
        "Height" => 1.0000,
        "Width" => 1.0000,
        "Head" => 1.0000,
        "Depth" => 1.00,
        "Proportion" => 0.0000,
        "BodyType" => 0.0000
    )
);

$json = json_encode($data, JSON_UNESCAPED_SLASHES);
echo $json;
?>
