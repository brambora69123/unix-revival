<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');
header("content-type: text/plain");
error_reporting(E_ALL);
ini_set('display_errors', 0);
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: -1");
header("Last-Modified: " . gmdate("D, d M Y H:i:s T") . " GMT");

$AssetID = (int)(($_GET['id'] ?? $_GET['assetversionid']) ?? die(json_encode(["message" => "Unable to process request."])));

function fetchAssetContent($AssetID) {
    $assetURL = 'https://assetdelivery.roblox.com/v1/asset/?id=' . $AssetID;
    $assetContent = file_get_contents($assetURL);
    if ($assetContent !== false) {
        return $assetContent;
    }
    return null;
}

$AssetFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid");
$AssetFetch->execute([":pid" => $AssetID]);
$Results = $AssetFetch->fetch(PDO::FETCH_ASSOC);

$AssetType = ($Results['itemtype'] ?? null);

$headers = getallheaders();

if ($Results != null) {
    if (isset($Results['itemtype']) && $Results['itemtype'] == "Place") {
        if (isset($headers['accesskey'])) {
            $access = $headers['accesskey'];
            if ($AccessKey == $access) {
                $e = 1;  // Access granted
            } else {
                die("Access key denied.");
            }
        } else {
            if (isset($_COOKIE['ROBLOSECURITY'])) {
                try {
                    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
   
                    // Step 3: Query the database
                    $token = $_COOKIE['ROBLOSECURITY'];
                    $stmt = $MainDB->prepare('SELECT * FROM users WHERE token = :token');
                    $stmt->bindParam(':token', $token);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($user && $Results['creatorid'] == $user['id']) {
                        $e = 1;  // User authorized
                    } else {
                        die("User not authorized to receive the asset.");
                    }
   
                } catch (PDOException $e) {
                    die("Database error: " . $e->getMessage());
                }
   
            } else {
                die("invalid");
            }
        }
    } else {
        $e = 1;  // Proceed for non-Place items
    }
}

switch ($AssetType) {
    case "CoreScript":
        switch (file_exists($_SERVER["DOCUMENT_ROOT"] . "/asset/" . $AssetID)) {
            case true:
                $file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/asset/" . $AssetID);
                sign("\r\n" . $file);
                break;
            default:
                die(json_encode(['message' => 'Requested asset was not found.']));
        }
        break;
    default:
        switch (file_exists($_SERVER["DOCUMENT_ROOT"] . "/asset/" . $AssetID)) {
            case true:
                $file = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/asset/" . $AssetID);
                header('Content-Disposition: attachment; filename="' . $AssetID . '"');
                echo $file;
                break;

            default:
                $assetURL = 'https://assetdelivery.roblox.com/v1/asset/?id=' . $AssetID;
                header('Location: ' . $assetURL);
                exit;
        }
        break;
}
?>
