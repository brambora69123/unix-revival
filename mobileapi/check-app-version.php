<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
$appVersion = $_GET['appVersion'];

switch(true){
	case (in_array($appVersion, $allowedVersions)):
		echo '{"data":{"UpgradeAction":"NotRequired"}}';
		break;
	default:
		echo '{"data":{"UpgradeAction":"Required"}}';
		break;
}
?>