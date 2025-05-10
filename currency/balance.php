<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

header("Content-Type: application/json");

if (isset($_COOKIE['ROBLOSECURITY'])) {
    $thecookie = $_COOKIE['ROBLOSECURITY'];

    $GetPlayerInfo = $MainDB->prepare("SELECT id, name, membership, admin, robux, ticket FROM users WHERE token = :token");
    $GetPlayerInfo->execute([':token' => $thecookie]);
    $PlayerInfo = $GetPlayerInfo->fetch(PDO::FETCH_ASSOC);

	
	$data = [
    "robux" => $PlayerInfo['robux'],
    "tickets" => $PlayerInfo['ticket']
];
} else {
	
	$data = [
    "robux" => 400,
    "tickets" => 400
];
}



$json = json_encode($data);

echo $json;
?>
