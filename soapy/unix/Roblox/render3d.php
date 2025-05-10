<?php
include_once 'Grid/Rcc/RCCServiceSoap.php';
include_once 'Grid/Rcc/Job.php';
include_once 'Grid/Rcc/ScriptExecution.php';
include_once 'Grid/Rcc/LuaType.php';
include_once 'Grid/Rcc/LuaValue.php';
include_once 'Grid/Rcc/Status.php';
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');

function base64DecodeToFile($filename, $base64_content, $directory) {
    $filepath = $directory . DIRECTORY_SEPARATOR . $filename;
    $dir = dirname($filepath);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    if (substr($filename, -4) === ".png") {
        $decoded_content = base64_decode($base64_content);
        file_put_contents($filepath, $decoded_content);
    } else {
        $decoded_content = base64_decode($base64_content);
        file_put_contents($filepath, $decoded_content);
    }

    echo "$filename has been extracted successfully.<br>";
}

function extractAndSaveFiles($jsonFilePath, $outputDirectory) {
    if (!file_exists($jsonFilePath)) {
        die("JSON file does not exist.");
    }

    $json_data = json_decode(file_get_contents($jsonFilePath), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error decoding JSON: " . json_last_error_msg());
    }

    if (!isset($json_data['files']) || !is_array($json_data['files'])) {
        die("Invalid JSON structure.");
    }

    foreach ($json_data['files'] as $filename => $file_info) {
        $content = isset($file_info['content']) ? $file_info['content'] : "";
        base64DecodeToFile($filename, $content, $outputDirectory);
    }
}

$id = $_GET["id"];
$redirect = ($_GET["redirect"] ?? true); // Default to true if not specified
$closeup = ($_GET['closeup'] ?? false);
$jobidNormal = "RENDER_3D_" . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
$elportus = rand(42000, 44000);
$normalrenderrcc = CreateRcc($elportus, 2017, $jobidNormal);
$RCCServiceSoap = new Roblox\Grid\Rcc\RCCServiceSoap("127.0.0.1", $elportus);

$path1 = $_SERVER['DOCUMENT_ROOT'] . '/renders/' . $id . '.rccraw.obj';

$newURL = "http://unixfr.xyz/home";

$jobNormal = new Roblox\Grid\Rcc\Job($jobidNormal, 60);

$scriptTextNormal = file_get_contents('./luascripts/avat3d.lua') . " return start(\"" . $id . "\",\"" . "http://unixfr.xyz" . "\");";
$scriptNormal = new Roblox\Grid\Rcc\ScriptExecution("Render", $scriptTextNormal);
$jobResultNormal = $RCCServiceSoap->BatchJobEx($jobNormal, $scriptNormal);

file_put_contents($path1, $jobResultNormal[0]);

sendLog("New 3D Render for User ID " . $id, "render", "https://unixfr.xyz/Thumbs/Avatar.ashx?userId=" . $id . "&v=" . rand(0, 100));

$wompwomp = RemoveRcc($jobidNormal);

// Extract and save files
$outputDir = $_SERVER['DOCUMENT_ROOT'] . '/renders/' . $id . '/';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}
extractAndSaveFiles($path1, $outputDir);

$RCCServiceSoap->CloseJob($jobNormal);
echo "3DRender job complete.<br>";