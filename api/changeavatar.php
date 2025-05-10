<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

// Check if RBXTICKET is null
if ($RBXTICKET == null) {
    die(header('Location: ' . $baseUrl . '/'));
}

// Get the wear item type
$WearItem = (int) ($_GET['type'] ?? die(header('Location: ' . $baseUrl . '/error')));

// Set avatartype based on $WearItem value
switch ($WearItem) {
    case 1:
        $avatarType = 'R6';
        break;
    case 2:
        $avatarType = 'R15';
        break;
    default:
        die(header('Location: ' . $baseUrl . '/error'));
}

$UpdateDB = $MainDB->prepare("UPDATE users SET avatartype = ? WHERE id = ?");
$UpdateDB->execute([$avatarType, $id]);

$url = "https://unixfr.xyz/soapy/unix/Roblox/render?id=" . urlencode($id);
$content = file_get_contents($url);

die(header('Location: ' . $baseUrl . '/avatar'));
?>
