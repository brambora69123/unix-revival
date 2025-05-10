<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

function userExists($id)
{
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
	$get = $MainDB->prepare("SELECT * FROM users WHERE id = :i");
	$get->bindParam(":i", $id, PDO::PARAM_INT);
	$get->execute();
	if($get->rowCount() > 0) 
	{
		return true;
	}
	return false;
}


function areUsersFriends($user1, $user2)
{
	include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
	$check = $MainDB->prepare("SELECT * FROM friends WHERE (user1 = :u and user2 = :u2 OR user1 = :ua and user2 = :ua2)");
	$check->bindParam(":u", $user1, PDO::PARAM_INT);
	$check->bindParam(":u2", $user2, PDO::PARAM_INT);
	$check->bindParam(":ua", $user2, PDO::PARAM_INT);
	$check->bindParam(":ua2", $user1, PDO::PARAM_INT);
	$check->execute();
	if($check->rowCount() > 0) 
	{
		return true;
	}
	return false;
}
function CreateFriend($firstuserid, $seconduserid)
{
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

    if (!areUsersFriends($firstuserid, $seconduserid))
    {
        if (userExists($firstuserid) && userExists($seconduserid))
        {
            include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');


            $newfriend = $MainDB->prepare("INSERT into friends(id, user1, user2) VALUES(NULL, :u, :u2)");
            $newfriend->bindParam(":u", $firstuserid, PDO::PARAM_INT);
            $newfriend->bindParam(":u2", $seconduserid, PDO::PARAM_INT);
            $newfriend->execute();

            $updateUser1FriendsCount = $MainDB->prepare("UPDATE users SET friends = friends + 1 WHERE id = :u");
            $updateUser1FriendsCount->bindParam(":u", $firstuserid, PDO::PARAM_INT);
            $updateUser1FriendsCount->execute();

            $updateUser2FriendsCount = $MainDB->prepare("UPDATE users SET friends = friends + 1 WHERE id = :u");
            $updateUser2FriendsCount->bindParam(":u", $seconduserid, PDO::PARAM_INT);
            $updateUser2FriendsCount->execute();
        }
    }
}



$firstuser = $_GET['firstUserId'];
$seconduser = $_GET['secondUserId'];

CreateFriend($firstuser, $seconduser);