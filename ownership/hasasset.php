<?php
header('Content-Type: application/json');
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

 function OwnsAsset(int $userid, $assetid)
        {
			include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

            $ownership = $MainDB->prepare("SELECT COUNT(*) FROM `bought` WHERE `boughtid` = :assetid AND `boughtby` = :userid");
            $ownership->bindParam(":assetid", $assetid, PDO::PARAM_INT);
            $ownership->bindParam(":userid", $userid, PDO::PARAM_INT);
            $ownership->execute();
            if($ownership->fetchColumn() > 0) {
                return true;
            }
            return false;
        }



function isOwner($id, $userid=NULL)
{
	include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
	if ($userid === NULL){
		die("E");
	}

	$check = $MainDB->prepare("SELECT * FROM asset WHERE id = :i");
	$check->bindParam(":i", $id, PDO::PARAM_INT);
	$check->execute();
	if ($check->rowCount() > 0)
	{
		$check = $check->fetch(PDO::FETCH_OBJ);
		$creatorid = $check->creatorid;


		if ($creatorid == $userid) {
			return true;
		}
	}
	return false;
}


$userid = $_GET['userId'];
$assetId = $_GET['assetId'];

if (OwnsAsset($userid, $assetId) || isOwner($assetId, $userid))
{
    echo(json_encode(true));
}
else
{
    echo(json_encode(false));
}