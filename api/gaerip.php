<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;

ini_set('display_errors', 0);


switch (true) {
  case ($RBXTICKET == null):
    header("Location: " . $baseUrl . "/");
    die();
    break;
  default:
    if ($admin != 2) {
      header("Location: " . $baseUrl . "/");
      die();
    }
    break;
}


if (isset($_POST['asset']) && isset($_POST['price'])) {

	include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

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


$version =($_POST['version'] ?? null);
if ($assetDetails) {

    if (isset($assetDetails['Name'], $assetDetails['Description'])) {
		include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

        $elname = $assetDetails['Name'];
        $description = $assetDetails['Description'];
		$atype = $assetDetails['AssetTypeId'];

        try {
			include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
			include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
             
			 
if ($atype == 9) {
    $tip = "Place";
} elseif ($atype == 8) {
    $tip = "Hat";
} elseif ($atype == 11) {
    $tip = "Shirt";
} elseif ($atype == 12) {
    $tip = "Pants";
} elseif ($atype == 3) {
    $tip = "Audio";
} elseif ($atype == 41) {
    $tip = "Hat";
} elseif ($atype == 19) {
    $tip = "Gear";
} elseif ($atype == 2) {
    $tip = "TShirt";
} elseif ($atype == 18) {
    $tip = "Face";
} elseif ($atype == 41) {
    $tip = "Hat";
} elseif ($atype == 27) {
    $tip = "Torso";
} elseif ($atype == 28) {
    $tip = "RightArm";
} elseif ($atype == 29) {
    $tip = "LeftArm";
} elseif ($atype == 30) {
    $tip = "LeftLeg";
} elseif ($atype == 31) {
    $tip = "RightLeg";
} else {
    die("Unsupported asset, please tell Previous to add its item type!");
}
$moneymoney = $_POST['price'];



            $stmt = $MainDB->prepare("SHOW TABLE STATUS LIKE 'asset'");
            $stmt->execute();
            $tableStatus = $stmt->fetch(PDO::FETCH_ASSOC);
            $nextAutoIncrement = $tableStatus['Auto_increment'];

          $stmt = $MainDB->prepare("INSERT INTO asset (name, moreinfo, creatorname, creatorid, createdon, rsprice, itemtype) VALUES (:name, :description, :creatorn, :creatorid, :createdon, :moneymoney, :itemtype)");

$stmt->bindParam(':name', $elname);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':creatorn', $name);
$stmt->bindParam(':createdon', date("d/m/Y"));
$stmt->bindParam(':creatorid', $id);
$stmt->bindParam(':moneymoney', $moneymoney); // Pass the variable by reference
$stmt->bindParam(':itemtype', $tip);

$stmt->execute();

            $lastInsertId = $MainDB->lastInsertId();

$assetURL = 'https://assetdelivery.roblox.com/v1/asset/?id=' . urlencode($AssetID);
if ($version !== null) {
    $assetURL = 'https://assetdelivery.roblox.com/v1/asset/?id=' . urlencode($AssetID).'&version=' . urlencode($version);
}
         
			$assetContent = file_get_contents($assetURL);
            $assetContent = str_replace('roblox.com', 'unixfr.xyz', $assetContent);

             
            $localFilePath = $_SERVER["DOCUMENT_ROOT"] . "/asset/" . $lastInsertId;
            file_put_contents($localFilePath, $assetContent);

              $elurl = 0;
if ($atype == 9) {
    $tip = "Place";
    $placeimg = $assetDetails['IconImageAssetId'];
    $elurl = 'https://unixfr.xyz/Thumbs/AssetIcon.ashx?id=' . $placeimg . '&idtobe=' . $lastInsertId;
    $assetContente2 = file_get_contents($elurl);
} elseif ($atype == 8) {
    $tip = "Hat";
    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/itemrender?id=" . $lastInsertId;
} elseif ($atype == 41) {
    $tip = "Hat"; // You can adjust this line if needed
    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/itemrender?id=" . $lastInsertId;
} elseif ($atype == 11 || $atype == 12) {
    $tip = "Shirt";
    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/clothingrender?id=" . $lastInsertId;
} elseif ($atype == 3) {
    $tip = "Audio";
} elseif ($atype == 19) {
    $tip = "Gear";
    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/gearrender?id=" . $lastInsertId;
} elseif ($atype == 2 || $atype == 18) {
    $tip = "TShirt";
    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/decal?id=" . $lastInsertId;
} elseif ($atype == 27 || $atype == 28 || $atype == 29 || $atype == 30 || $atype == 31) {
    $tip = "BodyPart";
    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/bodypart?id=" . $lastInsertId;
}


            if ($elurl != 0) {
            $assetContente = file_get_contents($elurl);
			}

           
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    } else {
        echo "Unable to extract valid 'Name' and 'Description' from JSON.";
    }
}


}
?>