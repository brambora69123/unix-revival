<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');

$GameId = (int)($_GET['gameid'] ?? die(header('Location: '. $baseUrl .'/')));
$GameFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid AND itemtype = 'place'");
$GameFetch->execute([":pid" => $GameId]);
$Results = $GameFetch->fetch(PDO::FETCH_ASSOC);
  
$TokenFetch = $MainDB->prepare("SELECT token, termtype FROM users WHERE id = :uid");
$TokenFetch->execute([":uid" => $id]);
$ResultsT = $TokenFetch->fetch(PDO::FETCH_ASSOC);

switch (true) {
    case (!$ResultsT):
        die(header('Location: ' . $baseUrl . '/'));
        break;
    case (!is_null($ResultsT['termtype'])):
        $errorResponse = ["error" => "User is banned."];
        die(json_encode($errorResponse));
        break;
    default:
        break;
}


$token = $ResultsT["token"];
$base64Token = base64_encode($token);

$gameargs = "1+launchmode:play+gameinfo:".$base64Token."+placelauncherurl:https://unixfr.xyz/game/PlaceLauncher.ashx?request=RequestGame&placeId=".$GameId."&token=".$token;


if ($Results['year'] == 2017) {
header("Location: unix17-player-unixfr:".$gameargs);
} elseif ($Results['year'] == 2019) {
	header("Location: unix19-player:".$gameargs);
} elseif ($Results['year'] == 2021) {
header("Location: unix21-player:".$gameargs);
} elseif ($Results['year'] == 2015) {
header("Location: unix15-player:".$gameargs);
}