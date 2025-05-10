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
    // set the time var for creation dates
    $time = date("d/m/Y");
    // set the sql query
    $query = "
    DELETE FROM asset
    WHERE id = :id
    AND itemtype = 'Place';
    ";
    $stmt = $MainDB->prepare($query);
    $stmt->execute([":id" => $id]);
    header("Location: https://unixfr.xyz/create");
} else {
    die("nope");
}

?>