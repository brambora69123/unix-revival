<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php'); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php'); ?>
<?php
switch(true){case($RBXTICKET == null):die(header('Location: '. $baseUrl .'/'));break;}
$ItemId = (int)($_GET['id'] ?? die(json_encode(['message' => 'error, no asset to rate!'])));
$ratingTypeParam = (int)($_GET['type'] ?? die(json_encode(['message' => 'specify a rate type dum'])));

$AssetFetch = $MainDB->prepare("SELECT * FROM asset WHERE approved = '1' AND id = :pid AND public = '1'");
$AssetFetch->execute([":pid" => $ItemId]);
$Results = $AssetFetch->fetch(PDO::FETCH_ASSOC);


if ($Results !== false) {
$e = 1;
} else {
	die(json_encode(['message' => 'invalid asset']));
}
$ratingsFetch = $MainDB->prepare("SELECT * FROM ratings WHERE userid = :userId AND gameid = :gameId");
$ratingsFetch->bindParam(":userId", $id, PDO::PARAM_INT);
$ratingsFetch->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
$ratingsFetch->execute();

$ratingsResults = $ratingsFetch->fetchAll(PDO::FETCH_ASSOC);

if ($ratingsFetch->rowCount() > 0) {
    $getTypeQuery = $MainDB->prepare("SELECT ratetype FROM ratings WHERE userid = :userId AND gameid = :gaemid");
    $getTypeQuery->bindParam(":userId", $id, PDO::PARAM_INT);
    $getTypeQuery->bindParam(":gaemid", $ItemId, PDO::PARAM_INT);
    $getTypeQuery->execute();

    $ratingTypee = $getTypeQuery->fetch(PDO::FETCH_ASSOC);
    $ratingType = $ratingTypee["ratetype"];

    
    
    if ($ratingType == 0) { // id 0 means like, everything else is dislike
        $deleteQuery = $MainDB->prepare("UPDATE ratings SET ratetype = :ratetype WHERE userid = :userId AND gameid = :gameId");
        $deleteQuery->bindParam(":userId", $id, PDO::PARAM_INT);
        $deleteQuery->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
        $deleteQuery->bindParam(":ratetype", $ratingTypeParam, PDO::PARAM_INT);
        $deleteQuery->execute();

	    $updateAssetQuery = $MainDB->prepare("UPDATE asset SET liked = liked - 1 WHERE id = :gameId");
        $updateAssetQuery->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
        $updateAssetQuery->execute();

        $updateAssetQuery = $MainDB->prepare("UPDATE asset SET disliked = disliked + 1 WHERE id = :gameId");
        $updateAssetQuery->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
        $updateAssetQuery->execute();
    } else {
        $deleteQuery = $MainDB->prepare("UPDATE ratings SET ratetype = :ratetype WHERE userid = :userId AND gameid = :gameId");
        $deleteQuery->bindParam(":userId", $id, PDO::PARAM_INT);
        $deleteQuery->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
        $deleteQuery->bindParam(":ratetype", $ratingTypeParam, PDO::PARAM_INT);
        $deleteQuery->execute();

        $updateAssetQuery = $MainDB->prepare("UPDATE asset SET disliked = disliked - 1 WHERE id = :gameId");
        $updateAssetQuery->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
        $updateAssetQuery->execute();

        $updateAssetQuery = $MainDB->prepare("UPDATE asset SET liked = liked + 1 WHERE id = :gameId");
        $updateAssetQuery->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
        $updateAssetQuery->execute();
    }

} else {
    $insertQuery = $MainDB->prepare("INSERT INTO ratings (ratetype, userid, gameid) VALUES (:ratetype, :userId, :gameId)");
    $insertQuery->bindParam(":ratetype", $ratingTypeParam, PDO::PARAM_INT);
    $insertQuery->bindParam(":userId", $id, PDO::PARAM_INT);
    $insertQuery->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
    $insertQuery->execute();

    if ($ratingType == 0) { // id 0 means like, everything else is dislike
        $updateAssetQuery = $MainDB->prepare("UPDATE asset SET liked = liked + 1 WHERE id = :gameId");
        $updateAssetQuery->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
        $updateAssetQuery->execute();
    } else {
        $updateAssetQuery = $MainDB->prepare("UPDATE asset SET disliked = disliked + 1 WHERE id = :gameId");
        $updateAssetQuery->bindParam(":gameId", $ItemId, PDO::PARAM_INT);
        $updateAssetQuery->execute();
    }
	
}



if ($Results['itemtype'] === 'Place') {




$redirectUrl = 'https://www.unixfr.xyz/viewgame?id='.$ItemId;

header('Location: ' . $redirectUrl);
} else {
    header('Location: '. $baseUrl .'/');
}
