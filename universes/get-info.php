<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header("content-type: application/json");
$universeId = (int) ($_GET['universeId'] ?? die('nah'));
$GameFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid AND itemtype = 'place'");
$GameFetch->execute([":pid" => $universeId]);
$Results = $GameFetch->fetch(PDO::FETCH_ASSOC);
$roblosec = filter_var($_COOKIE['_ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$usrquery = $MainDB->prepare("SELECT * FROM `users` WHERE `token` = :ROBLOSECURITY");
$usrquery->execute(['ROBLOSECURITY' => $roblosec]);
$usr = $usrquery->fetch();
if(!is_array($usr)){
$isallowed = false;
} else {
	if ($usr['id'] == $Results['creatorid'] OR $usr['admin'] == 1) {
			$isallowed = true;
	} else {
	$isallowed = false;
	}
}




if ($Results != false) {
	if ($Results['avatartype'] == "R6") {
	$avat = "MorphToR6";	
	} elseif ($Results['avatartype'] == "R15") {
	$avat = "MorphToR15";
	} else {
	$avat = "PlayerChoice";
	}
	$data = array(
	"Name" => $Results['name'],
	"Description" => $Results['moreinfo'],
	"RootPlace" => $Results['id'],
	"StudioAccessToApisAllowed" => true,
	"CurrentUserHasEditPermissions" => $isallowed,
	"UniverseAvatarType" => $avat
	);
	echo json_encode($data);
	
} else {
die("incorrect");	
}