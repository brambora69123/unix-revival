<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');


function BreakFriend($firstuserid, $seconduserid)
{
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    
        $remove = $MainDB->prepare("DELETE FROM friends WHERE (user1 = :u and user2 = :u2 OR user1 = :ua and user2 = :ua2)");
        $remove->bindParam(":u", $firstuserid, PDO::PARAM_INT);
        $remove->bindParam(":u2", $seconduserid, PDO::PARAM_INT);
        $remove->bindParam(":ua", $seconduserid, PDO::PARAM_INT);
        $remove->bindParam(":ua2", $firstuserid, PDO::PARAM_INT);
        $remove->execute();

        $updateUser1FriendsCount = $MainDB->prepare("UPDATE users SET friends = friends - 1 WHERE id = :u");
        $updateUser1FriendsCount->bindParam(":u", $firstuserid, PDO::PARAM_INT);
        $updateUser1FriendsCount->execute();

        $updateUser2FriendsCount = $MainDB->prepare("UPDATE users SET friends = friends - 1 WHERE id = :u");
        $updateUser2FriendsCount->bindParam(":u", $seconduserid, PDO::PARAM_INT);
        $updateUser2FriendsCount->execute();
   
}


$firstuser = $_GET['firstUserId'];
$seconduser = $_GET['secondUserId'];

BreakFriend($firstuser, $seconduser);