<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

function getUserBadgeInfo($id)
{
    global $MainDB;

    $check = $MainDB->prepare("SELECT * FROM badges WHERE id = :i");
    $check->bindParam(":i", $id, PDO::PARAM_INT);
    $check->execute();
    if ($check->rowCount() > 0) {
        return $check->fetch(PDO::FETCH_OBJ);
    }
    return false;
}

function rewardUserBadge($UserID, $BadgeID, $PlaceID)
{
    global $MainDB;

    $badge = getUserBadgeInfo($BadgeID);
    if ($badge !== FALSE && $badge->AwardingPlaceID == $PlaceID) {
        $rbadge = $MainDB->prepare("INSERT INTO user_badges(uid, bid, isOfficial, whenEarned) VALUES(:n, :d, 0, UNIX_TIMESTAMP())");
        $rbadge->bindParam(":n", $UserID, PDO::PARAM_INT);
        $rbadge->bindParam(":d", $BadgeID, PDO::PARAM_INT);
        $rbadge->execute();
        return true;
    }
    return false;
}

$userid = $_GET['UserID'] ?? null;
$badgeid = $_GET['BadgeID'] ?? null;
$placeid = $_GET['PlaceID'] ?? null;

if ($userid && $badgeid && $placeid) {
    $GetInfoee = $MainDB->prepare("SELECT * FROM users WHERE id = :token");
    $GetInfoee->execute([':token' => $userid]);
    $Infoee = $GetInfoee->fetch(PDO::FETCH_ASSOC);

    $GetInfofe = $MainDB->prepare("SELECT * FROM asset WHERE id = :token");
    $GetInfofe->execute([':token' => $placeid]);
    $Infof = $GetInfofe->fetch(PDO::FETCH_ASSOC);

    if ($Infoee && $Infof && rewardUserBadge($userid, $badgeid, $placeid)) {
        $badge = getUserBadgeInfo($badgeid);
        echo htmlspecialchars($Infoee['name'], ENT_QUOTES, 'UTF-8') . ' won ' . htmlspecialchars($Infof['creatorname'], ENT_QUOTES, 'UTF-8') . "'s \"" . htmlspecialchars($badge->name, ENT_QUOTES, 'UTF-8') . "\" award!";
    } else {
        echo 0;
    }
} else {
    echo "Invalid parameters.";
}
?>
