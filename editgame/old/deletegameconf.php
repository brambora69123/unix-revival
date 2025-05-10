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
    echo '
    are you sure?
    <br>
    <form action="deletegame" method="post">
        <input name="gameid" type="hidden" value="'.$id.'">
        <input type="submit" value="yes, delete game" style="color:red;">
    </form>
    <button onclick="history.back()" style="display:inline;">wait no</button>
    ';
} else {
    die("nope");
}

?>