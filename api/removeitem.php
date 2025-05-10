<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
    case ($RBXTICKET == null):
        die(header('Location: ' . $baseUrl . '/'));
        break;
}
$WearItem = (int) ($_GET['id'] ?? die(header('Location: ' . $baseUrl . '/error')));

        $UpdateDB = $MainDB->prepare("UPDATE bought SET wearing = null WHERE boughtid=? AND boughtby=?")->execute([$WearItem, $id]);

        $url = "https://unixfr.xyz/soapy/unix/Roblox/render?id=" . urlencode($id);

        $content = file_get_contents($url);

        die(header('Location: ' . $baseUrl . '/avatar'));
