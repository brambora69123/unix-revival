<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

$userid = ((int) $_GET['userid'] ?? die("error"));
$gameid = ((int) $_GET['gameid'] ?? die("error"));
$job = ((int) $_GET['job'] ?? die("error"));


$headers = getallheaders();

if (isset($headers['accesskey'])) {
    $access = $headers['accesskey'];
    if ($AccessKey == $access) {
        $checkSql = "SELECT * FROM recentplayed WHERE userid = :userid AND gameid = :gameid";
        $checkStmt = $MainDB->prepare($checkSql);
        $checkStmt->bindParam(':userid', $userid, PDO::PARAM_INT);
        $checkStmt->bindParam(':gameid', $gameid, PDO::PARAM_INT);
        $checkStmt->execute();
		
		$updateUsers = $MainDB->prepare("UPDATE `users` SET `currentGame` = :currentGame WHERE `id` = :id");
$updateUsers->bindParam(':currentGame', $_GET['job']);
$updateUsers->bindParam(':id', $_GET['userid']);
$updateUsers->execute();

     if ($checkStmt->rowCount() > 0) {
    $deleteSql = "DELETE FROM recentplayed WHERE userid = :userid AND gameid = :gameid";
    $deleteStmt = $MainDB->prepare($deleteSql);
    $deleteStmt->bindParam(':userid', $userid, PDO::PARAM_INT);
    $deleteStmt->bindParam(':gameid', $gameid, PDO::PARAM_INT);
    $deleteStmt->execute();
} else {
    $checkSqlo = "SELECT * FROM asset WHERE id = :gameid";
    $checkStmto = $MainDB->prepare($checkSqlo);
    $checkStmto->bindParam(':gameid', $gameid, PDO::PARAM_INT);
    $checkStmto->execute();

    if ($checkStmto->rowCount() > 0) {
        $updateSql = "UPDATE asset SET visits = visits + 1 WHERE id = :gameid";
        $updateStmt = $MainDB->prepare($updateSql);
        $updateStmt->bindParam(':gameid', $gameid, PDO::PARAM_INT);
        $updateStmt->execute();
    }
}


        $insertSql = "INSERT INTO recentplayed (userid, gameid, played) VALUES (:userid, :gameid, :played)";
        $insertStmt = $MainDB->prepare($insertSql);

        $insertStmt->bindParam(':userid', $userid, PDO::PARAM_INT);
        $insertStmt->bindParam(':gameid', $gameid, PDO::PARAM_INT);

        $played = date('d/m/Y');
        $insertStmt->bindParam(':played', $played, PDO::PARAM_STR);

        try {
            $insertStmt->execute();
            $e = 1; 
        } catch (PDOException $e) {
            die("Error inserting into recentlyplayed: " . $e->getMessage());
        }

    } else {
        die("Access key denied.");
    }
} else {
    die("Access key not in headers.");
}
?>
