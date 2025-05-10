<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

function hasUserBadge($userid, $badgeid)
{
	include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

	$check = $MainDB->prepare("SELECT * FROM user_badges WHERE uid = :i AND bid = :bid AND isOfficial = 0");
	$check->bindParam(":i", $userid, PDO::PARAM_INT);
	$check->bindParam(":bid", $badgeid, PDO::PARAM_INT);
	$check->execute();
	if ($check->rowCount() > 0)
	{
		return true;
	}
	return false;
}


$userid = $_GET['UserID'];
$badgeid = $_GET['BadgeID'];

if (hasUserBadge($userid, $badgeid))
{
    echo "Success";
}