<?php 
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

$response = [
    "success" => true,
    "userList" => [],
    "total" => 0
];

echo json_encode($response);
?>
