<?php 
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

$id = $_POST["gameid"] ?? die("Enter a game id");

$getgameinfo = $MainDB->prepare("SELECT * FROM asset WHERE id = :id AND itemtype = 'Place'");
$getgameinfo->bindParam(":id", $id, PDO::PARAM_INT);
$getgameinfo->execute();
$results = $getgameinfo->fetch(PDO::FETCH_ASSOC);

if ($results["creatorname"] !== $name) {
    die("You don't own this game.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['thumbfile'])) {
        $time = date("d/m/Y");
        $uploadedFile = $_FILES["thumbfile"]["tmp_name"];
        $destination = $_SERVER["DOCUMENT_ROOT"] . "/renderedassets/". $id.".png";
        $mime = getimagesize($uploadedFile)["mime"];
        try {
            if ($mime == "image/png") {
                if(move_uploaded_file($uploadedFile, $destination)) {
                    header("Location: {$baseUrl}/editgame/?id={$id}");
                } else {
                    die("nawww");
                }
            } else {
                
                die("no");
            }
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