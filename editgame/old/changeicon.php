<?php 
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

$id = $_POST["gameid"] ?? die("enter a game id");

$getgameinfo = $MainDB->prepare("SELECT * FROM asset WHERE id = :id AND itemtype = 'Place'");
$getgameinfo->bindParam(":id", $id, PDO::PARAM_INT);
$getgameinfo->execute();
$results = $getgameinfo->fetch(PDO::FETCH_ASSOC);

if ($results["creatorname"] !== $name) {
    die("you dont own this game");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['iconfile'])) {
        // icon image
        $uploadedFile = $_FILES["iconfile"]["tmp_name"];
        $destination = $_SERVER["DOCUMENT_ROOT"] . "/renderedicons/". $id.".png";
        $mime = getimagesize($uploadedFile)["mime"];
        // trycatch to catch any errors
        try {
            if ($mime == "image/png") {
                if(move_uploaded_file($uploadedFile, $destination)) {
                    // file valid
                    header("Location: {$baseUrl}/editgame/?id={$id}&text=changes%20saved%20successfully!");
                } else {
                    die("nawww");
                }
            } else {
                
                die("no");
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