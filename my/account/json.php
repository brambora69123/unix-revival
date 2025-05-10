<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
header('Content-Type: application/json; charset=UTF-8; X-Robots-Tag: noindex');
$roblosec = filter_var($_COOKIE['ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$usrquery = $MainDB->prepare("SELECT * FROM `users` WHERE `token` = :ROBLOSECURITY");
$usrquery->execute(['ROBLOSECURITY' => $roblosec]);
$usr = $usrquery->fetch();
$uID = $usr['id'];
$username = $usr['name'];
$robux = $usr['robux'];
$MembType = $usr['membership'];
if($MembType == 0){$MembValue = 0;}elseif($MembType == 1){$MembValue = 1;}elseif($MembType == 2){$MembValue = 2;}elseif($MembType == 3){$MembValue = 3;}
?>
{"UserId":<?=$uID;?>,"Username":"<?=$username;?>","DisplayName":"<?=$username;?>","HasPasswordSet":true,"Email":null,"AgeBracket":0,"Roles":[],"MembershipType":<?=$MembValue;?>,"RobuxBalance":<?=$robux;?>,"NotificationCount":0,"EmailNotificationEnabled":false,"PasswordNotificationEnabled":false}