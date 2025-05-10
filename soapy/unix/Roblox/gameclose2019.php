<?php
include_once 'Grid/Rcc/RCCServiceSoap.php';
include_once 'Grid/Rcc/Job.php';
include_once 'Grid/Rcc/ScriptExecution.php';
include_once 'Grid/Rcc/LuaType.php';
include_once 'Grid/Rcc/LuaValue.php';
include_once 'Grid/Rcc/Status.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/func.php';

$job = $_GET['job'];
$headers = getallheaders();


$deleteQuery = "DELETE FROM open_servers WHERE jobid = :jobId";
$deleteStatement = $MainDB->prepare($deleteQuery);
$deleteStatement->execute([':jobId' => $job]);

if (isset($headers['accesskey'])) {
    $access = $headers['accesskey'];
    if ($AccessKey == $access) {
        $e = 1;
    } else {
        die("Access key denied.");
    }
} else {
    $acckey = ($_GET['acckey'] ?? die("nah"));
    if ($acckey !== $AccessKey) {
        die("incorrect key");
    }
}

RemoveRcc($job);

sendLog("A 2019 server with the jobid of " . $job . " was closed.", "jobclosed");
