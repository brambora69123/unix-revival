<?php
include_once 'Grid/Rcc/RCCServiceSoap.php';
include_once 'Grid/Rcc/Job.php';
include_once 'Grid/Rcc/ScriptExecution.php';
include_once 'Grid/Rcc/LuaType.php';
include_once 'Grid/Rcc/LuaValue.php';
include_once 'Grid/Rcc/Status.php';
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER["DOCUMENT_ROOT"] . "/api/webhookstuff.php");

$job = $_GET['jobid'];
$user = $_GET['userid'];

$headers = getallheaders();
if (isset($headers['accesskey'])) {
    $access = $headers['accesskey'];
    if ($AccessKey == $access) {
        $e = 1;
    } else {
        die("Access key denied.");
    }
} else {
    $acckey = ($_GET['acckey'] ?? die("Access key missing"));
    if ($acckey !== $AccessKey) {
        die("Incorrect access key");
    }
}

try {
    $sql = "SELECT port FROM open_rccs WHERE jobid = :jobid";
    $stmt = $MainDB->prepare($sql);
    $stmt->bindParam(':jobid', $job, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        die("No RCC found for the given jobid");
    }

    $port = $row['port'];
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

$RCCServiceSoap = new Roblox\Grid\Rcc\RCCServiceSoap("127.0.0.1", $port);

$scriptTextNormal = file_get_contents('./luascripts/evict.lua') . "return start(\"" . $user . "\",\"" . "http://unixfr.xyz" . "\");";
$script = new Roblox\Grid\Rcc\ScriptExecution("EvictPlayer", $scriptTextNormal);

$jobResult = $RCCServiceSoap->Execute($job, $script);

sendLog("A user with the id " . $user . " was evicted from job " . $job . ".", "error");
exit();
?>
