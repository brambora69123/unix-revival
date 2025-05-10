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


                  

                $updateUserStmt = $MainDB->prepare("UPDATE `users` SET `currentGame` = NULL WHERE `id` = :id");
                $updateUserStmt->bindParam(':id', $userid, PDO::PARAM_INT);
                $updateUserStmt->execute();

             

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
