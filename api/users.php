<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;

switch (true) {
  case ($RBXTICKET == null):
    header("Location: " . $baseUrl . "/");
    die();
    break;
  default:
    // Redirect to the video if $admin is not set to 1
    if ($admin < 1) {
      header("Location: " . $baseUrl . "/");
      die();
    }
    break;
}
$ActionFetch = $MainDB->prepare("SELECT name FROM users WHERE termtype = null");
$ActionFetch->execute();
$ActionRows = $ActionFetch->fetchAll();

foreach ($ActionRows as $GameInfo) {

  echo "{$GameInfo["name"]}<br>";
}

?>