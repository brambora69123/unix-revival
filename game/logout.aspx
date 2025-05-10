<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config.php');
    setcookie(".ROBLOSECURITY", $roblosec, time() - 24 * 60 * 60, "/");
	setcookie("ROBLOSECURITY", $roblosece, time() - 24 * 60 * 60, "/");
	setcookie("_ROBLOSECURITY", $roblosecee, time() - 24 * 60 * 60, "/");

?>

<div style='margin:10px;padding:4px;color:#333333;'>Logged out! <META http-equiv=refresh content=1;URL=/index.php></div>