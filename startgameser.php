<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$acckey = ($_GET['acckey'] ?? die("incorrect key"));
$placeId = $_GET['id'];
if ($acckey !== $AccessKey) {
	die("incorrect key");
}
$vipowner = (int) ($_GET['VipOwner'] ?? null);
$url = "http://unixfr.xyz/soapy/unix/Roblox/startgame2017.php?id=" . $placeId."&acckey=".$acckey;
if ($vipowner !== null && $vipowner !== 0) {
	$url = "http://unixfr.xyz/soapy/unix/Roblox/startgame2017.php?id=".$vipowner."&id=" . $placeId;
}

    file_get_contents($url);
	
	
exit();
