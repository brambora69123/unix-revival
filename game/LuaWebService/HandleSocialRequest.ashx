<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header("content-type:text/plain");

error_reporting(0);
ini_set('display_errors', 0);

$method = filter_input(INPUT_GET, "method", FILTER_SANITIZE_STRING) ?? die(json_encode(["message" => "Cannot process request at this time."]));
$pid = filter_input(INPUT_GET, 'playerid', FILTER_SANITIZE_NUMBER_INT) ?? die(json_encode(["message" => "Cannot process request at this time."]));
$uid = filter_input(INPUT_GET, 'userid', FILTER_SANITIZE_NUMBER_INT);
$gid = filter_input(INPUT_GET, 'groupid', FILTER_SANITIZE_NUMBER_INT);

function getAdminIDs() {
    include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

    $stmt = $MainDB->prepare("SELECT id FROM users WHERE admin = 1");
    $stmt->execute();
    $adminIDs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $adminIDs;
}

function getInternIDs() {
    include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

    $stmt = $MainDB->prepare("SELECT id FROM users WHERE admin = 2");
    $stmt->execute();
    $internIDs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $internIDs;
}

$AdminList = getAdminIDs();
$InternList = getInternIDs();
$AdminGroup = 1200769;
$InternGroup = 2868472;

switch ($method){
    case "GetRankInGroup":
        switch(true){
            case (in_array($pid, $AdminList) && $gid == $AdminGroup):
                echo '<?xml version="1.0" encoding="UTF-8"?><Value Type="integer">255</Value>';
                break;
            case (in_array($pid, $InternList) && $gid == $InternGroup):
                echo '<?xml version="1.0" encoding="UTF-8"?><Value Type="integer">100</Value>';
                break;
            default:
                echo '<?xml version="1.0" encoding="UTF-8"?><Value Type="integer">0</Value>';
                break;
        }
        break;

        case "GetGroupRank":
            switch(true){
                case (in_array($pid, $AdminList) && $gid == $AdminGroup):
                    echo '<?xml version="1.0" encoding="UTF-8"?><Value Type="integer">255</Value>';
                    break;
                case (in_array($pid, $InternList) && $gid == $InternGroup):
                    echo '<?xml version="1.0" encoding="UTF-8"?><Value Type="integer">100</Value>';
                    break;
                default:
                    echo '<?xml version="1.0" encoding="UTF-8"?><Value Type="integer">0</Value>';
                    break;
            }
            break;

    case "IsBestFriendsWith":
        echo '<Value Type="boolean">false</Value>';
        break;

    case "IsFriendsWith":
        $Friends = $MainDB->prepare("SELECT * FROM friends WHERE user1 = ? AND user2 = ? OR user1 = ? AND user2 = ?");
        $Friends->execute(array($pid, $uid, $uid, $pid));
        $Results = $Friends->fetchAll();
        switch(true){
            case(!$Results):
                die('<?xml version="1.0" encoding="UTF-8"?><Value Type="boolean">false</Value>');
                break;
        }
        echo '<?xml version="1.0" encoding="UTF-8"?><Value Type="boolean">true</Value>';
        break;

    case "IsInGroup":
        switch(true){
            case (in_array($pid, $AdminList) && $gid == $AdminGroup):
            case (in_array($pid, $InternList) && $gid == $InternGroup):
                echo '<?xml version="1.0" encoding="UTF-8"?><Value Type="boolean">true</Value>';
                break;
            default:
                echo '<?xml version="1.0" encoding="UTF-8"?><Value Type="boolean">false</Value>';
                break;
        }
        break;

    default:
        die(json_encode(["message" => "Cannot process request at this time."]));
        break;
}
?>