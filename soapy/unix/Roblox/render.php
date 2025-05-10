<?php
include_once 'Grid/Rcc/RCCServiceSoap.php';
include_once 'Grid/Rcc/Job.php';
include_once 'Grid/Rcc/ScriptExecution.php';
include_once 'Grid/Rcc/LuaType.php';
include_once 'Grid/Rcc/LuaValue.php';
include_once 'Grid/Rcc/Status.php';
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');


$id = $_GET["id"];
$redirect = ($_GET["redirect"] ?? true); // Default to true if not specified
$closeup = ($_GET['closeup'] ?? false);
$jobidNormal = "RENDER_NORMAL_" . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
$jobidCloseup = "RENDER_CLOSEUP_" . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
$elportus = rand(42000, 44000);
$normalrenderrcc = CreateRcc($elportus, 2017 ,$jobidNormal);
$RCCServiceSoap=new Roblox\Grid\Rcc\RCCServiceSoap("127.0.0.1", $elportus);

$path1 = $_SERVER['DOCUMENT_ROOT'] . '/renders/' . $id . '.png';
$path2 = $_SERVER['DOCUMENT_ROOT'] . '/renders/' . $id . '-closeup' . '.png';

$newURL = "http://unixfr.xyz/home";

$jobNormal = new Roblox\Grid\Rcc\Job($jobidNormal, 60);

$jobCloseup = new Roblox\Grid\Rcc\Job($jobidCloseup, 60);

$scriptTextNormal = file_get_contents('./luascripts/avat.lua') . " return start(\"" . $id . "\",\"" . "http://unixfr.xyz" . "\");";
$scriptNormal = new Roblox\Grid\Rcc\ScriptExecution("Render", $scriptTextNormal);
$jobResultNormal = $RCCServiceSoap->BatchJobEx($jobNormal, $scriptNormal);

$scriptTextCloseup = file_get_contents('./luascripts/avatcloseup.lua') . " return start(\"" . $id . "\");";
$scriptCloseup = new Roblox\Grid\Rcc\ScriptExecution("Render", $scriptTextCloseup);
$jobResultCloseup = $RCCServiceSoap->BatchJobEx($jobCloseup, $scriptCloseup);

$imgNormal = base64_decode($jobResultNormal[0]);
$imgCloseup = base64_decode($jobResultCloseup[0]);

file_put_contents($path1, $imgNormal);
file_put_contents($path2, $imgCloseup);

$wompwomp = RemoveRcc($jobidNormal);
sendLog("A user with the id " . urlencode($id) . " was rendered!", "render", "https://www.unixfr.xyz/thumbs/avatar.ashx?userId=" . urlencode($id) . "&rnd=" . urlencode(rand(1, 1000000)) );
if ($redirect !== "false") {
	 $RCCServiceSoap->CloseJob($jobNormal);
	 $RCCServiceSoap->CloseJob($jobCloseup);
    header('Location: ' . $newURL);
} else {
    // Output the contents of the PNG files
		 $RCCServiceSoap->CloseJob($jobNormal);
	 $RCCServiceSoap->CloseJob($jobCloseup);
    header('Content-Type: image/png');
	if ($closeup == true) {
		
    readfile($path2);
	} else {
		    readfile($path1);
	}
}
?>
