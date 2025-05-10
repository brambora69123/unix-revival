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

        $friendRequestFetch = $MainDB->prepare("SELECT * FROM friend_requests WHERE user1 = $userid AND user2 = $id");
        $friendRequestFetch->execute();
        $friendRequest = $friendRequestFetch->fetch(PDO::FETCH_ASSOC);

        if ($friendRequest) {
            $friendRequestDelete = $MainDB->prepare("DELETE FROM friend_requests WHERE user1 = $userid AND user2 = $id");
            $friendRequestDelete->execute();

            $friendRequestDeleteNotification = $MainDB->prepare("DELETE FROM notification WHERE user1 = $userid AND user2 = $id AND type = 'friend'");
            $friendRequestDeleteNotification->execute();

            die(header('Location: https://unixfr.xyz/friends#requests'));
        }


    } else{
        die("unable to ignore self");
    }
} else{
    die("user doesn't exist");
}