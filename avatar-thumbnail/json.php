<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config.php');
$userId = (int)$_GET['userId'];
$width = (int)$_GET['width'];
$height = (int)$_GET['height'];
?>
{"Url":"https://unixfr.xyz/avatar-thumbnail/image?userId=<?php echo $userId; ?>&x=<?php echo $width; ?>&y=<?php echo $height; ?>","Final":true,"SubstitutionType":0}