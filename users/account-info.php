<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');


function NoXSSPlz($input){
$input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
return $input;
}
header("Content-Type: application/json");
$roblosec = filter_var($_COOKIE['ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
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
if($MembType == 0){$MembValue = 0;}elseif($MembType == 1){$MembValue = 1;}elseif($MembType == 2){$MembValue = 2;}elseif($MembType == 3){$MembValue = 3;}elseif($MembType == "Premium"){$MembValue = 4;}
?>
{"UserId":<?=$uID;?>,"Username":"<?php echo  $username;?>","DisplayName":"<?php echo $displayname;?>","HasPasswordSet":true,"Email":null,"AgeBracket":0,"Roles":<?=$Roles;?>,"MembershipType":<?=$MembValue;?>,"RobuxBalance":<?=$robux;?>,"NotificationCount":0,"EmailNotificationEnabled":false,"PasswordNotificationEnabled":false}
<?php
exit();
