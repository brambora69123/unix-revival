<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');
$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;

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
    if (isset($_POST['naem']) && isset($_FILES["assetfile"]) && isset($_POST["assettype"])) {
        // SQL injection and XSS checks
        if (preg_match('/<script\b[^>]*>(.*?)<\/script>/i', $_POST['naem'])) {
            die(header("Location: {$baseUrl}/media/videos/haha.mp4"));
        }

        $query = "
        SELECT * FROM asset WHERE creatorid = :id AND itemtype = :assettype
        ";
        $stmt = $MainDB->prepare($query);

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":assettype", $_POST['assettype'], PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() > 25) {
            die("You have created too many assets of this type! The limit is 25.");
        }

        // set the time var for creation dates
        $time = date("d/m/Y");
        $currenttime = time();
        if ($lastGameUpload + 43200 > $currenttime && $admin !== 1) {
            die("Please wait 12 hours before uploading a new asset.");
        }

        // Determine file type
        $fileType = pathinfo($_FILES["assetfile"]["name"], PATHINFO_EXTENSION);
        $allowedFileTypes = [
            'rbxm' => ["Model", "Hat", "Shirt", "T-Shirt", "Pants", "Package", "LeftArm", "RightArm", "RightLeg", "LeftLeg", "Face"],
            'png' => ["Image", "Decal"],
            'jpg' => ["Image", "Decal"],
            'mp3' => ["Audio"],
            'rbxmx' => ["Model", "Hat", "Shirt", "T-Shirt", "Pants", "Package", "LeftArm", "RightArm", "RightLeg", "LeftLeg", "Face"],
            'webm' => ["Video"]
        ];

        if (!array_key_exists($fileType, $allowedFileTypes) || !in_array($_POST['assettype'], $allowedFileTypes[$fileType])) {
            die("Invalid file type or asset type.");
        }

        // set the sql query
        $query = "
        INSERT INTO asset (name, approved, creatorname, creatorid, gameid, updatedon, address, createdon, public, itemtype, maxPlayers, year, lastGameJoin)
        VALUES (:name, 0, :cname, :cid, 'not_applicable', :updatedon, '191.96.208.35', :createdon, 0, :assettype, 0, 0, :lgj);
        ";
        // prepare the query
        $createassetstmt = $MainDB->prepare($query);
        // bind parameters
        $createassetstmt->bindParam(":name", $_POST["naem"], PDO::PARAM_STR);
        $createassetstmt->bindParam(":cname", $name, PDO::PARAM_STR);
        $createassetstmt->bindParam(":cid", $id, PDO::PARAM_INT);
        $createassetstmt->bindParam(":updatedon", $time, PDO::PARAM_STR);
        $createassetstmt->bindParam(":createdon", $time, PDO::PARAM_STR);
        $createassetstmt->bindParam(":assettype", $_POST['assettype'], PDO::PARAM_STR);
        $createassetstmt->bindParam(":lgj", $currenttime, PDO::PARAM_INT);
        // execute query and add the asset to the sql
        $createassetstmt->execute();

        // get id of what was just uploaded
        $leid = $MainDB->lastInsertId();
        // change gameid because its not the same as id
        $editgidstmt = $MainDB->prepare("UPDATE asset SET gameid = :leid WHERE id = :leid");
        $editgidstmt->bindParam(":leid", $leid, PDO::PARAM_INT);
        $editgidstmt->execute();

        // upload the desired file
        $uploadedFile = $_FILES["assetfile"]["tmp_name"];
        $destination = $_SERVER["DOCUMENT_ROOT"] . "/asset/". $leid;
        

        $stmt = $MainDB->prepare("UPDATE users SET lastGameUpload = :curtime WHERE id = :lastid");
        $stmt->bindParam(":lastid", $id, PDO::PARAM_INT);
        $stmt->bindParam(":curtime", $currenttime, PDO::PARAM_INT);
        $stmt->execute();
        
        sendLog("An asset with the id of ".$leid." was created by ".$name." via the asset uploader!");
        // redirect to validator        
    } else {
        die("nop");
    }
} else {
    die("nope");
}
?>
