<?php 
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

$id = $_GET["id"] ?? die("enter a game id");

$getgameinfo = $MainDB->prepare("SELECT * FROM asset WHERE id = :id AND itemtype = 'Place'");
$getgameinfo->bindParam(":id", $id, PDO::PARAM_INT);
$getgameinfo->execute();
$results = $getgameinfo->fetch(PDO::FETCH_ASSOC);

if ($results["creatorname"] !== $name) {
    die("you dont own this game");
}

?>
<title>unix - edit game</title>
<h1>i cant be bothered to make the frontend for this page</h1>
<p>you are editing <?= $results["name"] ?></p>
<hr>
<h1>change description</h1>
<form action="wip" method="post">
    
    <textarea rows="10" cols="50" placeholder="describe what your game is about, put updates, put info etc" name="desc"><?= nx($results["moreinfo"]) ?></textarea>
    <br>
    <br>
    <input name="gameid" type="submit" value="<?= $id ?>">
</form>
<hr>
<h1>change icon</h1>
<form action="wip" method="post" enctype="multipart/form-data">
    <p>current icon:</p>
    <img src="/Thumbs/AssetIcon.ashx?id=<?= $results["id"] ?>" width="64">
    <br>
    <p>select icon</p>
    <input type="file" id="placefile" class="placefile-uploader" name="iconfile" accept="image/png" required />
    <br>
    <br>
    <input name="submitbtn" type="submit" value="change">
</form>
<hr>
<h1>change thumbnail</h1>
<form action="wip" method="post" enctype="multipart/form-data">
    <p>current thumbnail:</p>
    <img src="/Thumbs/GameIcon.ashx?id=<?= $results["id"] ?>&width=576&height=324&version=<?= rand(0,123) ?>">
    <br>
    <p>select thumbnail</p>
    <input type="file" id="placefile" class="placefile-uploader" name="iconfile" accept="image/png" required />
    <br>
    <br>
    <input name="submitbtn" type="submit" value="change">
</form>
<hr>
<h1>swap game file</h1>
<form action="wip" method="post" enctype="multipart/form-data">
    <p>select game file to swap</p>
    <input type="file" id="placefile" class="placefile-uploader" name="placefile" accept=".rbxl" required />
    <br>
    <br>
    <input name="submitbtn" type="submit" value="swap">
</form>