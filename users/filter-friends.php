<?php
header("Cache-Control: no-cache, no-store");
header("Pragma: no-cache");
header("Expires: -1");
header("Last-Modified: " . gmdate("D, d M Y H:i:s T") . " GMT");
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$rawPostData = file_get_contents('php://input');


$userId = null;
$otherUserIds = [];

parse_str($rawPostData, $postData);

if (isset($postData['userId'])) {
    $userId = intval($postData['userId']);
}

if (isset($postData['otherUserIds'])) {
    if (!is_array($postData['otherUserIds'])) {
        $otherUserIds[] = intval($postData['otherUserIds']);
    } else {
        $otherUserIds = array_map('intval', $postData['otherUserIds']);
    }
}


if (empty($userId) || empty($otherUserIds)) {
    echo json_encode(["error" => "Missing userId or otherUserIds"]);
    exit();
}

function getUserDetails($userId)
{
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    
    global $MainDB;
    $query = $MainDB->prepare("SELECT * FROM users WHERE id = :id");
    $query->bindParam(":id", $userId, PDO::PARAM_INT);
    $query->execute();
    
    return $query->fetch(PDO::FETCH_ASSOC);
}

function friendsWithUser($user1, $user2)
{
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    
    global $MainDB;
    $check = $MainDB->prepare("SELECT * FROM friends WHERE (user1 = :u AND user2 = :u2) OR (user1 = :ua AND user2 = :ua2)");
    $check->bindParam(":u", $user1, PDO::PARAM_INT);
    $check->bindParam(":u2", $user2, PDO::PARAM_INT);
    $check->bindParam(":ua", $user2, PDO::PARAM_INT);
    $check->bindParam(":ua2", $user1, PDO::PARAM_INT);
    $check->execute();
    
    return $check->rowCount() > 0;
}

$friendDetails = [];

foreach ($otherUserIds as $user) {
    if (friendsWithUser($userId, $user)) {
        $usr = getUserDetails($user);
        if ($usr) {
            $friendDetails[] = [
                "Id" => $usr['id'],
                "Username" => $usr['name'],
                "AvatarUri" => "https://unixfr.xyz/viewuser?id=" . $usr['id'],
                "AvatarFinal" => true,
                "IsOnline" => true
            ];
        }
    }
}

$json = json_encode($friendDetails);

file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/users/haigfeiijirg.txt', $json);

echo $json;
?>
