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

            if (isset($_POST["message"])) {

                if (strpos($_POST['message'], '[PLAYERNAME]') !== false) {
                    die("User ID not set");
                } else {
                    $message = $_POST['message'];
                }

                $time = time();

                $notificationAdd = $MainDB->prepare("INSERT INTO notification (text, type, user1, userId, time) SELECT :message, :type, id, id, :time FROM users");
                $notificationAdd->bindParam(":message", $message, PDO::PARAM_STR);
                $notificationAdd->bindParam(":type", $_POST['type'], PDO::PARAM_STR);
                $notificationAdd->bindParam(":time", $time, PDO::PARAM_INT);
                $notificationAdd->execute();

            } else {
                die("Message not set");
            }
        }

        if ($_POST['type'] == "event") {
            if (isset($_POST['gameid'])) {
                // A
            } else {
                die("Game ID not set");
            }
        }
    echo "Notification sent!<br>";
    } else {
        die("Invalid type");
    }
} else {
    die("Invalid request method");
}
