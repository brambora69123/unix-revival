<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header("Content-Type: application/json");

// Log function
function logData($data) {
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/user/aaaaaa.txt', $data . PHP_EOL, FILE_APPEND);
}

// Log the input
$input = file_get_contents('php://input');
logData("Input: " . $input);

// Log the POST data
logData("POST Data: " . json_encode($_POST));

$recipient = $_POST['recipientUserId'] ?? null;
if (!$recipient) {
    $response = json_encode(["message" => "error"]);
    logData("Output: " . $response);
    die($response);
}

// Check for the ROBLOSECURITY cookie
if (!isset($_COOKIE['ROBLOSECURITY'])) {
    $response = json_encode(["message" => "ROBLOSECURITY cookie not set"]);
    logData("Output: " . $response);
    die($response);
}

$roblosec = filter_var($_COOKIE['ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
logData("ROBLOSECURITY: " . $roblosec);

// Database query to verify user
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

if ($uID == $recipient) {
    $response = json_encode(["message" => "you can't friend yourself"]);
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
		$check->bindParam(":u3", $user1, PDO::PARAM_INT);
		$check->bindParam(":u4", $user2, PDO::PARAM_INT);
        $check->execute();
        $friends = $check->rowCount() > 0;
        logData("areUsersFriends: " . ($friends ? "true" : "false") . " for user1: " . $user1 . " and user2: " . $user2);
        return $friends;
    } catch (PDOException $e) {
        logData("PDOException in areUsersFriends: " . $e->getMessage());
        return false;
    }
}

function friendRequestExists($user1, $user2) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    global $MainDB;
    logData("Checking if friend request exists between user1: " . $user1 . " and user2: " . $user2);
    try {
        $check = $MainDB->prepare("SELECT * FROM friend_requests WHERE (user1 = :u1 AND user2 = :u2) OR (user1 = :u3 AND user2 = :u4)");
        $check->bindParam(":u1", $user1, PDO::PARAM_INT);
        $check->bindParam(":u2", $user2, PDO::PARAM_INT);
		$check->bindParam(":u3", $user1, PDO::PARAM_INT);
		$check->bindParam(":u4", $user2, PDO::PARAM_INT);
        $check->execute();
        $request = $check->fetch(PDO::FETCH_ASSOC);
        logData("friendRequestExists: " . ($request ? "true" : "false") . " for user1: " . $user1 . " and user2: " . $user2);
        return $request;
    } catch (PDOException $e) {
        logData("PDOException in friendRequestExists: " . $e->getMessage());
        return false;
    }
}

function createFriend($firstuserid, $seconduserid) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    global $MainDB;

    logData("Attempting to create friend relationship between $firstuserid and $seconduserid");

    if (!areUsersFriends($firstuserid, $seconduserid)) {
        logData("Users are not friends yet");

        if (userExists($firstuserid) && userExists($seconduserid)) {
            logData("Both users exist");

            $friendRequest = friendRequestExists($firstuserid, $seconduserid);

            if ($friendRequest) {
                logData("Friend request exists");

                if ($friendRequest['user2'] == $firstuserid) {
                    logData("Deleting friend request");
                    $deleteRequest = $MainDB->prepare("DELETE FROM friend_requests WHERE id = :id");
                    $deleteRequest->bindParam(":id", $friendRequest['id'], PDO::PARAM_INT);
                    $deleteRequest->execute();

                    logData("Inserting new friend relationship");
                    $newFriend = $MainDB->prepare("INSERT INTO friends (user1, user2) VALUES (:u1, :u2)");
                    $newFriend->bindParam(":u1", $firstuserid, PDO::PARAM_INT);
                    $newFriend->bindParam(":u2", $seconduserid, PDO::PARAM_INT);
                    $newFriend->execute();

                    logData("Updating friends count for user1");
                    $updateUser1FriendsCount = $MainDB->prepare("UPDATE users SET friends = friends + 1 WHERE id = :u");
                    $updateUser1FriendsCount->bindParam(":u", $firstuserid, PDO::PARAM_INT);
                    $updateUser1FriendsCount->execute();

                    logData("Updating friends count for user2");
                    $updateUser2FriendsCount = $MainDB->prepare("UPDATE users SET friends = friends + 1 WHERE id = :u");
                    $updateUser2FriendsCount->bindParam(":u", $seconduserid, PDO::PARAM_INT);
                    $updateUser2FriendsCount->execute();

                    $response = json_encode(["success" => true]);
                    logData("Output: " . $response);
                    die($response);
                } else {
                    $response = json_encode(["message" => "Only the recipient can accept the friend request"]);
                    logData("Output: " . $response);
                    die($response);
                }
            } else {
                logData("No existing friend request, creating a new one");
                $newFriendRequest = $MainDB->prepare("INSERT INTO friend_requests (user1, user2) VALUES (:u1, :u2)");
                $newFriendRequest->bindParam(":u1", $firstuserid, PDO::PARAM_INT);
                $newFriendRequest->bindParam(":u2", $seconduserid, PDO::PARAM_INT);
                $newFriendRequest->execute();

                $response = json_encode(["success" => true]);
                logData("Output: " . $response);
                die($response);
            }
        } else {
            if (!userExists($firstuserid)) {
                logData("User with ID $firstuserid does not exist");
            }
            if (!userExists($seconduserid)) {
                logData("User with ID $seconduserid does not exist");
            }
            $response = json_encode(["message" => "One or both users do not exist"]);
            logData("Output: " . $response);
            die($response);
        }
    } else {
        $response = json_encode(["message" => "Users are already friends"]);
        logData("Output: " . $response);
        die($response);
    }
}

createFriend($uID, $recipient);
?>
