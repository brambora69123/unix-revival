<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');
$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;
//error_reporting(E_ERROR | E_WARNING | E_PARSE);



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
    if (isset($_POST['naem']) && isset($_POST['daesc']) && isset($_FILES["placefile"])) {
        // set the time var for creation dates
        $time = date("d/m/Y");
        // set the sql query
        $query = "
        INSERT INTO asset (name, approved, creatorname, creatorid, gameid, updatedon, address, createdon, moreinfo, public, itemtype, maxPlayers)
        VALUES (:name, 0, :cname, :cid, 'can we do this later ples', :updatedon, '191.96.208.35', :createdon, :gdesc, 0, 'Place', 20);
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
        // execute query and add the game to the sql
        $creategamestmt->execute();

        // get id of what was just uploaded
        $leid = $MainDB->lastInsertId();
        // change gameid because its not the same as id
        $editgidstmt = $MainDB->prepare("UPDATE asset SET gameid = :sex WHERE id = :omgsex");
        $editgidstmt->bindParam(":sex", $leid, PDO::PARAM_INT);
        $editgidstmt->bindParam(":omgsex", $leid, PDO::PARAM_INT);
        $editgidstmt->execute();

        // upload the desired rbxl
        $uploadedFile = $_FILES["placefile"]["tmp_name"];
        $destination = $_SERVER["DOCUMENT_ROOT"] . "/asset/". $leid;
        
        // trycatch to catch any errors
        try {
            move_uploaded_file($uploadedFile, $destination);
        } catch (Exception $e) {
            die("<b>ERROR!</b> ".$e);
        }
        // upload the images

        // icon image
        $uploadedFile = $_FILES["iconfile"]["tmp_name"];
        $destination = $_SERVER["DOCUMENT_ROOT"] . "/renderedicons/". $leid.".png";
        
        // trycatch to catch any errors
        try {
            move_uploaded_file($uploadedFile, $destination);
        } catch (Exception $e) {
            die("<b>ERROR!</b> ".$e);
        }

        // thumbnail image
        $uploadedFile = $_FILES["thumbnailfile"]["tmp_name"];
        $destination = $_SERVER["DOCUMENT_ROOT"] . "/renderedassets/". $leid.".png";
        
        // trycatch to catch any errors
        try {
            move_uploaded_file($uploadedFile, $destination);
        } catch (Exception $e) {
            die("<b>ERROR!</b> ".$e);
        }
        sendLog("A game with the id of ".$leid." was created by ".$name." via the game uploader!");
        // redirect to validator
        header("Location: ". $baseurl."/soapy/unix/Roblox/validateplace?id=".$leid);
        
        
    } else {
        die("no");
    }
} else {
    die("no");
}

?>