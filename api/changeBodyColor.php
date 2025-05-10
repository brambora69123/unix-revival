<?php 
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
    case ($RBXTICKET == null):
        die(header('Location: ' . $baseUrl . '/'));
        break;
}


$part = (string)$_GET["part"];
$cid  = (int)$_GET["cid"];
if ($cid == "null") {
    header("Location: /avatar.php");
    die("i died");
}
switch ($part) {
    case "h":
        $query = "
            UPDATE body_colours
            SET h = :h
            WHERE uid = :playerId;
        ";
        $statement = $MainDB->prepare($query);
        //$statement->bindParam(':uid', $id, PDO::PARAM_INT);
        $statement->bindParam(':h', $cid, PDO::PARAM_INT);
        $statement->bindParam(':playerId', $id, PDO::PARAM_INT);
        $statement->execute();
        // re render player
        $url = "https://unixfr.xyz/soapy/unix/Roblox/render?id=" . urlencode($id);
        $content = file_get_contents($url);
        // go back to avatar page
        header("Location: /avatar");
        die("i died");
        break;
    case "t":
        $query = "
            UPDATE body_colours
            SET t = :t
            WHERE uid = :playerId;
        ";
        $statement = $MainDB->prepare($query);
        //$statement->bindParam(':uid', $id, PDO::PARAM_INT);
        $statement->bindParam(':t', $cid, PDO::PARAM_INT);
        $statement->bindParam(':playerId', $id, PDO::PARAM_INT);
        $statement->execute();
        // re render player
        $url = "https://unixfr.xyz/soapy/unix/Roblox/render?id=" . urlencode($id);
        $content = file_get_contents($url);
        // go back to avatar page
        header("Location: /avatar");
        die("i died");
        break;
    case "ll":
        $query = "
            UPDATE body_colours
            SET rl = :ll
            WHERE uid = :playerId;
        ";
        $statement = $MainDB->prepare($query);
        //$statement->bindParam(':uid', $id, PDO::PARAM_INT);
        $statement->bindParam(':ll', $cid, PDO::PARAM_INT);
        $statement->bindParam(':playerId', $id, PDO::PARAM_INT);
        $statement->execute();
        // re render player
        $url = "https://unixfr.xyz/soapy/unix/Roblox/render?id=" . urlencode($id);
        $content = file_get_contents($url);
        // go back to avatar page
        header("Location: /avatar");
        die("i died");
        break;
    case "rl":
        $query = "
            UPDATE body_colours
            SET ll = :rl
            WHERE uid = :playerId;
        ";
        $statement = $MainDB->prepare($query);
        //$statement->bindParam(':uid', $id, PDO::PARAM_INT);
        $statement->bindParam(':rl', $cid, PDO::PARAM_INT);
        $statement->bindParam(':playerId', $id, PDO::PARAM_INT);
        $statement->execute();
        // re render player
        $url = "https://unixfr.xyz/soapy/unix/Roblox/render?id=" . urlencode($id);
        $content = file_get_contents($url);
        // go back to avatar page
        header("Location: /avatar");
        die("i died");
        break;
    case "ra":
        $query = "
            UPDATE body_colours
            SET ra = :ra
            WHERE uid = :playerId;
        ";
        $statement = $MainDB->prepare($query);
        //$statement->bindParam(':uid', $id, PDO::PARAM_INT);
        $statement->bindParam(':ra', $cid, PDO::PARAM_INT);
        $statement->bindParam(':playerId', $id, PDO::PARAM_INT);
        $statement->execute();
        // re render player
        $url = "https://unixfr.xyz/soapy/unix/Roblox/render?id=" . urlencode($id);
        $content = file_get_contents($url);
        // go back to avatar page
        header("Location: /avatar");
        die("i died");
        break;
    case "la":
        $query = "
            UPDATE body_colours
            SET la = :la
            WHERE uid = :playerId;
        ";
        $statement = $MainDB->prepare($query);
        //$statement->bindParam(':uid', $id, PDO::PARAM_INT);
        $statement->bindParam(':la', $cid, PDO::PARAM_INT);
        $statement->bindParam(':playerId', $id, PDO::PARAM_INT);
        $statement->execute();
        // re render player
        $url = "https://unixfr.xyz/soapy/unix/Roblox/render?id=" . urlencode($id);
        $content = file_get_contents($url);
        // go back to avatar page
        header("Location: /avatar");
        die("i died");
        break;
    default:
        echo "hmm";
        break;
}


//echo "part: ". $_GET["part"] .", cid: ". $_GET["cid"];


?>