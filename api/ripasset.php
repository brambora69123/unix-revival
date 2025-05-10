<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');

$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

if ($RBXTICKET == null) {
    header("Location: " . $baseUrl . "/");
    die();
}

if ($admin < 1) {
    header("Location: " . $baseUrl . "/");
    die();
}

if (isset($_POST['asset']) && isset($_POST['price'])) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

    $AssetID = (int)$_POST['asset'];

    function fetchAssetDetails($AssetID) {
        $assetURL = 'https://economy.roblox.com/v2/assets/' . $AssetID . '/details';
        $assetContent = file_get_contents($assetURL);

        if ($assetContent !== false) {
            return json_decode($assetContent, true);
        }

        return null;
    }

    $assetDetails = fetchAssetDetails($AssetID);

    if ($assetDetails) {
        if (isset($assetDetails['Name'], $assetDetails['Description'])) {
            $elname = $assetDetails['Name'];
            $description = $assetDetails['Description'];
            $atype = $assetDetails['AssetTypeId'];

            switch ($atype) {
                case 9:
                    $tip = "Place";
                    break;
                case 8:
                case 41:
                    $tip = "Hat";
                    break;
                case 11:
                case 12:
                    $tip = "Clothing";
                    break;
                case 3:
                    $tip = "Audio";
                    break;
                case 19:
                    $tip = "Gear";
                    break;
                case 2:
                case 18:
                    $tip = "TShirt";
                    break;
                case 27:
                case 28:
                case 29:
                case 30:
                case 31:
                    $tip = "BodyPart";
                    break;
                case 17:
                    $tip = "Head";
                    break;
                default:
                    die("Unsupported asset type. Womp womp");
            }

            $moneymoney = $_POST['price'];

            try {
                $stmt = $MainDB->prepare("INSERT INTO asset (name, moreinfo, creatorname, creatorid, createdon, rsprice, itemtype) VALUES (:name, :description, :creatorn, :creatorid, :createdon, :moneymoney, :itemtype)");

                $stmt->bindParam(':name', $elname);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':creatorn', $name);
                $stmt->bindParam(':createdon', date("d/m/Y"));
                $stmt->bindParam(':creatorid', $id);
                $stmt->bindParam(':moneymoney', $moneymoney);
                $stmt->bindParam(':itemtype', $tip);

                $stmt->execute();

                $lastInsertId = $MainDB->lastInsertId();

                $assetURL = 'http://assetdelivery.roblox.com/v1/asset?id=' . $_POST['asset'];
                $assetContent = file_get_contents($assetURL);

                if ($assetContent === false) {
                    die("Failed to fetch asset content from {$assetURL}");
                }

                $firstLine = strtok($assetContent, "\n");

                if (strpos(trim($firstLine), '<roblox') === 0 && strpos(trim($firstLine), '>') === false) {
                    $localFilePath = $_SERVER["DOCUMENT_ROOT"] . "/asset/" . $lastInsertId;
                    file_put_contents($localFilePath, $assetContent);
                    echo "Asset content saved successfully as raw data.";
                } else {
                    libxml_use_internal_errors(true);
                    $xml = simplexml_load_string($assetContent);
                    if ($xml === false) {
                        $errors = libxml_get_errors();
                        foreach ($errors as $error) {
                            echo "XML Error: {$error->message}\n";
                            echo "XML Error Line: " . getLineWithError($assetContent, $error) . "\n";
                        }
                        libxml_clear_errors();
                        die("Failed to parse fetched content as XML");
                    }
                    $localFilePath = $_SERVER["DOCUMENT_ROOT"] . "/asset/" . $lastInsertId;
                    $xml->asXML($localFilePath);
                    echo "XML content saved successfully.";
                }

                $elurl = 0;
                if ($atype == 9) {
                    $tip = "Place";
                    $placeimg = $assetDetails['IconImageAssetId'];
                    $elurl = 'https://unixfr.xyz/Thumbs/AssetIcon.ashx?id=' . $placeimg . '&idtobe=' . $lastInsertId;
                    $assetContente2 = file_get_contents($elurl);
                } elseif ($atype == 8 || $atype == 41) {
                    $tip = "Hat";
                    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/itemrender?id=" . $lastInsertId;
                } elseif ($atype == 11 || $atype == 12) {
                    $tip = "Clothing";
                    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/clothingrender?id=" . $lastInsertId;
                } elseif ($atype == 19) {
                    $tip = "Gear";
                    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/gearrender?id=" . $lastInsertId;
                } elseif ($atype == 2 || $atype == 18) {
                    $tip = "TShirt";
                    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/decal?id=" . $lastInsertId;
                } elseif ($atype == 27 || $atype == 28 || $atype == 29 || $atype == 30 || $atype == 31) {
                    $tip = "BodyPart";
                    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/bodypart?id=" . $lastInsertId;
                } elseif ($atype == 17) {
                    $tip = "Head";
                    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/head?id=" . $lastInsertId;
                }

                if ($elurl != 0) {
                    $assetContente = file_get_contents($elurl);
                }

                sendLog("An asset with the id of " . $lastInsertId . " was created by " . $name . " via the asset ripper!");

                header("Location: https://unixfr.xyz/supersecretadminpanel/assetcreation");
                exit();

            } catch (PDOException $e) {
                die("Error: " . $e->getMessage());
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }
        } else {
            die("Unable to extract valid 'Name' and 'Description' from JSON.");
        }
    }
}

function getLineWithError($content, $error) {
    $line = 0;
    $lines = explode("\n", $content);
    $errorLine = $error->line - 1;

    if (isset($lines[$errorLine])) {
        $line = $lines[$errorLine];
    }

    return $line;
}
?>
