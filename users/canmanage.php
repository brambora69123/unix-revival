

<?php

// Get the current URL
$currentUrl = $_SERVER['REQUEST_URI'];

// Define the keywords
$keywordCanManage = 'canmanage';
$keywordAccountInfo = 'account-info';

// Check if the current URL contains "canmanage"
if (strpos($currentUrl, $keywordCanManage) !== false) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

	echo '{"Success":true,"CanManage":false}';
    exit();
} elseif (strpos($currentUrl, $keywordAccountInfo) !== false) {
    // include_once config.php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
function NoXSSPlz($input){
$input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
return $input;
}
header("Content-Type: application/json");
$roblosec = filter_var($_COOKIE['_ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
$usrquery = $MainDB->prepare("SELECT * FROM `users` WHERE `token` = :ROBLOSECURITY");
$usrquery->execute(['ROBLOSECURITY' => $roblosec]);
$usr = $usrquery->fetch();
if(!is_array($usr)){
http_response_code(403);
exit();
}
$uID = $usr['id'];
$username = $usr['name'];
$robux = $usr['robux'];
$MembType = $usr['membership'];
if($usr['displayname'] == NULL){
$displayname = $username;
}else{
$displayname = $usr['displayname'];
}
if(!empty($usr['Roles'])){
$Roles = json_encode(explode(",",$usr['Roles']));
}else{
$Roles = "[]";
}
if($MembType == "None"){$MembValue = 0;}elseif($MembType == "BuildersClub"){$MembValue = 1;}elseif($MembType == "TurboBuildersClub"){$MembValue = 2;}elseif($MembType == "OutrageousBuildersClub"){$MembValue = 3;}elseif($MembType == "Premium"){$MembValue = 4;}
?>
{"UserId":<?=$uID;?>,"Username":"<?php echo  $username;?>","DisplayName":"<?echo $displayname;?>","HasPasswordSet":true,"Email":null,"AgeBracket":0,"Roles":<?=$Roles;?>,"MembershipType":<?=$MembValue;?>,"RobuxBalance":<?=$robux;?>,"NotificationCount":0,"EmailNotificationEnabled":false,"PasswordNotificationEnabled":false}
<?php
exit();

    exit();
} else {

}

?>

