<?php
include_once 'Grid/Rcc/RCCServiceSoap.php';
include_once 'Grid/Rcc/Job.php';
include_once 'Grid/Rcc/ScriptExecution.php';
include_once 'Grid/Rcc/LuaType.php';
include_once 'Grid/Rcc/LuaValue.php';
include_once 'Grid/Rcc/Status.php';
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');


$GameId = (int) ($_GET['id'] ?? die(header('Location: ' . $baseUrl . '/RobloxDefaultErrorPage.aspx')));
$vipowner = (int) ($_GET['vipowner'] ?? null);


$acckey = ($_GET['acckey'] ?? die("nah"));
$GameFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid AND itemtype = 'place'");
$GameFetch->execute([":pid" => $GameId]);
$Results = $GameFetch->fetch(PDO::FETCH_ASSOC);
if ($acckey !== $AccessKey) {
	die("incorrect key");
}
$domain = "unixfr.xyz";

switch (true) {
    case (!$Results):
        die(header('Location: ' . $baseUrl . '/RobloxDefaultErrorPage.aspx'));
        break;
}

$id = "GAME_" . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
$elportus = rand(42000, 44000);
$normalrenderrcc = CreateRcc($elportus, 2021 ,$id);

if ($Results['iscool'] == 1) {
    $isR15 = 1;
} else {
    $isR15 = 0;
}


if ($Results['year'] == 2017) {
    exit();
} else {
    $isR15 = 0;
}
echo $Results['iscool'];

$porty = rand(5000, 10000);
$RCCServiceSoap = new Roblox\Grid\Rcc\RCCServiceSoap("127.0.0.1", $elportus);
$job = new Roblox\Grid\Rcc\Job($id, 700000);



$scriptText = '
{
    "Mode": "GameServer",
    "Settings": {
        "PlaceId": '.$GameId.',
        "CreatorId": '.$Results['creatorid'].',
        "GameId": "'.$id.'",
        "MachineAddress": "45.131.65.54",
        "MaxPlayers": '.$Results['maxPlayers'].',
        "GsmInterval": 5,
        "MaxGameInstances": 5,
        "PreferredPlayerCapacity": '.$Results['maxPlayers'].',
        "UniverseId": '.$GameId.',
        "BaseUrl": "unixfr.xyz",
        "PlaceFetchUrl": "http://www.unixfr.xyz/asset/?id='.$GameId.'",
        "MatchmakingContextId": 1,
        "CreatorType": "User",
        "PlaceVersion": 1,
        "JobId": "'.$id.'",
        "PreferredPort": '.$porty.',
        "ApiKey": "569hNhfIoyxcm9xDHOgD",
        "PlaceVisitAccessKey": "8lKdX1t4YWfLUpc7D6le"
    }
}';
$script = new Roblox\Grid\Rcc\ScriptExecution("Game", $scriptText);



$checkServerQuery = $MainDB->prepare("SELECT COUNT(*) FROM open_servers WHERE gameid = :gameid AND status = 1");
$checkServerQuery->execute([":gameid" => $GameId]);
$existingServersCount = $checkServerQuery->fetchColumn();

if ($existingServersCount > 0) {
    exit();
}

$jobResult = $RCCServiceSoap->OpenJobEx($job, $script);

if ($vipowner !== null && $vipowner !== 0) {
	$stmte = "INSERT INTO open_servers (jobid, gameid, status, maxPlayers, playerCount, port, vipID) VALUES (?, ?, 1, ?, 0, ?, ?)";
	$MainDB->prepare($stmte)->execute([$id, $GameId, $Results['maxPlayers'], $porty, $vipowner]);
} else {
$stmte = "INSERT INTO open_servers (jobid, gameid, status, maxPlayers, playerCount, port, vipID) VALUES (?, ?, 2, ?, 0, ?, NULL)";
$MainDB->prepare($stmte)->execute([$id, $GameId, $Results['maxPlayers'], $porty]);
}



if ($jobResult != null) {
    $updateStatusQuery = $MainDB->prepare("UPDATE open_servers SET status = 2 WHERE port = :port AND jobid = :jobid");
    $updateStatusQuery->execute([":port" => $porty, ":jobid" => $id]);
}
exit();
?>
