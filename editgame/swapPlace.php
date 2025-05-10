<?php 
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

$gid = $_POST["gameid"] ?? die("Enter a game id");

$getgameinfo = $MainDB->prepare("SELECT * FROM asset WHERE id = :id AND itemtype = 'Place'");
$getgameinfo->bindParam(":id", $gid, PDO::PARAM_INT);
$getgameinfo->execute();
$results = $getgameinfo->fetch(PDO::FETCH_ASSOC);

if ($results["creatorname"] !== $name) {
    die("You don't own this game.");
}

$pid = null;

if (!isset($_POST["placenaem"])) {
    $pid = "Place";
} else {
    $pid = $_POST["placenaem"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['placefile'])) {
        // set the time var for creation dates
        $time = date("d/m/Y");
        // thumbnail image
        $uploadedFile = $_FILES["placefile"]["tmp_name"];
        $destination = $_SERVER["DOCUMENT_ROOT"] . "/asset/". $gid;
        //$mime = getimagesize($uploadedFile)["mime"];
        // trycatch to catch any errors
        try {
            
                if(move_uploaded_file($uploadedFile, $destination)) {
                    // file valid
                    /*$query = "
                INSERT INTO places (createdat, placename, filename, forgameid, creatorid)
                VALUES (:date, :placename, 1337, :gid, :cid)
            ";
                    $stmt = $MainDB->prepare($query);
                    $stmt->execute([":date" => $time, ":placename" => $pid, ":gid" => $gid, ":cid" => $id]);*/
                    header("Location: {$baseUrl}/editgame/?id={$gid}");
                } else {
                    die("nawww");
                }
            // set the sql query
          $query = "
          UPDATE asset
          updatedon = :date
          WHERE id = :id 
          AND itemtype = 'Place';
          ";
          $stmt = $MainDB->prepare($query);
          $stmt->execute([":date" => $time]);

          
          
        } catch (Exception $e) {
            die("<b>ERROR!</b> ".$e);
        }
          
    } else {
        die("nop");
    }
} else {
    die("nope");
}

?>