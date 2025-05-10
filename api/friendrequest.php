<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
    case ($RBXTICKET == null):
        die(header('Location: ' . $baseUrl . '/'));
        break;
}

$userSent = (int) ($_GET['id'] ?? die(header('Location: ' . $baseUrl . '/error')));

$checkUser = $MainDB->prepare("SELECT * FROM users WHERE id = :id");
$checkUser->execute([":id" => $userSent]);
$User = $checkUser->fetch(PDO::FETCH_ASSOC);

if ($User){

    $userid = $User["id"];

    if ($User["id"] != $id) {

        $getFriendRequestSent = $MainDB->prepare("SELECT * FROM friend_requests WHERE user1 = $id AND user2 = $userid");
        $getFriendRequestSent->execute();
        $friendRequestSent = $getFriendRequestSent->fetch(PDO::FETCH_ASSOC);

        if ($friendRequestSent) {

            $friend1Request = $friendRequestSent["user1"];
            $friend2Request = $friendRequestSent["user2"];
            $deleteFriendRequest = $MainDB->prepare("DELETE FROM friend_requests WHERE user1 = $friend1Request AND user2 = $friend2Request");
            $deleteFriendRequest->execute();

            /*$deleteFriendRequestNotification = $MainDB->prepare("DELETE FROM notification WHERE user1 = $friend1Request AND user2 = $friend2Request");
            $deleteFriendRequestNotification->execute();*/

            die(header('Location: '. $baseUrl .'/viewuser?id='.$userid.' '));

        } else{

            $getFriendRequestRecieve = $MainDB->prepare("SELECT * FROM friend_requests WHERE user1 = $userid AND user2 = $id");
            $getFriendRequestRecieve->execute();
            $friendRequestRecieve = $getFriendRequestRecieve->fetch(PDO::FETCH_ASSOC);

            if ($friendRequestRecieve) {

                $friend1Request = $friendRequestRecieve["user1"];
                $friend2Request = $friendRequestRecieve["user2"];
                $deleteFriendRequest = $MainDB->prepare("DELETE FROM friend_requests WHERE user1 = $friend1Request AND user2 = $friend2Request");
                $deleteFriendRequest->execute();

                /*$deleteFriendRequestNotification = $MainDB->prepare("DELETE FROM notification WHERE user1 = $friend1Request AND user2 = $friend2Request");
                $deleteFriendRequestNotification->execute();*/

                $addFriend = $MainDB->prepare("INSERT INTO friends (user1, user2) VALUES ($friend1Request, $friend2Request)");
                $addFriend->execute();
                die(header('Location: '. $baseUrl .'/viewuser?id='.$userid.' '));

            } else{

                $getFriend = $MainDB->prepare("SELECT * FROM friends WHERE user1 = $id OR user2 = $id
                                      INTERSECT
                                      SELECT * FROM friends WHERE user1 = $userid OR user2 = $userid;
                                    ");
                $getFriend->execute();
                $friend = $getFriend->fetch(PDO::FETCH_ASSOC);

                if ($friend) {

                    $friend1 = $friend["user1"];
                    $friend2 = $friend["user2"];
                    $deleteFriend = $MainDB->prepare("DELETE FROM friends WHERE user1 = $friend1 AND user2 = $friend2");
                    $deleteFriend->execute();
                    die(header('Location: '. $baseUrl .'/viewuser?id='.$userid.' '));

                } else{

                    $time = time();

                    $sendRequest = $MainDB->prepare("INSERT INTO friend_requests (user1, user2, time, unread) VALUES ($id, :id2, :unix, 1)");
                    $sendRequest->bindParam(":unix", $time, PDO::PARAM_STR);
                    $sendRequest->bindParam(":id2", $userSent, PDO::PARAM_STR);
                    $sendRequest->execute();

                    /*$sendRequestNotification = $MainDB->prepare("INSERT INTO notification (userid, user1, user2, time, type) VALUES ($userid, $id, $userid, $time, 'friend')");
                    $sendRequestNotification->execute();*/
                    die(header('Location: '. $baseUrl .'/viewuser?id='.$userid.' '));

                }

            }

            

        }


    } else{
        die("cant send friend request to self. idiot");
    }
} else{
    die("user doesn't exist");
}