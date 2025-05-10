<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/antixss.php');
//TO NOTE: studio's browser dosen't seem to unset the cookie properly.
//if we try to get rid of the cookie, it simply changes the value to "deleted".
//we have to check for this value and turn it into null so the entirety of the site dosent go apeshit.
switch (true) {
    case (isset($_COOKIE['ROBLOSECURITY'])):
        $RBXTICKET = $_COOKIE['ROBLOSECURITY'];
        switch ($RBXTICKET) {
            case "deleted":
                $RBXTICKET = null;
                break;
            case (strpos($RBXTICKET, " ")):
                $RBXTICKET = null;
                break;
        }
        break;
    case (isset($_COOKIE['.ROBLOSECURITY'])):
        $RBXTICKET = $_COOKIE['.ROBLOSECURITY'];
        switch ($RBXTICKET) {
            case "deleted":
                $RBXTICKET = null;
                break;
            case (strpos($RBXTICKET, " ")):
                $RBXTICKET = null;
                break;
        }
        break;
    default:
        $RBXTICKET = null;
        break;
}


switch(true){
  case ($RBXTICKET !== null):
    $GetInfo = $MainDB->prepare("SELECT id, name, ticket, robux, status, about, nextrobuxgive, termtype, treason, toi, tnote, displayname, tdate, bannedAt, banEndsAt, email, emailverified, membership, friends, creationdate, admin, theme, backgroundEnabled, lastGameUpload FROM users WHERE token = :token");
    $GetInfo->execute([':token' => $RBXTICKET]);
    $Info = $GetInfo->fetch(PDO::FETCH_ASSOC);
    $id = ($Info['id'] ?? null);
    $name = ($Info['name'] ?? null);
    $ticket = ($Info['ticket'] ?? null);
    $robux = ($Info['robux'] ?? null);
    $termtype = ($Info['termtype'] ?? null);
	$status = ($Info['status'] ?? null);
    $about = ($Info['about'] ?? null);
    $termreason = ($Info['treason'] ?? null);
    $toi = ($Info['toi'] ?? null);
    $termnote = ($Info['tnote'] ?? null);
    $termdate = ($Info['tdate'] ?? null);
    $tba = nx(($Info['bannedAt'] ?? null))  ;
    $bannedAt = ($Info['bannedAt'] ?? null);
    $tbea = ($Info['banEndsAt'] ?? null);
    $email = ($Info['email'] ?? null);
    $reward = ($Info['nextrobuxgive'] ?? null);
    $verified = ($Info['emailverified'] ?? null);
    $membership = ($Info['membership'] ?? null);
    $friendapi = ($Info['friends'] ?? null);
    $birthdate = ($Info['creationdate'] ?? null);
	$admin = ($Info['admin'] ?? null);
	$theme = ($Info['theme'] ?? null);
	$displayname = ($Info['displayname'] ?? null);
    $backgroundEnabled = ($Info['backgroundEnabled'] ?? null);
    $lastGameUpload = ($Info['lastGameUpload'] ?? null);
    $GetFriends = $MainDB->prepare("SELECT * FROM friends WHERE user1 = :id OR user2 = :idd");
    $GetFriends->execute([':id' => $id, ':idd' => $id]);
    $friendCount = $GetFriends->rowCount();

    switch(true){case($friendapi !== null):$friends = explode(';', $Info['friends']);break;default:$friends = array();break;}
    switch(true){case ($name == null):header('Location: '. $baseUrl .'/api/logout?rUrl=/');break;}
    switch (true){
        case ($termtype !== null):
            switch ($CurrPage) {
                case $CurrPage !== "/banned":
                    header("Location: ". $baseUrl . "/banned");
                    die();
                    break;
                }
            break;
        }
    break;
  default:
    $id = null;
    $name = null;
    $robux = null;
    $reward = null;
    $ticket = null;
    $termtype = null;
    $termreason = null;
    $toi = null;
    $termnote = null;
    $termdate = null;
    $tba = null;
    $tbea = null;
    $email = null;
    $verified = null;
    $membership = null;
    $friends = null;
    $birthdate = null;
	$admin = null;
	$theme = null;
	$displayname = null;
	$status = null;
    $backgroundEnabled = null;
    break;
}
?>