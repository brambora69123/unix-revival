<?php
include_once 'Grid/Rcc/RCCServiceSoap.php';
include_once 'Grid/Rcc/Job.php';
include_once 'Grid/Rcc/ScriptExecution.php';
include_once 'Grid/Rcc/LuaType.php';
include_once 'Grid/Rcc/LuaValue.php';
include_once 'Grid/Rcc/Status.php';
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');


$id = $_GET["id"];
$GameFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid");
$GameFetch->execute([":pid" => $id]);
$Results = $GameFetch->fetch(PDO::FETCH_ASSOC);


switch (true) {
    case (!$Results):
        die(header('Location: ' . $baseUrl . '/error'));
        break;
}


$jobidNormal = "RENDER_HEAD_" . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
$elportus = rand(42000, 44000);
$normalrenderrcc = CreateRcc($elportus, 2017 ,$jobidNormal);
$RCCServiceSoap=new Roblox\Grid\Rcc\RCCServiceSoap("127.0.0.1", $elportus);
$jobNormal = new Roblox\Grid\Rcc\Job($jobidNormal, 60);

$path1 = $_SERVER['DOCUMENT_ROOT'] . '/renderedassets/' . $id . '.png';

$newURL = "http://unixfr.xyz/home";


$customUrl = "e";


$scriptTextNormal = file_get_contents('./luascripts/head.lua') . " return start(\"" . $id . "\",\"" . "http://unixfr.xyz" . "\",\"" . $customUrl. "\");";
$scriptNormal = new Roblox\Grid\Rcc\ScriptExecution("Render", $scriptTextNormal);
$jobResultNormal = $RCCServiceSoap->BatchJobEx($jobNormal, $scriptNormal);


$imgNormal = base64_decode($jobResultNormal[0]);

file_put_contents($path1, $imgNormal);

$wompwomp = RemoveRcc($jobidNormal);

?>
