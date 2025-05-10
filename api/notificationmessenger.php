<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');

$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;

if ($RBXTICKET === null) {
    header("Location: " . $baseUrl . "/");
    die();
}

if ($admin < 1) {
    header("Location: " . $baseUrl . "/");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['type']) && in_array($_POST['type'], ['info', 'warn', 'event'])) {

        if ($_POST['type'] == "info" || $_POST['type'] == "warn") {

            if (isset($_POST["message"], $_POST['userid'])) {

                if (strpos($_POST['message'], '[PLAYERNAME]') !== false) {
                    $userQuery = $MainDB->prepare("SELECT name FROM users WHERE id = :userid");
                    $userQuery->bindParam(":userid", $_POST['userid'], PDO::PARAM_INT);
                    $userQuery->execute();
                    $user = $userQuery->fetch(PDO::FETCH_ASSOC);
                    
                    if ($user) {
                        $message = str_replace('[PLAYERNAME]', $user['name'], $_POST['message']);
                    } else {
                        die("User not found");
                    }
                } else {
                    $message = $_POST['message'];
                }

                $time = time();

                $notificationAdd = $MainDB->prepare("INSERT INTO notification (text, type, user1, userId, time) VALUES (:message, :type, :id, :elid, :time)");
                $notificationAdd->bindParam(":message", $message, PDO::PARAM_STR);
                $notificationAdd->bindParam(":type", $_POST['type'], PDO::PARAM_STR);
                $notificationAdd->bindParam(":id", $id, PDO::PARAM_INT);
                $notificationAdd->bindParam(":elid", $_POST['userid'], PDO::PARAM_INT);
                $notificationAdd->bindParam(":time", $time, PDO::PARAM_INT);
                $notificationAdd->execute();

            } else {
                die("Message or User ID not set");
            }
        }

        if ($_POST['type'] == "event") {
            if (isset($_POST['gameid'])) {
                // A
            } else {
                die("Game ID not set");
            }
        }

    } else {
        die("Invalid type");
    }
} else {
    die("Invalid request method");
}
?>
