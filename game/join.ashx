<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include($_SERVER['DOCUMENT_ROOT'] . '/func.php');
header("content-type:text/plain");
$token = ($_GET['TokenPlay'] ?? null);
$placeId = (int)($_GET['placeId'] ?? die(json_encode(["message" => "Cannot process request at this time."])));
$joinType = ($_GET['joinType'] ?? die(json_encode(["message" => "Cannot process your request at this time. Try again later."])));
$jobId = ($_GET['jobid'] ?? die(json_encode(["message" => "Cannot process your request at this time. Try again later."])));
$expiration_time = time() + (1 * 60 * 60);

switch(true){
	case ($token !== null):
		$GetPlayerInfo = $MainDB->prepare("SELECT id, name, membership, admin FROM users WHERE token = :token");
		$GetPlayerInfo->execute([':token' => $token]);
		$PlayerInfo = $GetPlayerInfo->fetch(PDO::FETCH_ASSOC);
		switch(true){case(!$PlayerInfo):die(json_encode(['message' => 'Cannot process your request at this time.']));break;}
		$userId = $PlayerInfo['id'];
		$userName = $PlayerInfo['name'];
		$userMembership = $PlayerInfo['membership'];
		$userAdmin = $PlayerInfo['admin'];
		setcookie("ROBLOSECURITY", $token, $expiration_time, "/", "unixfr.xyz");
        setcookie(".ROBLOSECURITY", $token, $expiration_time, "/", "unixfr.xyz");
		break;
	case ($RBXTICKET !== null):
		$GetPlayerInfo = $MainDB->prepare("SELECT id, name, membership, admin FROM users WHERE token = :token");
		$GetPlayerInfo->execute([':token' => $RBXTICKET]);
		$PlayerInfo = $GetPlayerInfo->fetch(PDO::FETCH_ASSOC);
		switch(true){case(!$PlayerInfo):die(json_encode(['message' => 'Cannot process your request at this time.']));break;}
		$userId = $PlayerInfo['id'];
		$userName = $PlayerInfo['name'];
		$userMembership = $PlayerInfo['membership'];
		$userAdmin = $PlayerInfo['admin'];
		break;
	default:
		die(json_encode(['message' => 'Cannot process your request at this time.']));
		break;
}

$GetGameInfo = $MainDB->prepare("SELECT id, address, port, creatorid, approved, year, public FROM asset WHERE id = :pid AND itemtype = 'place'");
$GetGameInfo->execute([':pid' => $placeId]);
$GameInfo = $GetGameInfo->fetch(PDO::FETCH_ASSOC);
switch(true){case(!$GameInfo):die(json_encode(['message' => 'Cannot process your request at this time.']));break;}

$GetServerInfo = $MainDB->prepare("SELECT * FROM open_servers WHERE jobid = :pid ");
$GetServerInfo->execute([':pid' => $jobId]);
$ServerInfo = $GetServerInfo->fetch(PDO::FETCH_ASSOC);
switch(true){case(!$ServerInfo):die(json_encode(['message' => 'Cannot process your requestOe at this time.']));break;}

function userExists($id)
{
	include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
	$get = $MainDB->prepare("SELECT * FROM users WHERE id = :i");
	$get->bindParam(":i", $id, PDO::PARAM_INT);
	$get->execute();
	if($get->rowCount() > 0) 
	{
		return true;
	}
	return false;
}

if ($ServerInfo['vipID'] !== null) {
    if ($userId !== $ServerInfo['vipID']) {
        $check = $MainDB->prepare("
            SELECT *
            FROM friends
            WHERE (user1 = :vipID OR user1 = :userId)
            AND (user2 = :vipID OR user2 = :userId)
        ");

        $check->execute(array(
            'vipID' => $ServerInfo['vipID'],
            'userId' => $userId
        ));

        if ($check->rowCount() === 0) {
            die(json_encode(['message' => 'Cannot process your request at this time.']));
        }
    }
}


$gameId = $GameInfo['id'];
$gameIp = $GameInfo['address'];
$gameYear = $GameInfo['year'];
$public = $GameInfo['public'];
$gamePort = $ServerInfo['port'];
$gameCreator = $GameInfo['creatorid'];
$approved = $GameInfo['approved'];
function SignData(string $data, bool $rbxsig=true)
        {
            $sig = "";
            $key = wordwrap(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/game/0b1BTQn2BgFq.pem"), 64, "\n",true);
            openssl_sign($data, $sig, $key, OPENSSL_ALGO_SHA1);

            if ($rbxsig) {
                return "--rbxsig%" . base64_encode($sig) . "%" . $data;
            }
            return base64_encode($sig);
        }



function ClientTicket17($userId, $userName, $charapp, $jobId,$version) {
$privatekey = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/game/0b1BTQn2BgFq.pem");
if($version != 1){$privatekey = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/game/0b1BTQn2BgFq.pem");};
    $ticket = $userId . "\n" . $jobId . "\n" . date('n/j/Y\ g:i:s\ A');
    openssl_sign($ticket, $sig, $privatekey, OPENSSL_ALGO_SHA1);
    $sig = base64_encode($sig);
    $ticket2 = $userId . "\n" . $userName . "\n" . $charapp . "\n". $jobId . "\n" . date('n/j/Y\ g:i:s\ A');
    openssl_sign($ticket2, $sig2, $privatekey, OPENSSL_ALGO_SHA1);
    $sig2 = base64_encode($sig2);
    $finaltickversion1 = date('n/j/Y\ g:i:s\ A') . ";" . $sig2 . ";" . $sig;
    $final = date('n/j/Y\ g:i:s\ A') . ";" . $sig2 . ";" . $sig;
    if($version == 1){return($finaltickversion1);} else {return($final . ";$version");
};};



function ClientTickete($id, $name, $charapp, $jobid, $e) {
$version = 2;
$privatekey = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/game/A2edGhT1bGaT.pem");

    $ticket = "$id\n$jobid\n" . date('n/j/Y g:i:s A');
    openssl_sign($ticket, $sig, $privatekey, OPENSSL_ALGO_SHA1);
    $sig = base64_encode($sig);

    $ticket2 = "$id\n$name\n$charapp\n$jobid\n" . date('n/j/Y\ g:i:s\ A');
    openssl_sign($ticket2, $sig2, $privatekey, OPENSSL_ALGO_SHA1);
    $sig2 = base64_encode($sig2);

    $finalTicket = date('n/j/Y\ g:i:s\ A') . ";$sig2;$sig";

    if ($version == 1) {
        return $finalTicket;
    } elseif ($version == 2) {
        return $finalTicket . ";$version";
    }
    return "";
}


if ($userMembership == 1) {
  $joinMem = "BuildersClub";
} elseif ($userMembership == 2) {
  $joinMem = "TurboBuildersClub";
} elseif ($userMembership == 3) {
  $joinMem = "OutrageousBuildersClub";
} elseif ($userMembership == 0) {
  $joinMem = "None";
} else {
  $joinMem = "None";
}

function ClientTicket21($userId, $username, $characterAppearanceUrl, $jobId, $e, $joinMem) {
    $version = 4;
    $followUserId = 0;
    $accountAge = 3000000;
    $membershipType = $joinMem;
    $countryCode = "US";
    $displayName = $username;
    $dateTime = date('n/j/Y g:i:s A');
    $privatekey = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/game/A2edGhT1bGaT.pem");

    $usernameLength = strlen($username);
    $membershipTypeLength = strlen($membershipType);
    $countryCodeLength = strlen($countryCode);
    $displayNameLength = strlen($displayName);

    $ticket1 = "$dateTime\n$jobId\n$userId\n$userId\n$followUserId\n$accountAge\nf\n$usernameLength\n$username\n$membershipTypeLength\n$membershipType\n$countryCodeLength\n$countryCode\n0\n\n$displayNameLength\n$displayName";
    $ticket2 = "$userId\n$username\n$characterAppearanceUrl\n$jobId\n$dateTime";

    openssl_sign($ticket1, $sig, $privatekey, OPENSSL_ALGO_SHA1);
    $sig = base64_encode($sig);

    openssl_sign($ticket2, $sig2, $privatekey, OPENSSL_ALGO_SHA1);
    $sig2 = base64_encode($sig2);

    $finalTicket = "$dateTime;$sig2;$sig;$version";

    return $finalTicket;
}



if ($approved == 0 || $public == 0) {
 if ($gameCreator == $PlayerInfo['id']) {
        $success = 1;
    } elseif ($PlayerInfo['admin'] == 1)  {
		$success = 1;
    } else {
    die('game:SetMessage("fuck off")');
	}	
}



if ($gameYear == 2021 && $joinMem != "None") {
  $joinMem = "Premium";
}



if ($token !== null) {
setcookie("ROBLOSECURITY", $token, $expiration_time, "/", "unixfr.xyz");
setcookie(".ROBLOSECURITY", $token, $expiration_time, "/", "unixfr.xyz");

}

$currenttime = time();

$stmt = $MainDB->prepare("UPDATE asset SET lastGameJoin = :currtime WHERE id = :joinedGameId");
$stmt->bindParam(":currtime", $currenttime, PDO::PARAM_INT);
$stmt->bindParam(":joinedGameId", $gameId, PDO::PARAM_INT);
$stmt->execute();



switch($joinType){
	case "lua":
		$data = '
--cokes super cool joinscript have credit
--DONT SHARE THIS PAGE, Giving someone the link to this page allows them to be able to use your ROBLOSECURITY and log in as you!
nc = game:GetService("NetworkClient")
nc:PlayerConnect('. $userId .', "'. $gameIp .'", '. $gamePort .')

plr = game.Players.LocalPlayer
plr.Name = "'. $userName .'"
plr.CharacterAppearance = "'. $baseUrl .'/Tools/FetchCharacterAppeareance.aspx?id='. $userId .'"
		
game:GetService("Visit"):SetUploadUrl("")
game.Players:SetChatStyle("ClassicAndBubble")

nc.ConnectionAccepted:connect(function(peer, repl)
game:SetMessageBrickCount()

local mkr = repl:SendMarker()
mkr.Received:connect(function()
game:SetMessage("Requesting Character...")
repl:RequestCharacter()

game:SetMessage("Waiting for character...")
--because a while loop didnt work
chngd = plr.Changed:connect(function(prop)
if prop == "Character" then chngd:disconnect() end
end)
game:ClearMessage()
end)

repl.Disconnection:connect(function()
game:SetMessage("This game has shut down")
end)
end)
nc.ConnectionFailed:connect(function() game:SetMessage("Failed to connect to the game ID: 15") end)
nc.ConnectionRejected:connect(function() game:SetMessage("Failed to connect to the game (Connection Rejected)") end)';
		sign($data);
		break;
	case "json":
	if ($gameYear == 2015) {
		$charapp = 'http://unixfr.xyz/asset/CharacterFetch.ashx?id='. $userId;
} else {
			$charapp = 'http://api.unixfr.xyz/v1.1/avatar-fetch/?userId='. $userId.'&placeId='.$placeId;
}
		$version = 1;
		if ($gameYear == 2017) {
    $ticketclient = ClientTicket17($userId, $userName, $charapp, $jobId, $version);
} elseif ($gameYear == 2019) {
    $ticketclient = ClientTickete($userId, $userName, $userId, $jobId, 2);
} elseif ($gameYear == 2021) {
	$ticketclient = ClientTicket21($userId, $userName, $charapp, $jobId, $version, $joinMem);
} elseif ($gameYear == 2015) {
	$ticketclient = ClientTicket17($userId, $userName, $charapp, $jobId, $version);
}
  function signe($data) {
    include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    $PrivKey = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/game/A2edGhT1bGaT.pem");
    openssl_sign($data, $signature, $PrivKey, OPENSSL_ALGO_SHA1);
    switch($SignType){
      case "2":
        echo sprintf("%%%s%%%s", base64_encode($signature), $data);
        break;
      default:
        echo sprintf("--rbxsig2%%%s%%%s", base64_encode($signature), $data);
        break;
    }
  }
 
		$data = '
{"ClientPort":0,"MachineAddress":"45.131.65.54","ServerPort":'. $gamePort .',"PingUrl":"","RobloxLocale":"en_us","GameLocale":"en_us","PingInterval":50,"UserName":"'. $userName .'","SeleniumTestMode":false,"UserId":'. $userId .',"SuperSafeChat":true,"CharacterAppearance":"'.$charapp.'","ClientTicket":"'.$ticketclient.'","GameId":"'.$jobId.'","PlaceId":'. $gameId .',"BaseUrl":"http://www.unixfr.xyz/","ChatStyle":"ClassicAndBubble","VendorId":0,"ScreenShotInfo":"","VideoInfo":"","CreatorId":'. $gameCreator .',"CreatorTypeEnum":"User","MembershipType":"'. $joinMem .'","AccountAge":3000000,"CountryCode":"US","CookieStoreFirstTimePlayKey":"rbx_evt_ftp","CookieStoreFiveMinutePlayKey":"rbx_evt_fDmp","CookieStoreEnabled":true,"IsRobloxPlace":false,"GenerateTeleportJoin":true,"IsUnknownOrUnder13":false,"SessionId":"","GameChatType":"AllUsers","DataCenterId":0,"UniverseId":'. $gameId .',"BrowserTrackerId":0,"UsePortraitMode":false,"FollowUserId":0,"characterAppearanceId":'. $userId .'}';
		if ($gameYear === 2021) {
		    signe($data);
		} else {
			sign($data);
		}
		break;
	default:
		die(json_encode(["message" => "Unsupported request type."]));
		break;
}
?>