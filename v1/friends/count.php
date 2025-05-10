<?php

$userid = $_GET['userid'];

include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');
header("content-type: application/json");
if (!isset($userid)) {
    echo json_encode(array("error" => "No userid provided"));
    return;
}

$check = $MainDB->prepare("SELECT * FROM friends WHERE user1 = $userid OR user2 = $userid");
$check->execute();
$ActionRows = $check->fetchAll(PDO::FETCH_ASSOC);



// {"count": 69420}
echo json_encode(array("count" => count($ActionRows)));

?>