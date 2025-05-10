<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header("content-type: application/json");

$userId = (int)($_GET['userId'] ?? die(json_encode(["message" => "Cannot process request at this time."])));
$follower = (int)($_GET['followerUserId'] ?? die(json_encode(["message" => "Cannot process request at this time."])));







$followquery = $MainDB->prepare("SELECT * FROM `following` WHERE `toid` = :toid AND `fromid` = :fromid");
$followquery->execute(['toid' => $userId, 'fromid' => $follower]);
$follow = $followquery->fetch();
if(is_array($follow)){
echo json_encode(["success" => true, "message" => "Success", "isFollowing" => true]);
}else{
echo json_encode(["success" => true, "message" => "Success", "isFollowing" => false]);
exit();
}