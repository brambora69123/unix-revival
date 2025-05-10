<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header("Content-Type: application/json");

$UserId = (int)($_GET['userId'] ?? die(json_encode(["message" => "Cannot process this request at this time."])));
$placeId = (int)($_GET['placeId'] ?? 0);

$resolvedAvatarType = 'R6';
if ($placeId > 0) {
    $placeQuery = $MainDB->prepare("SELECT avatartype, gearsAllowed FROM asset WHERE id = :placeId");
    $placeQuery->execute([':placeId' => $placeId]);
    $place = $placeQuery->fetch(PDO::FETCH_ASSOC);

    if ($place) {
        if ($place['avatartype'] == 'R6' || $place['avatartype'] == 'R15') {
            $resolvedAvatarType = $place['avatartype'];
        } elseif ($place['avatartype'] == 'Choice') {
            $userQuery = $MainDB->prepare("SELECT avatartype FROM users WHERE id = :userId");
            $userQuery->execute([':userId' => $UserId]);
            $user = $userQuery->fetch(PDO::FETCH_ASSOC);

            if ($user && isset($user['avatartype'])) {
                $resolvedAvatarType = $user['avatartype'];
            }
        }
    }
} else {
    $userQuery = $MainDB->prepare("SELECT avatartype FROM users WHERE id = :userId");
    $userQuery->execute([':userId' => $UserId]);
    $user = $userQuery->fetch(PDO::FETCH_ASSOC);

    if ($user && isset($user['avatartype'])) {
        $resolvedAvatarType = $user['avatartype'];
    }
}

$WardrobeSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id AND itemtype != 'model' AND wearing = '1' AND itemtype != 'advertisement' AND itemtype != 'decal' AND itemtype != 'audio' ORDER BY id");
$WardrobeSrh->execute([":id" => $UserId]);
$ReWDS = $WardrobeSrh->fetchAll();

$accessoryVersionIds = [];
$equippedGearVersionIds = [];

foreach ($ReWDS as $item) {
    if ($item['itemtype'] != 'Gear') {
        $accessoryVersionIds[] = (int)$item['boughtid'];
    } else {
        $equippedGearVersionIds[] = (int)$item['boughtid'];
    }

    $packageQuery = $MainDB->prepare("SELECT * FROM package WHERE packageid = :packageId");
    $packageQuery->execute([':packageId' => $item['boughtid']]);
    $package = $packageQuery->fetch(PDO::FETCH_ASSOC);

    if ($package && isset($package['boughtid'])) {
        $accessoryVersionIds[] = (int)$package['boughtid'];

        if ($package['itemtype'] == 'Gear') {
            $equippedGearVersionIds[] = (int)$package['boughtid'];
        }
    }
}

$bcQuery = $MainDB->prepare("SELECT * FROM body_colours WHERE uid = :u");
$bcQuery->bindParam(":u", $UserId, PDO::PARAM_INT);
$bcQuery->execute();
$bodyColors = $bcQuery->fetch(PDO::FETCH_OBJ);

if ($placeId > 0 && $place && isset($place['gearsAllowed']) && $place['gearsAllowed'] == 0) {
    $equippedGearVersionIds = [];
}

$accessoryVersionIds = array_unique($accessoryVersionIds);
$equippedGearVersionIds = array_unique($equippedGearVersionIds);

$rendering = ($_GET['rendering'] ?? false) != false;
$backpackgears = $rendering ? $equippedGearVersionIds : [];
if ($placeId > 0) {
	$backpackgears = $rendering ? $equippedGearVersionIds : [];

} else {
$backpackgears = $equippedGearVersionIds;	
}
$data = [
    "resolvedAvatarType" => $resolvedAvatarType,
    "equippedGearVersionIds" => $equippedGearVersionIds,
    "backpackGearVersionIds" => $backpackgears,
    "accessoryVersionIds" => $accessoryVersionIds,
    "animations" => (object)["Run" => 969731563],
    "bodyColorsUrl" => "http://www.unixfr.xyz/asset/BodyColors.ashx?userId=".$UserId,
    "scales" => [
        "Height" => 1.0000,
        "Width" => 1.0000,
        "Head" => 1.0000,
        "Depth" => 1.00,
        "Proportion" => 0.0000,
        "BodyType" => 0.0000
    ]
];

if ($bodyColors) {
    $data['bodyColors'] = [
        "HeadColor" => $bodyColors->h,
        "LeftArmColor" => $bodyColors->la,
        "TorsoColor" => $bodyColors->t,
        "RightArmColor" => $bodyColors->ra,
        "LeftLegColor" => $bodyColors->ll,
        "RightLegColor" => $bodyColors->rl
    ];
}

echo json_encode($data, JSON_UNESCAPED_SLASHES);
?>
