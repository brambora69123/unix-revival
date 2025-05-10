<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header("Content-Type: application/json");

$requestUri = $_SERVER['REQUEST_URI'];
$parts = explode('/', $requestUri);
$userId = end($parts);

echo json_encode(["ChatFilter" => "blacklist"]);
?>