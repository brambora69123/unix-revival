<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php'); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php'); ?>
<?php
switch(true){case($RBXTICKET == null):die(header('Location: '. $baseUrl .'/'));break;}
$ItemId = (int)($_GET['id'] ?? die(json_encode(['message' => 'error, no asset to favourite!'])));

$AssetFetch = $MainDB->prepare("SELECT * FROM asset WHERE approved = '1' AND id = :pid AND public = '1'");
$AssetFetch->execute([":pid" => $ItemId]);
$Results = $AssetFetch->fetch(PDO::FETCH_ASSOC);


if ($Results !== false) {
$e = 1;
} else {
	die(json_encode(['message' => 'invalid asset']));
}
$favoritesFetch = $MainDB->prepare("SELECT * FROM favorites WHERE userid = :userId AND gameid = :gameId");
$favoritesFetch->bindParam(":userId", $id, PDO::PARAM_INT);
$favoritesFetch->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
$favoritesFetch->execute();

$favoritesResults = $favoritesFetch->fetchAll(PDO::FETCH_ASSOC);

if ($favoritesFetch->rowCount() > 0) {
    $deleteQuery = $MainDB->prepare("DELETE FROM favorites WHERE userid = :userId AND gameid = :gameId");
    $deleteQuery->bindParam(":userId", $id, PDO::PARAM_INT);
    $deleteQuery->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
    $deleteQuery->execute();
	  $updateAssetQuery = $MainDB->prepare("UPDATE asset SET favorited = favorited - 1 WHERE id = :gameId");
    $updateAssetQuery->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
    $updateAssetQuery->execute();

} else {
    $insertQuery = $MainDB->prepare("INSERT INTO favorites (userid, gameid) VALUES (:userId, :gameId)");
    $insertQuery->bindParam(":userId", $id, PDO::PARAM_INT);
    $insertQuery->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
    $insertQuery->execute();
	$updateAssetQuery = $MainDB->prepare("UPDATE asset SET favorited = favorited + 1 WHERE id = :gameId");
    $updateAssetQuery->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
    $updateAssetQuery->execute();
}



if ($Results['itemtype'] === 'Place') {




$redirectUrl = 'https://www.unixfr.xyz/viewgame?id='.$ItemId;

header('Location: ' . $redirectUrl);
} else {
    header('Location: '. $baseUrl .'/');
}
