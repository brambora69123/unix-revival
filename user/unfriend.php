<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header("Content-Type: application/json");

function logData($data) {
	// hi
}

$input = file_get_contents('php://input');
logData("Input: " . $input);

logData("POST Data: " . json_encode($_POST));

$friendId = $_POST['friendUserId'] ?? null;
if (!$friendId) {
    $response = json_encode(["message" => "error"]);
    logData("Output: " . $response);
    die($response);
}

if (!isset($_COOKIE['ROBLOSECURITY'])) {
    $response = json_encode(["message" => "ROBLOSECURITY cookie not set"]);
    logData("Output: " . $response);
    die($response);
}

$roblosec = filter_var($_COOKIE['ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
logData("ROBLOSECURITY: " . $roblosec);

$usrquery = $MainDB->prepare("SELECT * FROM `users` WHERE `token` = :roblosec");
$usrquery->execute(['roblosec' => $roblosec]);
$usr = $usrquery->fetch(PDO::FETCH_ASSOC);

if ($usr) {
    $logged = true;
    $uID = $usr['id'];
    logData("User authenticated: ID " . $uID);
} else {
    $response = json_encode(["message" => "not authenticated"]);
    logData("Output: " . $response);
    die($response);
}

if ($uID == $friendId) {
    $response = json_encode(["message" => "you can't unfriend yourself"]);
    logData("Output: " . $response);
    die($response);
}

function userExists($id) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    global $MainDB;
    $get = $MainDB->prepare("SELECT * FROM users WHERE id = :i");
    $get->bindParam(":i", $id, PDO::PARAM_INT);
    $get->execute();
    $exists = $get->rowCount() > 0;
    logData("userExists: " . ($exists ? "true" : "false") . " for ID " . $id);
    return $exists;
}

function areUsersFriends($user1, $user2) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    global $MainDB;
    logData("Checking friendship status between user1: " . $user1 . " and user2: " . $user2);
    try {
        $check = $MainDB->prepare("SELECT * FROM friends WHERE (user1 = :u1 AND user2 = :u2) OR (user1 = :u3 AND user2 = :u4)");
        $check->bindParam(":u1", $user1, PDO::PARAM_INT);
        $check->bindParam(":u2", $user2, PDO::PARAM_INT);
        $check->bindParam(":u3", $user2, PDO::PARAM_INT);
        $check->bindParam(":u4", $user1, PDO::PARAM_INT);
        $check->execute();
        $friends = $check->rowCount() > 0;
        logData("areUsersFriends: " . ($friends ? "true" : "false") . " for user1: " . $user1 . " and user2: " . $user2);
        return $friends;
    } catch (PDOException $e) {
        logData("PDOException in areUsersFriends: " . $e->getMessage());
        return false;
    }
}

function unfriend($uID, $friendId) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    global $MainDB;

    logData("Attempting to unfriend user $friendId by $uID");

    if (userExists($uID) && userExists($friendId)) {
        logData("Both users exist");

        if (areUsersFriends($uID, $friendId)) {
            logData("Users are friends");

            logData("Deleting friend relationship");
            $deleteFriend = $MainDB->prepare("DELETE FROM friends WHERE (user1 = :u1 AND user2 = :u2) OR (user1 = :u3 AND user2 = :u4)");
            $deleteFriend->bindParam(":u1", $uID, PDO::PARAM_INT);
            $deleteFriend->bindParam(":u2", $friendId, PDO::PARAM_INT);
            $deleteFriend->bindParam(":u3", $friendId, PDO::PARAM_INT);
            $deleteFriend->bindParam(":u4", $uID, PDO::PARAM_INT);
            $deleteFriend->execute();

            logData("Updating friends count for user1");
            $updateUser1FriendsCount = $MainDB->prepare("UPDATE users SET friends = friends - 1 WHERE id = :u");
            $updateUser1FriendsCount->bindParam(":u", $uID, PDO::PARAM_INT);
            $updateUser1FriendsCount->execute();

            logData("Updating friends count for user2");
            $updateUser2FriendsCount = $MainDB->prepare("UPDATE users SET friends = friends - 1 WHERE id = :u");
            $updateUser2FriendsCount->bindParam(":u", $friendId, PDO::PARAM_INT);
            $updateUser2FriendsCount->execute();

            $response = json_encode(["success" => true]);
            logData("Output: " . $response);
            die($response);
        } else {
            $response = json_encode(["message" => "Users are not friends"]);
            logData("Output: " . $response);
            die($response);
        }
    } else {
        if (!userExists($uID)) {
            logData("User with ID $uID does not exist");
        }
        if (!userExists($friendId)) {
            logData("User with ID $friendId does not exist");
        }
        $response = json_encode(["message" => "One or both users do not exist"]);
        logData("Output: " . $response);
        die($response);
    }
}

unfriend($uID, $friendId);
?>
