<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php'); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php'); ?>
<?php
switch(true){case($RBXTICKET == null):die(header('Location: '. $baseUrl .'/'));break;}


$url = "https://unixfr.xyz/soapy/unix/Roblox/render?id=" . urlencode($id);

$content = file_get_contents($url);


?>
