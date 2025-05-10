<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');

$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;

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
    if (isset($_POST['unban_player'])) {
        $userIdToUnban = $_POST['user_to_unban'];
        $unbanQuery = $MainDB->prepare("UPDATE users SET termtype = NULL WHERE id = :userId");
        $unbanQuery->execute([':userId' => $userIdToUnban]);
        sendLog("User ". $userIdToUnban . " was unbanned at " . time() . "");
        header("Location: https://unixfr.xyz/supersecretadminpanel/users");
    } else if (isset($_POST['usertoban']) && isset($_POST["bantype"]) && isset($_POST["banreason"]) && isset($_POST["oitem"]) && isset($_POST["deathnote"])) {
        $time = time();
        $unbanDate = null;

        switch ($_POST["bantype"]) {
            case "banned1":
                $unbanDate = $time + 86400;
                break;
            case "banned3":
                $unbanDate = $time + 86400 * 3;
                break;
            case "banned7":
                $unbanDate = $time + 86400 * 7;
                break;
            case "banned14":
                $unbanDate = $time + 86400 * 14;
                break;
            case "terminated":
                $unbanDate = $time + 999999999999999999;
                break;
            default:
                $unbanDate = 1;
                break;
        }

        $query = "SELECT id, currentGame, name FROM users WHERE name = :name";
        $stmt = $MainDB->prepare($query);
        $stmt->bindParam(":name", $_POST["usertoban"], PDO::PARAM_STR);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            $bannedUserId = $userData["id"];
            $bannedUserName = $userData["name"];
            $currentGame = $userData["currentGame"];

            if ($currentGame !== null) {
                $evictContent = file_get_contents('https://unixfr.xyz/soapy/unix/Roblox/evictplayer?userid=' . $bannedUserName . '&acckey=h933tM8fvZgwys6SDn1XdFwR5jOPtSXPgv&jobid=' . $currentGame);
            }

            $query = "
            UPDATE users
            SET bannedAt = :ba, banEndsAt = :bea, termtype = :ttype, treason = :treason, toi = :oitem, tnote = :tnote, tdate = null
            WHERE id = :id
            ";
            $stmt = $MainDB->prepare($query);
            $stmt->bindParam(":ba", $time, PDO::PARAM_INT);
            $stmt->bindParam(":bea", $unbanDate, PDO::PARAM_INT);
            $stmt->bindParam(":ttype", $_POST["bantype"], PDO::PARAM_STR);
            $stmt->bindParam(":treason", $_POST["banreason"], PDO::PARAM_INT);
            $stmt->bindParam(":oitem", $_POST["oitem"], PDO::PARAM_STR);
            $stmt->bindParam(":tnote", $_POST["deathnote"], PDO::PARAM_STR);
            $stmt->bindParam(":id", $bannedUserId, PDO::PARAM_INT);
            $stmt->execute();

            sendLog("User ". $bannedUserId . " was banned (" . $bannedUserName . "). \nReason: " . $_POST["banreason"] . " \nOffensive item: " . $_POST["oitem"] . " \nNote: " . $_POST["deathnote"] . " \nType: " . $_POST["bantype"] . "");
            header("Location: https://unixfr.xyz/supersecretadminpanel/users");
        } else {
            die("User not found");
        }
    } else {
        die("Invalid request");
    }
} else {
    die("Invalid method");
}
?>
