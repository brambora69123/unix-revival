<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/func.php');
include($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
header("content-type: application/json");

$placeId = (int) ($_GET['placeId'] ?? die(json_encode(["message" => "Cannot process request at this time."])));
$request = $_GET['request'] ?? die(json_encode(["message" => "Cannot process your request at this time. Try again later."]));
$token = $_GET['token'] ?? null;
$lua = $_GET['lua'] ?? null;
$isTeleport = $_GET['isTeleport'] ?? null;
$isVipServer = (int) ($_GET['VipServOwner'] ?? null);
$jobid = $_GET['jobid'] ?? null;

$guests = false;

$GetGameInfo = $MainDB->prepare("SELECT id, maxPlayers, year FROM asset WHERE approved = '1' AND id = :pid AND itemtype = 'place'");
$GetGameInfo->execute([':pid' => $placeId]);
$GameInfo = $GetGameInfo->fetch(PDO::FETCH_ASSOC);

if ($jobid) {
    $GetOpenServer = $MainDB->prepare("SELECT * FROM open_servers WHERE gameID = :pid AND jobid = :jobid");
    $GetOpenServer->execute([':pid' => $placeId, ':jobid' => $jobid]);
} else {
    $GetOpenServer = $MainDB->prepare("SELECT * FROM open_servers WHERE gameID = :pid AND vipID IS NULL AND playerCount < maxPlayers");
    $GetOpenServer->execute([':pid' => $placeId]);
}

$OpenServer = $GetOpenServer->fetch(PDO::FETCH_ASSOC);

$status = 0;

if ($OpenServer && $OpenServer['status'] == 1) {
    $status = 0;
}

if ($OpenServer && $OpenServer['status'] == 2 && isPortInUse($serviceport)) {
    $status = 2;
}

$cookieName = isset($_COOKIE['.ROBLOSECURITY']) ? '.ROBLOSECURITY' : 'ROBLOSECURITY';
if (isset($_COOKIE[$cookieName])) {
    $VerifyUser = $MainDB->prepare("SELECT token FROM users WHERE token = :token");
    $VerifyUser->execute([':token' => $_COOKIE[$cookieName]]);
    $Verification = $VerifyUser->fetch(PDO::FETCH_ASSOC);

    if ($Verification !== false) {
        $token = $_COOKIE[$cookieName];
    }
}
$base64Token = base64_encode($token);

if ($token !== null) {
    setcookie(".ROBLOSECURITY", $token, time() + 9900, "/", "http://unixfr.xyz/");
    setcookie("ROBLOSECURITY", $token, time() + 9900, "/", "http://unixfr.xyz/");
}

if ($OpenServer && $OpenServer['playerCount'] !== $OpenServer['maxPlayers']) {
    switch ($request) {
        case "RequestGame":
        case "RequestGameJob":
            if ($token) {
                $joinScript = "http://unixfr.xyz/game/join.ashx?placeId=" . $placeId . "&joinType=" . ($lua ? "json" : "json") . "&jobid=" . $OpenServer['jobid'] . "&TokenPlay=" . $token;
            } else {
                if ($guests) {
                    $joinScript = $baseUrl . "/game/joinguest.ashx?placeId=" . $placeId . "&joinType=" . ($lua ? "json" : "json") . "&jobid=" . $OpenServer['jobid'];
                } else {
                    die(json_encode(["message" => "guests are not allowed in game at this current moment"]));
                }
            }

            die(json_encode([
                "jobId" => $OpenServer['jobid'],
                "status" => $status,
                "joinScriptUrl" => $joinScript,
                "authenticationUrl" => "http://unixfr.xyz/Login/Negotiate.ashx",
                "authenticationTicket" => $base64Token
            ], JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
            break;
        case "CloudEdit":
            if ($token) {
                $joinScripte = "http://unixfr.xyz/game/join.ashx?placeId=" . $placeId . "&joinType=" . ($lua ? "json" : "json") . "&jobid=" . $OpenServer['jobid'] . "&TokenPlay=" . $token;
                die(json_encode([
                    "jobId" => $OpenServer['jobid'],
                    "status" => $status,
                    "joinScriptUrl" => $joinScripte,
                    "authenticationUrl" => "http://unixfr.xyz/Login/Negotiate.ashx",
                    "authenticationTicket" => $base64Token,
                    "message" => "a"
                ], JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
            } else {
                die(json_encode(["message" => "give user auth"]));
            }
            break;
        default:
            die(json_encode([
                "jobId" => null,
                "status" => 1,
                "joinScriptUrl" => null,
                "authenticationUrl" => null,
                "authenticationTicket" => "Guest:0"
            ], JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
            break;
    }
} elseif ($jobid) {
    die(json_encode([
        "message" => "server full",
        "jobId" => null,
        "status" => 6,
        "joinScriptUrl" => null,
        "authenticationUrl" => null,
        "authenticationTicket" => $base64Token
    ], JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

if ($GameInfo['year'] == 2017) {
    if ($request == "CloudEdit") {
        $url = "https://unixfr.xyz/soapy/unix/Roblox/studio2017?id=" . $placeId . "&acckey=" . $AccessKey;
        if ($isVipServer !== null && $isVipServer !== 0) {
            $url = "https://unixfr.xyz/soapy/unix/Roblox/studio2017?id=" . $isVipServer . "&id=" . $placeId;
        }
    } else {
        $url = "http://unixfr.xyz/startgameser.php?id=" . $placeId . "&acckey=" . $AccessKey;
        if ($isVipServer !== null && $isVipServer !== 0) {
            $url = "http://unixfr.xyz/startgameser.php?VipOwner=" . $isVipServer . "&id=" . $placeId;
        }
    }
} elseif ($GameInfo['year'] == 2021) {
    $url = "https://unixfr.xyz/soapy/unix/Roblox/game2021?id=" . $placeId . "&acckey=" . $AccessKey;
    if ($isVipServer !== null && $isVipServer !== 0) {
        $url = "https://unixfr.xyz/soapy/unix/Roblox/game2021?id=" . $placeId . "&acckey=" . $AccessKey;
    }
} elseif ($GameInfo['year'] == 2015) {
    $url = "http://unixfr.xyz/soapy/unix/Roblox/game2015?id=" . $placeId . "&acckey=" . $AccessKey;
    if ($isVipServer !== null && $isVipServer !== 0) {
        $url = "http://unixfr.xyz/soapy/unix/Roblox/game2015?id=" . $placeId . "&acckey=" . $AccessKey;
    }
} else {
    $url = "https://unixfr.xyz/soapy/unix/Roblox/game2019?id=" . $placeId . "&acckey=" . $AccessKey;
    if ($isVipServer !== null && $isVipServer !== 0) {
        $url = "https://unixfr.xyz/soapy/unix/Roblox/game2019?id=" . $placeId . "&acckey=" . $AccessKey;
    }
}


if (!$jobid) {
    file_get_contents($url);

    $GetNewOpenServer = $MainDB->prepare("SELECT playerCount, jobid, status FROM open_servers WHERE gameID = :pid");
    $GetNewOpenServer->execute([':pid' => $placeId]);
    $NewOpenServer = $GetNewOpenServer->fetch(PDO::FETCH_ASSOC);

    if ($NewOpenServer) {
        if ($NewOpenServer['playerCount'] >= $GameInfo['maxPlayers']) {
            $status = 6;
        } else {
            $status = 0;
        }

        if ($token) {
            $joinScript = "http://unixfr.xyz/game/join.ashx?placeId=" . $placeId . "&joinType=" . ($lua ? "json" : "json") . "&jobid=" . $NewOpenServer['jobid'] . "&TokenPlay=" . $token;
        } else {
            if ($guests) {
                $joinScript = $baseUrl . "/game/joinguest.ashx?placeId=" . $placeId . "&joinType=" . ($lua ? "json" : "json") . "&jobid=" . $NewOpenServer['jobid'];
            } else {
                die(json_encode(["message" => "guests are not allowed in game at this current moment"]));
            }
        }

        die(json_encode([
            "jobId" => $NewOpenServer['jobid'],
            "status" => $status,
            "joinScriptUrl" => $joinScript,
            "authenticationUrl" => "http://unixfr.xyz/Login/Negotiate.ashx",
            "authenticationTicket" => $base64Token,
            "message" => null
        ], JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
    } else {
        $status = ($GameInfo && $GameInfo['maxPlayers'] > 0) ? 6 : 0;
        die(json_encode([
            "jobId" => null,
            "status" => $status,
            "joinScriptUrl" => null,
            "authenticationUrl" => null,
            "authenticationTicket" => $base64Token,
            "message" => null
        ], JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
    }
} else {
    die(json_encode([
        "message" => "no server availible",
        "jobId" => null,
        "status" => 1,
        "joinScriptUrl" => null,
        "authenticationUrl" => null,
        "authenticationTicket" => $base64Token
    ], JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}
