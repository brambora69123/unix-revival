<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header("content-type:text/plain");

$userId = (int)($_GET['userId'] ?? die(json_encode(["message" => "Cannot process request at this time."])));

echo json_encode(["success" => true, "message" => "Success", "isFollowing" => false]);





