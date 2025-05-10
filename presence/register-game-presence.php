<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

$userid = ((int) $_GET['visitorId'] ?? die("error"));
$gameId = $_GET['gameId'] ?? die("error");
$placeid = ((int)$_GET['placeId'] ?? die("error"));

$headers = getallheaders();

if (isset($headers['accesskey'])) {
    $access = $headers['accesskey'];
    if ($AccessKey == $access) {
        $checkSql = "SELECT * FROM recentplayed WHERE userid = :userid AND gameid = :gameid";

        $checkStmt = $MainDB->prepare($checkSql);
        $checkStmt->bindParam(':userid', $userid, PDO::PARAM_INT);
        $checkStmt->bindParam(':gameid', $placeid, PDO::PARAM_INT);
        $checkStmt->execute();

        $stmt = $MainDB->prepare("UPDATE `open_servers` SET `playerCount` = `playerCount` + 1 WHERE `jobid` = ?");
        $stmt->execute([$_GET['gameId']]);

        $updateUsers = $MainDB->prepare("UPDATE `users` SET `currentGame` = :currentGame WHERE `id` = :id");
        $updateUsers->bindParam(':currentGame', $_GET['gameId']);
        $updateUsers->bindParam(':id', $_GET['visitorId']);
        $updateUsers->execute();

        if ($checkStmt->rowCount() > 0) {
            $deleteSql = "DELETE FROM recentplayed WHERE userid = :userid AND gameid = :gameid";
            $deleteStmt = $MainDB->prepare($deleteSql);
            $deleteStmt->bindParam(':userid', $userid, PDO::PARAM_INT);
            $deleteStmt->bindParam(':gameid', $placeid, PDO::PARAM_INT);
            $deleteStmt->execute();
        } else {
            $checkSqlo = "SELECT * FROM asset WHERE id = :gameid";
            $checkStmto = $MainDB->prepare($checkSqlo);
            $checkStmto->bindParam(':gameid', $placeid, PDO::PARAM_INT);
            $checkStmto->execute();

            if ($checkStmto->rowCount() > 0) {
                $updateSql = "UPDATE asset SET visits = visits + 1 WHERE id = :gameid";
                $updateStmt = $MainDB->prepare($updateSql);
                $updateStmt->bindParam(':gameid', $placeid, PDO::PARAM_INT);
                $updateStmt->execute();
            }
        }


        $insertSql = "INSERT INTO recentplayed (userid, gameid, played) VALUES (:userid, :gameid, :played)";
        $insertStmt = $MainDB->prepare($insertSql);

        $insertStmt->bindParam(':userid', $userid, PDO::PARAM_INT);
        $insertStmt->bindParam(':gameid', $placeid, PDO::PARAM_INT);

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
