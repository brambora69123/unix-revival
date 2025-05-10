<?php
include_once 'Grid/Rcc/RCCServiceSoap.php';
include_once 'Grid/Rcc/Job.php';
include_once 'Grid/Rcc/ScriptExecution.php';
include_once 'Grid/Rcc/LuaType.php';
include_once 'Grid/Rcc/LuaValue.php';
include_once 'Grid/Rcc/Status.php';
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');


$porty = rand(5000, 10000);

$RCCServiceSoap = new Roblox\Grid\Rcc\RCCServiceSoap("127.0.0.1", 56217);
$id = "GAME_" . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
$job = new Roblox\Grid\Rcc\Job($id, 700000);


// If no open server with status 1 exists, proceed to create a new server
$scriptText = file_get_contents('./luascripts/gameserver2017.lua') . " start(\"" . $_GET['id'] . "\",\"" . $porty . "\",\"http://" . $_SERVER['SERVER_NAME'] . "\",\"" . $Results['creatorid'] . "\", '" . $isR15 . "');";
$script = new Roblox\Grid\Rcc\ScriptExecution("Game", $scriptText);
$jobResult = $RCCServiceSoap->OpenJobEx($job, $script);

// Insert the new server into the open_servers table with status 1



echo ($jobResult);
exit();
