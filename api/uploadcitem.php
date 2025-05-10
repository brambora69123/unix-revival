<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');
$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

// log("An asset with the id of ".$lastInsertId." was created by ".$name." via the asset ripper!");


switch (true) {
  case ($RBXTICKET == null):
    header("Location: " . $baseUrl . "/");
    die();
    break;
  default:
    if ($admin < 1) {
      header("Location: " . $baseUrl . "/");
      die();
    }
    break;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['naem']) && isset($_POST['daesc']) && isset($_POST["muhney"]) && isset($_POST["tayp"]) && isset($_FILES["assetfile"])) {
        // set the time var for creation dates
        $time = date("d/m/Y");
        // set item type
        $itemtayp = ucfirst($_POST["tayp"]);
        // set the sql query
        $query = "
        INSERT INTO asset (name, approved, creatorname, creatorid, updatedon, createdon, moreinfo, public, itemtype, rsprice, maxPlayers)
        VALUES (:name, 0, :cname, :cid, :updatedon, :createdon, :gdesc, 0, :itemtype, :cost, 20);
        ";
        // prepare the query
        $creategamestmt = $MainDB->prepare($query);
        // bind parameters
        $creategamestmt->bindParam(":name", $_POST["naem"], PDO::PARAM_STR);
        $creategamestmt->bindParam(":cname", $name, PDO::PARAM_STR);
        $creategamestmt->bindParam(":cid", $id, PDO::PARAM_INT);
        $creategamestmt->bindParam(":updatedon", $time, PDO::PARAM_STR);
        $creategamestmt->bindParam(":createdon", $time, PDO::PARAM_STR);
        $creategamestmt->bindParam(":gdesc", $_POST["daesc"], PDO::PARAM_STR);
        $creategamestmt->bindParam(":itemtype", $itemtayp, PDO::PARAM_STR);
        $creategamestmt->bindParam(":cost", $_POST["muhney"], PDO::PARAM_STR);
        // execute query and add the game to the sql
        $creategamestmt->execute();

        // get id of what was just uploaded
        $leid = $MainDB->lastInsertId();
		$lastInsertId = $MainDB->lastInsertId();
		$atype = $itemtayp;
		
		$tip = ""; // Initialize $tip to avoid undefined variable notice

 if ($atype == "Hat") {
    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/itemrender?id=" . $lastInsertId;
} elseif ($atype == "Shirt") {
    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/clothingrender?id=" . $lastInsertId;
} elseif ($atype == "Audio") {
    // No URL for Audio type
} elseif ($atype == "Gear") {
    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/gearrender?id=" . $lastInsertId;
} elseif ($atype == "TShirt") {
    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/tshirt?id=" . $lastInsertId;
} elseif ($atype == "BodyPart") {
    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/bodypart?id=" . $lastInsertId;
} elseif ($atype == "Head") {
    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/head?id=" . $lastInsertId;
} elseif ($atype == "Face") {
    $elurl = "https://unixfr.xyz/soapy/unix/Roblox/decal?id=" . $lastInsertId;
}
 if ($elurl != 0) {
            $assetContente = file_get_contents($elurl);
			}
// Add any additional logic needed after setting $elurl


        // upload the desired rbxl
        $uploadedFile = $_FILES["assetfile"]["tmp_name"];
        $destination = $_SERVER["DOCUMENT_ROOT"] . "/asset/". $leid;
        
        // trycatch to catch any errors
        try {
            move_uploaded_file($uploadedFile, $destination);
        } catch (Exception $e) {
            die("<b>ERROR!</b> ".$e);
        }
        
		sendLog("An asset with the id of ".$leid." was created by ".$name." via the custom item uploader!");
		
        // done
		header("Location: https://unixfr.xyz/supersecretadminpanel/assetcreation");

        
    } else {
        die("no");
    }
} else {
    die("no");
}

?>