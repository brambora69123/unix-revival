<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');


if (isset( $_GET['status'])){
    if (strlen($_GET['status']) <= 80){
        $userStatus = htmlspecialchars($_GET['status']);
    } else {
        die(header('Location: ' . $baseUrl . '/viewuser?id=' . $id));
    }
} else {
    die(header('Location: ' . $baseUrl . '/viewuser?id=' . $id));
}

$statusUpdate = $MainDB->prepare("UPDATE users SET status = :status WHERE id = :userId");
$statusUpdate->bindParam(":userId", $id, PDO::PARAM_INT);
$statusUpdate->bindParam(":status", $userStatus, PDO::PARAM_INT);
$statusUpdate->execute();

die(header('Location: ' . $baseUrl . '/viewuser?id=' . $id));

?>