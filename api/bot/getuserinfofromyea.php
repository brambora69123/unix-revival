<?php 
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');

$code = $_GET["code"];

if (!isset($code)) {
    die("Nop!");
}  

$stmt = $MainDB->prepare("SELECT name, discordverified FROM users WHERE vercode = ?");
$stmt->execute([$code]);
$rows = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$rows) {
    die("Nop!");
}
var_dump($stmt->fetch(PDO::FETCH_ASSOC));

?>