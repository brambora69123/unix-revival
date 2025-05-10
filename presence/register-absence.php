<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$userid = ((int) $_GET['visitorId'] ?? die("error"));

$headers = getallheaders();
if (isset($headers['accesskey'])) {
    $access = $headers['accesskey'];
    if ($AccessKey == $access) {
        try {
            $stmt = $MainDB->prepare("SELECT `currentGame` FROM `users` WHERE `id` = :id");
            $stmt->bindParam(':id', $userid, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result !== false) {
                $gameid = $result['currentGame'];
                
                $updateOpenServersStmt = $MainDB->prepare("UPDATE `open_servers` SET `playerCount` = `playerCount` - 1 WHERE `jobid` = :jobid");
                $updateOpenServersStmt->bindParam(':jobid', $gameid, PDO::PARAM_INT);
                $updateOpenServersStmt->execute();
                
                $getOpenServerStmt = $MainDB->prepare("SELECT * FROM `open_servers` WHERE `jobid` = :jobid");
                $getOpenServerStmt->bindParam(':jobid', $gameid, PDO::PARAM_INT);
                $getOpenServerStmt->execute();
                $openServer = $getOpenServerStmt->fetch(PDO::FETCH_ASSOC);
                
                $updateUserStmt = $MainDB->prepare("UPDATE `users` SET `currentGame` = NULL WHERE `id` = :id");
                $updateUserStmt->bindParam(':id', $userid, PDO::PARAM_INT);
                $updateUserStmt->execute();
                
                if ($openServer['playerCount'] == 0) {
					$gameide = $openServer['gameID'];
                    $getAssetStmt = $MainDB->prepare("SELECT * FROM `asset` WHERE `id` = :id");
                    $getAssetStmt->bindParam(':id', $gameide, PDO::PARAM_INT);
                    $getAssetStmt->execute();
                    $asset = $getAssetStmt->fetch(PDO::FETCH_ASSOC);
                    
                        if ($asset['year'] === 2019) {
                            $gameCloseUrl = "https://unixfr.xyz/soapy/unix/roblox/gameclose2019?job=".$gameid."&acckey=h933tM8fvZgwys6SDn1XdFwR5jOPtSXPgv";
                        } elseif ($asset['year'] === 2021) {
                            $gameCloseUrl = "https://unixfr.xyz/soapy/unix/roblox/gameclose2021?job=".$gameid."&acckey=h933tM8fvZgwys6SDn1XdFwR5jOPtSXPgv";
                        } else {
                            $gameCloseUrl = "https://unixfr.xyz/soapy/unix/roblox/gameclose2019?job=".$gameid."&acckey=h933tM8fvZgwys6SDn1XdFwR5jOPtSXPgv";
                        }
                        file_get_contents($gameCloseUrl);
                   
                }

                echo "Operations completed successfully.";
            } else {
                echo "User not found or current game not available.";
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    } else {
        die("Access key denied.");
    }
} else {
    die("Access key not in headers.");
}
?>
