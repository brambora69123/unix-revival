<?php
include_once 'Grid/Rcc/RCCServiceSoap.php';
include_once 'Grid/Rcc/Job.php';
include_once 'Grid/Rcc/ScriptExecution.php';
include_once 'Grid/Rcc/LuaType.php';
include_once 'Grid/Rcc/LuaValue.php';
include_once 'Grid/Rcc/Status.php';

$id = $_GET["id"];


$RCCServiceSoap = new Roblox\Grid\Rcc\RCCServiceSoap("127.0.0.1", 42342);

$path1 = $_SERVER['DOCUMENT_ROOT'] . '/renderedassets/' . $id . '.png';

$newURL = "http://unixfr.xyz/home";

$jobidNormal = "RENDER_PLACE_" . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
$jobNormal = new Roblox\Grid\Rcc\Job($jobidNormal, 60);



$scriptTextNormal = file_get_contents('./luascripts/place.lua') . " return start(\"" . $id . "\",\"" . "http://unixfr.xyz" . "\");";
$scriptNormal = new Roblox\Grid\Rcc\ScriptExecution("Render", $scriptTextNormal);
$jobResultNormal = $RCCServiceSoap->BatchJobEx($jobNormal, $scriptNormal);


$imgNormal = base64_decode($jobResultNormal[0]);

file_put_contents($path1, $imgNormal);
