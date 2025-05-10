<?php
header('Content-Type: application/json; charset=utf-8');
include_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');

$url = $_SERVER['REQUEST_URI'];
$testurl = preg_replace('#[^0-9-./]#', '', $url);
$testurl = substr($testurl, 4);
  
preg_match_all('#/([^/]*)#', $testurl, $matches);

$testurl = rtrim($testurl, "/");


    $data = array('ChatFilter' => 'blacklist');
    echo json_encode($data);  
?>