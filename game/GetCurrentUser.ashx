<?php
include ($_SERVER['DOCUMENT_ROOT'].'/config.php');
$logged = false;
$roblosec = filter_var($_COOKIE['ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$usrquery = $MainDB->prepare("SELECT * FROM `users` WHERE `token` = :ROBLOSECURITY");
$usrquery->execute(['ROBLOSECURITY' => $roblosec]);
$usr = $usrquery->fetch();
if($usr != 0){
$logged = true;
}
$uID = $usr['id'];
echo$uID;
?>