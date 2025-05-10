<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');
$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

// log("An asset with the id of ".$lastInsertId." was created by ".$name." via the asset ripper!");


switch (true) {
  case ($RBXTICKET == null):
    header("Location: " . $baseUrl . "/");
    die();
    break;
  default:
    if ($admin < 1) {
      header("Location: " . $baseUrl . "/");
      die();
    }
    break;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["be"])) {
        // set the sql query
        $query = "
        UPDATE users SET backgroundEnabled = 0 WHERE id = :id;
        ";
        // prepare the query
        $creategamestmt = $MainDB->prepare($query);
        // bind parameters
        $creategamestmt->bindParam(":id", $id, PDO::PARAM_INT);
        // execute query and add the game to the sql
        $creategamestmt->execute();
        header("Location: /settings");
    } else {
        // set the sql query
        $query = "
        UPDATE users SET backgroundEnabled = 1   WHERE id = :id;
        ";
        // prepare the query
        $creategamestmt = $MainDB->prepare($query);
        // bind parameters
        $creategamestmt->bindParam(":id", $id, PDO::PARAM_STR);
        // execute query and add the game to the sql
        $creategamestmt->execute();
        header("Location: /settings");
    }
} else {
    die("no");
}

?>