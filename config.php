<?php
//die("<h1>Unixfr.xyz</h1><p>No we didnt get hacked or something like that. Previoustimes hasnt paid for the vps in a long time and stuff like that.</p><p>We also didnt shut down (or maybe not i dont know)</p><p><i>-trolleybus </i></p>");
# yes this page is from multrbx, its just easy to setup and doesnt contain vulns
// pre why do u put comments with #
$baseUrl = "https://".$_SERVER['SERVER_NAME'];
$domainUrl = $_SERVER['SERVER_NAME'];
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: -1");
header("Last-Modified: " . gmdate("D, d M Y H:i:s T") . " GMT");


$allowedVersions = array('AppAndroidV2.234.70767','AppAndroidV2.276.102913','AppAndroidV2.300.138670','AppAndroidV2.158.48944','AppAndroidV2.299.137632','AppAndroidV2.275.102906','AppAndroidV2.384.302309','AppAndroidV2.166.50996','AppAndroidV2.237.72017','AppAndroidV2.269.94916','AppAndroidV2.270.96141','AppAndroidV2.299.137632','AppiOSV2.205.61876','AppAndroidV2.205.61876');
$rbxUserAgent = array('Mozilla/5.0 (3946MB; 1600x900; 240x240; 1066x600; Samsung go fuck yourself; 9) AppleWebKit/537.36 (KHTML, like Gecko)  ROBLOX Android App 2.269.94916 Tablet Hybrid()');
$allowedmd5hashes = array('2b4ba7fc-5843-44cf-b107-ba22d3319dcd');

# Hey! This is the config page, if you are here, you either found a vuln or are in the vps or source is dumped, congrats.

$hostdb = "localhost";
$accdb = "unixsql";
$passdb = "Ii968[K~<~8Myx1KaUekn-dU^1'$0h/h";
$namedb = "unixdb";

$SignType = 1; // 1 for rbxsig, 2 for no rbxsig

$CurrPage = $_SERVER["REQUEST_URI"];
$StarterID = 20; //Put in here your starterscript's asset id!
$AssetRedirect = true;
$CurrentVersion = "version-27973050fb3b494f";

$AdminList = array("1");
$AccessKey = "h933tM8fvZgwys6SDn1XdFwR5jOPtSXPgv";

$currentUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$targetUrl = "http://unixfr.xyz";

if ($currentUrl == "http://45.131.65.54/") {
    header("Location: $targetUrl");
    exit();
}


try {
    $MainDB = new PDO("mysql:host=$hostdb;dbname=$namedb", $accdb, $passdb);
} catch (Exception $e) {
    header('Location: /maintenance.php');
}

$MainDB->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);






?>