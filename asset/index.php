<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/func.php');
header("content-type: text/plain");
error_reporting(E_ALL);
ini_set('display_errors', 0);
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: -1");
header("Last-Modified: " . gmdate("D, d M Y H:i:s T") . " GMT");

$AssetID = (int)(($_GET['id'] ?? $_GET['assetversionid']) ?? die(json_encode(["message" => "Unable to process request."])));
// Hello! -Previous
function fetchAssetContent($AssetID)
{
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
    if ($Results['itemtype'] == "Place") {
        if (isset($headers['accesskey'])) {
            $access = $headers['accesskey'];
            if ($AccessKey == $access) {
                $e = 1;
            } else {
                die("Access key denied.");
            }
        } else {
            die("Access key not in headers.");
        }
    } else {
        $e = 1;
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
                $assetContent = fetchAssetContent($AssetID);
                if ($assetContent !== null) {
                    header('Content-Disposition: attachment; filename="' . $AssetID . '"');
                    echo $assetContent;
                } else {
                    die(json_encode(['message' => 'Requested asset was not found.']));
                }
                break;
        }
        break;
}
