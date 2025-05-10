<?php
session_start();
require ($_SERVER['DOCUMENT_ROOT'].'/config.php');
$roblosec = filter_var($_COOKIE['_ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$usrquery = $MainDB->prepare("SELECT * FROM `users` WHERE `token` = :ROBLOSECURITY");
$usrquery->execute(['ROBLOSECURITY' => $roblosec]);
$usr = $usrquery->fetch();
if(!is_array($usr)){
header("Location: https://www.unixfr.xyz/");
exit();
}
$uID = $usr['id'];
if(isset($_POST['followedUserId'])){
$friendId = (int)$_POST['followedUserId'];
}else{
$json = json_decode(file_get_contents("php://input"), true);
$friendId = (int)$json['targetUserId'];
}
$followquery = $MainDB->prepare("SELECT * FROM `following` WHERE `toid` = :toid AND `fromid` = :fromid");
$followquery->execute(['toid' => $friendId, 'fromid' => $uID]);
$follow = $followquery->fetch();
if(!is_array($follow)){
$sql = "INSERT INTO `following` (`toid`, `fromid`) VALUES (:friendId, :uID)";
$stmt = $MainDB->prepare($sql);
$stmt->bindValue(':friendId', $friendId, PDO::PARAM_INT);
$stmt->bindValue(':uID', $uID, PDO::PARAM_INT);
$stmt->execute();
$data = array('success' => 'true');
echo json_encode($data);
}else{
$data = array('success' => 'false');
echo json_encode($data);
exit();
}
?>