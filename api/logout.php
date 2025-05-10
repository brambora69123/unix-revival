<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php'); ?>
<?php
$returnUrl = ($_GET['rUrl'] ?? die(json_encode(['message' => 'Cannot process this request at this time.'])));

sendLog("User ".$name   ." logged out.");

switch (true) {
    case (isset($_COOKIE['ROBLOSECURITY'])):
        setcookie('ROBLOSECURITY', null, -1, '/', $_SERVER['SERVER_NAME']);
        header("Location: " . $baseUrl . $returnUrl);
        die();
        break;
    case (isset($_COOKIE['.ROBLOSECURITY'])):
        setcookie('.ROBLOSECURITY', null, -1, '/', $_SERVER['SERVER_NAME']);
        header("Location: " . $baseUrl . $returnUrl);
        die();
        break;
    default:
        header("Location: " . $baseUrl . $returnUrl);
        die();
        break;
}
?>