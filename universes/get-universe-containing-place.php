<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php'); 
header("content-type: application/json");
$placeId = $_GET['placeId'] ?? null;
if ($placeId != null) {
	$place = (int) $placeId;
$data = array("UniverseId" => $place);
$json_data = json_encode($data);
} else {
	$data = array(
	"status" => "error",
	"success" => true
	);
	$json_data = json_encode($data);
}
echo $json_data;
?>


