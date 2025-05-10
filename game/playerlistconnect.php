<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');


$stmt = $MainDB->prepare("UPDATE `open_servers` SET `playerCount` = ? WHERE `jobid` = ?");
$stmt->execute([$_GET['players'],$_GET['jobid']]);

  ?>