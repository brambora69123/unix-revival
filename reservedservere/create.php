<?php

exit; // DO NOT RUN THIS FILE, INSECURE!!!
// TODO: figure out what it does and fix it

include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$placeId = (int)$_GET['placeId'];
$url = "https://unixfr.xyz/soapy/unix/Roblox/game2019?id=" . $placeId . "&acckey=" . $AccessKey;
file_get_contents($url);