<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

if (isset( $_GET['text'] ) ){
    $about = htmlspecialchars($_GET['text']);
} else {
    die(header('Location: ' . $baseUrl . '/error400'));
}

$statusUpdate = $MainDB->prepare("UPDATE users SET about = :about WHERE id = :userId");
$statusUpdate->bindParam(":userId", $id, PDO::PARAM_INT);
$statusUpdate->bindParam(":about", $about, PDO::PARAM_INT);
$statusUpdate->execute();

die(header('Location: ' . $baseUrl . '/settings'));

?>