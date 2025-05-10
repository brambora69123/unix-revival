<?php
include ($_SERVER['DOCUMENT_ROOT'].'/config.php');

function log_error($message) {
    $logFile = $_SERVER['DOCUMENT_ROOT'] . '/Data/58295839.txt';
    $logMessage = "[" . date('Y-m-d H:i:s') . "] " . $message . "\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
}

try {
    $logged = false;
    $usr = null;

    if (isset($_COOKIE['ROBLOSECURITY'])) {
        $roblosec = filter_var($_COOKIE['ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
        $usrquery = $MainDB->prepare("SELECT * FROM `users` WHERE `token` = :ROBLOSECURITY");
        $usrquery->execute(['ROBLOSECURITY' => $roblosec]);
        $usr = $usrquery->fetch(PDO::FETCH_ASSOC);

        if ($usr !== false) {
            $logged = true;
        }
    }

    $assetId = isset($_GET['assetid']) ? (int)$_GET['assetid'] : 0;
    $type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

    if (empty($type)) {
        $itemtypeQuery = $MainDB->prepare("SELECT itemtype FROM `asset` WHERE `id` = :id");
        $itemtypeQuery->execute(['id' => $assetId]);
        $itemtypeResult = $itemtypeQuery->fetch(PDO::FETCH_ASSOC);

        if ($itemtypeResult === false) {
            throw new Exception("Asset not found");
        }

        $itemtype = $itemtypeResult['itemtype'];

        if (strcasecmp($itemtype, "Place") !== 0) {
            throw new Exception("Invalid asset type");
        }
    } else {
        $itemtype = $type;
    }

    if (strcasecmp($itemtype, "Place") === 0) {
        $uID = ($usr !== false) ? $usr['id'] : 0;

        $gamequery = $MainDB->prepare("SELECT id FROM `asset` WHERE `id` = :id AND `creatorid` = :cid");
        $gamequery->execute(['id' => $assetId, 'cid' => $uID]);
        $game = $gamequery->fetch(PDO::FETCH_ASSOC);

        if (!$game) {
            throw new Exception("Game not found or unauthorized access");
        }

        $post = gzdecode(file_get_contents('php://input'));

        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/asset/' . $assetId;
        if (file_put_contents($filePath, $post) === false) {
            throw new Exception("Failed to save the file");
        }

        $date = date("d/m/Y");

        $updatedate = $MainDB->prepare("UPDATE `asset` SET `updatedon` = :date WHERE `id` = :assetId");
        $updatedate->execute(['date' => $date, 'assetId' => $assetId]);

        $response = ["success" => true];
        echo json_encode($response);
        exit();
    } else {
        throw new Exception("Type Error");
    }
} catch (Throwable $e) {
    log_error($e->getMessage());

    http_response_code(403);
    $response = ["success" => false, "message" => $e->getMessage()];
    echo json_encode($response);
    exit();
}
?>
