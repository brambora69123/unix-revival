<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$requestUri = $_SERVER['REQUEST_URI'];
$uriSegments = explode('/', trim(parse_url($requestUri, PHP_URL_PATH), '/'));

$uriSegments = array_filter($uriSegments);
if (
    $uriSegments[0] === 'v1' &&
    $uriSegments[1] === 'games' &&
    is_numeric($uriSegments[2]) &&
    $uriSegments[3] === 'media'
) {
    $placeId = $uriSegments[2];

    $PlaceFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid");
    $PlaceFetch->execute([":pid" => $placeId]);
    $Place = $PlaceFetch->fetch(PDO::FETCH_ASSOC);

    if ($Place) {
       $data = array(
    'data' => array() 
);
        echo json_encode($data);
    } else {
        echo json_encode(["Success" => false, "Message" => "Game not found"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["Success" => false, "Message" => "Invalid request"]);
}
exit();
?>
 