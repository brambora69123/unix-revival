<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$requestUri = $_SERVER['REQUEST_URI'];
$uriSegments = explode('/', trim(parse_url($requestUri, PHP_URL_PATH), '/'));

$uriSegments = array_filter($uriSegments);

if (
    count($uriSegments) === 3 &&
    $uriSegments[0] === 'universes' &&
    is_numeric($uriSegments[1]) &&
    $uriSegments[2] === 'cloudeditenabled'
) {
    $universeId = $uriSegments[1];

    $UniverseFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :uid");
    $UniverseFetch->execute([":uid" => $universeId]);
    $Universe = $UniverseFetch->fetch(PDO::FETCH_ASSOC);
    if ($Universe['teamCreateEnabled'] == 1) {
		$e = true;
	} else {
	$e = false;
	}
	
    if ($Universe) {
        $cloudEditEnabled = $e;
        $data = array(
            "enabled" => $e
        );
        echo json_encode($data);
    } else {
        echo json_encode(["Success" => false, "Message" => "Universe not found"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["Success" => false, "Message" => "Invalid request"]);
}
exit();
?>
