<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');


if (isset( $_GET['status']) ){
    if (strlen($_GET['status']) <= 80){
        $userStatus = htmlspecialchars($_GET['status']);
    } else {
        die(header('Location: ' . $baseUrl . '/settings'));
    }
} else {
    die(header('Location: ' . $baseUrl . '/settings'));
}

if (isset( $_GET['about']) && strlen(isset( $_GET['about'])) <= 255) {
    if (strlen($_GET['status']) <= 255){
        $userAbout = htmlspecialchars($_GET['about']);
    } else {
        die(header('Location: ' . $baseUrl . '/settings'));
    }
} else {
    die(header('Location: ' . $baseUrl . '/settings'));
}

$statusUpdate = $MainDB->prepare("UPDATE users SET status = :status WHERE id = :userId");
$statusUpdate->bindParam(":userId", $id, PDO::PARAM_INT);
$statusUpdate->bindParam(":status", $userStatus, PDO::PARAM_INT);
$statusUpdate->execute();

$aboutUpdate = $MainDB->prepare("UPDATE users SET about = :about WHERE id = :userId");
$aboutUpdate->bindParam(":userId", $id, PDO::PARAM_INT);
$aboutUpdate->bindParam(":about", $userAbout, PDO::PARAM_INT);
$aboutUpdate->execute();

die(header('Location: ' . $baseUrl . '/settings'));

?>