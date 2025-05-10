<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
$check = $MainDB->prepare("SELECT COUNT(*) as notification_count FROM notification WHERE userId = :id AND unread = 1");
$check->bindParam(":id", $id, PDO::PARAM_INT);
$check->execute();
$countResult = $check->fetch(PDO::FETCH_ASSOC);

$check2 = $MainDB->prepare("SELECT COUNT(*) as request_count FROM friend_requests WHERE user2 = :id AND unread = 1");
$check2->bindParam(":id", $id, PDO::PARAM_INT);
$check2->execute();
$countResult2 = $check2->fetch(PDO::FETCH_ASSOC);

$response = array('notificationcount' => $countResult['notification_count']);
$responseFriends = array('requestcount' => $countResult2['request_count']);

$responseTotal = $response + $responseFriends;
echo json_encode($response);
?>
