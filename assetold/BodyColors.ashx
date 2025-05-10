<?php
header("Content-type: text/xml");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: -1");
header("Last-Modified: " . gmdate("D, d M Y H:i:s T") . " GMT");
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
function userInfo($id, $useID = true) {
	include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

	if($useID) {
		$get = $MainDB->prepare("SELECT * FROM users WHERE id = :u");
	} else {
		$get = $MainDB->prepare("SELECT * FROM users WHERE name = :u");
	}
	$get->bindParam(":u", $id, PDO::PARAM_STR);
	$get->execute();
	if($get->rowCount() > 0) {
		$id = $get->fetch(PDO::FETCH_OBJ);
		return $id;
	}
	return false; //user not found
}

$id = (int)$_GET['userId'];
$assets = "";
	
$info = userInfo($id);

$h=$la=$t=$ra=$ll=$rl = 1;
if($info !== false) 
{
	$id = $info->id;
	$bc = $MainDB->prepare("SELECT * FROM body_colours WHERE uid = :u");
	$bc->bindParam(":u", $id, PDO::PARAM_INT);
	$bc->execute();
	if($bc->rowCount() > 0) {
		$bc = $bc->fetch(PDO::FETCH_OBJ);
		$h = $bc->h;
		$la = $bc->la;
		$t = $bc->t;
		$ra = $bc->ra;
		$ll = $bc->ll;
		$rl = $bc->rl;
		
	} else {
		$h=$la=$t=$ra=$ll=$rl = 5;
	}
}
?>
<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.unixfr.xyz/roblox.xsd" version="4">
	<External>null</External>
	<External>nil</External>
	<Item class="BodyColors">
		<Properties>
			<int name="HeadColor"><?=$h;?></int>
			<int name="LeftArmColor"><?=$la;?></int>
			<int name="LeftLegColor"><?=$ll;?></int>
			<string name="Name">Body Colors</string>
			<int name="RightArmColor"><?=$ra;?></int>
			<int name="RightLegColor"><?=$rl;?></int>
			<int name="TorsoColor"><?=$t;?></int>
			<bool name="archivable">true</bool>
		</Properties>                          
	</Item>
</roblox>