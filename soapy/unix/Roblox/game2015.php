<?php
include_once 'Grid/Rcc/RCCServiceSoap.php';
include_once 'Grid/Rcc/Job.php';
include_once 'Grid/Rcc/ScriptExecution.php';
include_once 'Grid/Rcc/LuaType.php';
include_once 'Grid/Rcc/LuaValue.php';
include_once 'Grid/Rcc/Status.php';
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

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

if ($Results['iscool'] == 1) {
    $isR15 = 1;
} else {
    $isR15 = 0;
}


if ($Results['year'] == 2019) {
    exit();
} else {
    $isR15 = 0;
}
echo $Results['iscool'];

$porty = rand(5000, 10000);

$RCCServiceSoap = new Roblox\Grid\Rcc\RCCServiceSoap("127.0.0.1", 42345);
$id = "GAME_" . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
$job = new Roblox\Grid\Rcc\Job($id, 700000);

$scriptText = file_get_contents('./luascripts/gameserver2015.lua') . " start(\"" . $_GET['id'] . "\",\"" . $porty . "\",\"http://" . $domain . "\",\"" . $Results['creatorid'] . "\", '" . $isR15 . "');";
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
    $stmte = "INSERT INTO open_servers (jobid, gameid, status, maxPlayers, playerCount, port, vipID) VALUES (?, ?, 1, ?, 0, ?, NULL)";
    $MainDB->prepare($stmte)->execute([$id, $GameId, $Results['maxPlayers'], $porty]);
}

echo $jobResult;
