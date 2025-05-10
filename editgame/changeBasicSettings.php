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
    if (isset($_POST['naem']) && isset($_POST['daesc']) && isset($_POST['year'])) {
        if (preg_match('/<script\b[^>]*>(.*?)<\/script>/i', $_POST['naem']) ||
            preg_match('/<script\b[^>]*>(.*?)<\/script>/i', $_POST['daesc'])) {
            die(header("Location: {$baseUrl}/media/videos/haha.mp4"));
        }
          // set the time var for creation dates
          $time = date("d/m/Y");
          // set the sql query
          $query = "
          UPDATE asset
          SET name = :newname, moreinfo = :newdesc, year = :newyear, updatedon = :date
          WHERE id = :id 
          AND itemtype = 'Place';
          ";
          $stmt = $MainDB->prepare($query);
          /*$stmt->bindParam(":newdesc", $_POST['desc'], PDO::PARAM_STR);
          $stmt->bindParam(":date", $date, PDO::PARAM_STR);*/
          $stmt->execute([":newname" => $_POST["naem"], ":newdesc" => $_POST["daesc"], ":newyear" => $_POST["year"], ":date" => $time, ":id" => $id]);
          header("Location: {$baseUrl}/editgame/?id={$id}");
    } else {
        die("nop");
    }
} else {
    die("nope");
}

?>