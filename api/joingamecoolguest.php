<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');

$GameId = (int)($_GET['gameid'] ?? die(header('Location: '. $baseUrl .'/')));
$GameFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid AND itemtype = 'place'");
$GameFetch->execute([":pid" => $GameId]);
$Results = $GameFetch->fetch(PDO::FETCH_ASSOC);



$gameargs = "1+launchmode:play+gameinfo:".$base64Token."+placelauncherurl:https://unixfr.xyz/game/PlaceLauncher.ashx?request=RequestGame&placeId=".$GameId."&token=e";


if ($Results['year'] == 2017) {
header("Location: unix17-player-unixfr:".$gameargs);
} else {
	header("Location: unix19-player:".$gameargs);
}