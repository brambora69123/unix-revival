<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');

$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;

if ($RBXTICKET == null) {
    die(header("Location: " . $baseUrl . "/"));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['naem']) && isset($_FILES["placefile"]) && isset($_POST["year"])) {
        $name = htmlspecialchars($_POST['naem'], ENT_QUOTES, 'UTF-8');
        $year = (int) $_POST['year'];

        if (preg_match('/<script\b[^>]*>(.*?)<\/script>/i', $name)) {
            die(header("Location: {$baseUrl}/media/videos/haha.mp4"));
        }

        $query = "SELECT * FROM asset WHERE creatorid = :id AND itemtype = 'Place'";
        $stmt = $MainDB->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 25) {
            die("You have created too many games! The limit is 25.");
        }
		$GetInfoee = $MainDB->prepare("SELECT id, name, ticket, robux, status, about, nextrobuxgive, termtype, treason, toi, tnote, displayname, tdate, bannedAt, banEndsAt, email, emailverified, membership, friends, creationdate, phone, admin, theme, backgroundEnabled, lastGameUpload FROM users WHERE token = :token");
    $GetInfoee->execute([':token' => $RBXTICKET]);
    $Infoee = $GetInfoee->fetch(PDO::FETCH_ASSOC);

        $gameyear = $_POST["year"];
    
        $time = date("d/m/Y");
        $currenttime = time();
        if ($lastGameUpload + 43200 > $currenttime && $admin !== 1) {
            die("Please wait 12 hours before uploading a new game.");
        }
        
        $query = "
            INSERT INTO asset (name, approved, creatorname, creatorid, gameid, updatedon, address, createdon, public, itemtype, maxPlayers, year, lastGameJoin)
            VALUES (:name, 0, :cname, :cid, 'can we do this later ples', :updatedon, '191.96.208.35', :createdon, 0, 'Place', 20, :gameyear, :lgj);
        ";
        $creategamestmt = $MainDB->prepare($query);
        
        $creategamestmt->bindParam(":name", $name, PDO::PARAM_STR);
        $creategamestmt->bindParam(":cname", $Infoee['name'], PDO::PARAM_STR);
        $creategamestmt->bindParam(":cid", $id, PDO::PARAM_INT);
        $creategamestmt->bindParam(":updatedon", $time, PDO::PARAM_STR);
        $creategamestmt->bindParam(":createdon", $time, PDO::PARAM_STR);
        $creategamestmt->bindParam(":gameyear", $gameyear, PDO::PARAM_INT);
        $creategamestmt->bindParam(":lgj", $currenttime, PDO::PARAM_INT);
        
        $creategamestmt->execute();

        $leid = $MainDB->lastInsertId();

        $editgidstmt = $MainDB->prepare("UPDATE asset SET gameid = :gameid WHERE id = :id");
        $editgidstmt->bindParam(":gameid", $leid, PDO::PARAM_INT);
        $editgidstmt->bindParam(":id", $leid, PDO::PARAM_INT);
        $editgidstmt->execute();

        $uploadedFile = $_FILES["placefile"]["tmp_name"];
        $destination = $_SERVER["DOCUMENT_ROOT"] . "/asset/" . $leid;
        
        try {
            if (!move_uploaded_file($uploadedFile, $destination)) {
                throw new Exception("File upload failed");
            }
        } catch (Exception $e) {
            die("<b>ERROR!</b> " . $e->getMessage());
        }

        $query = "
            INSERT INTO places (createdat, filename, forgameid, creatorid)
            VALUES (:date, :filename, :gid, :cid)
        ";
        $stmt = $MainDB->prepare($query);
        $stmt->execute([
            ":date" => $time,
            ":filename" => $leid,
            ":gid" => $leid,
            ":cid" => $id
        ]);

        $stmt = $MainDB->prepare("UPDATE users SET lastGameUpload = :curtime WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":curtime", $currenttime, PDO::PARAM_INT);
        $stmt->execute();

        sendLog("A game with the id of " . $leid . " was created by " . $creatorName . " via the game uploader!");

        header("Location: " . $baseurl . "/api/validateplace?id=" . $leid);
    } else {
        die("Required data not set.");
    }
} else {
    die("Invalid request method.");
}
?>
