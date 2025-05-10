<?php
// this should be in soap, but that would be insecure
include_once $_SERVER['DOCUMENT_ROOT'] .'/soapy/unix/Roblox/Grid/Rcc/RCCServiceSoap.php';
include_once $_SERVER['DOCUMENT_ROOT'] .'/soapy/unix/Roblox/Grid/Rcc/Job.php';
include_once $_SERVER['DOCUMENT_ROOT'] .'/soapy/unix/Roblox/Grid/Rcc/ScriptExecution.php';
include_once $_SERVER['DOCUMENT_ROOT'] .'/soapy/unix/Roblox/Grid/Rcc/LuaType.php';
include_once $_SERVER['DOCUMENT_ROOT'] .'/soapy/unix/Roblox/Grid/Rcc/LuaValue.php';
include_once $_SERVER['DOCUMENT_ROOT'] .'/soapy/unix/Roblox/Grid/Rcc/Status.php';
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');
switch(true){case($RBXTICKET == null):die(header('Location: '. $baseUrl .'/'));break;}
$GameId = (int) ($_GET['id'] ?? die(header('Location: ' . $baseUrl . '/error')));

$redirected = $_GET['redirected'] ?? null;


$domain = "unixfr.xyz";

sleep(1);


$ihateeveryoneequally = "http://unixfr.xyz/api/validateplace?redirected=true&id=" . $GameId;


if ($redirected == null) {

    $ihateeveryoneequally .= '&redirected=true';
    $contents = file_get_contents($ihateeveryoneequally);


} else {
$e=1;
}





$id = "VALIDATE_" . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
$elportus = rand(42000, 44000);
$normalrenderrcc = CreateRcc($elportus, 2017 ,$id);;
$RCCServiceSoap = new Roblox\Grid\Rcc\RCCServiceSoap("127.0.0.1", $elportus);
$job = new Roblox\Grid\Rcc\Job($id, 700000);

$scriptText = file_get_contents($_SERVER['DOCUMENT_ROOT'] .'/soapy/unix/Roblox/./luascripts/validate.lua') . " return start(\"" . $GameId  . "\",\"" . $GameId  . "\");";
$script = new Roblox\Grid\Rcc\ScriptExecution("Game", $scriptText);



$jobResultCloseup = $RCCServiceSoap->BatchJobEx($job, $script);

$GameFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid AND itemtype = 'Place'");
$GameFetch->execute([":pid" => $GameId]);
$Results = $GameFetch->fetch(PDO::FETCH_ASSOC);


switch (true) {
    case (!$Results):
        die(header('Location: ' . $baseUrl . '/error'));
        break;
}


if ($jobResultCloseup == true) {
	$lastinsertid = $MainDB->lastInsertId();
	$stmt = $MainDB->prepare("UPDATE asset SET approved = 1, public = 0 WHERE id = :lastid");
	$stmt->bindParam(":lastid", $GameId, PDO::PARAM_INT);
	$stmt->execute();
	sendLog("A game was validated successfully! https://unixfr.xyz/viewgame?id={$GameId}");
	die(header("Location: https://unixfr.xyz/viewgame?id={$GameId}"));
} else {
	$error = true;
	sendLog("A game failed to validate.");
	die(header("Location: /failedtovalidate"));
}
$wompwomp = RemoveRcc($id);

exit();
?>
