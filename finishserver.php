<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
$id = $_GET['id'];
$job = $_GET['jobid'];
$port = $_GET['port'];

$GameFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid AND itemtype = 'place'");
$GameFetch->execute([":pid" => $id]);
$Results = $GameFetch->fetch(PDO::FETCH_ASSOC);

$updateStatusQuery = $MainDB->prepare("UPDATE open_servers SET status = 2 WHERE port = :port AND jobid = :jobid");
$updateStatusQuery->execute([":port" => $port, ":jobid" => $job]);
?>

