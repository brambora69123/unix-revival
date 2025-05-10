<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config.php');
$headers = getallheaders();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($headers['Content-Encoding']) && strtolower($headers['Content-Encoding']) === 'gzip') {
        $json = gzdecode(file_get_contents('php://input'));
    } else {
        $json = file_get_contents('php://input');
    }

    $decoded = json_decode($json);
    if ($decoded !== null) {
        $response = array('data' => array());
        for ($i = 0; $i < count($decoded); $i++) {
            $obj = $decoded[$i];
            $targetId = $obj->targetId;
            if ($obj->type == "Avatar") {
                $imageurl = "https://unixfr.xyz/renders/{$targetId}.png";
            } elseif ($obj->type == "AvatarHeadShot") {
                $imageurl = "https://unixfr.xyz/renders/{$targetId}-closeup.png";
            } elseif ($obj->type == "Asset") {
                $imageurl = "https://unixfr.xyz/renderedassets/{$targetId}.png";
            } elseif ($obj->type == "PlaceIcon") {
                $imageurl = "https://unixfr.xyz/renderedicons/{$targetId}.png";
            } elseif ($obj->type == "GameIcon") {
                $imageurl = "https://unixfr.xyz/renderedicons/{$targetId}.png";
            } else {
                die(json_encode(['message' => "Sorry, that type doesn't exist."]));
            }

            $responseData = array(
                'requestId' => $obj->requestId,
                'errorCode' => 0,
                'errorMessage' => '',
                'targetId' => $obj->targetId,
                'state' => 'Completed',
                'imageUrl' => $imageurl
            );
            $response['data'][] = $responseData;
        }
        echo json_encode($response);
    } else {
        echo 'Error: Unable to decode JSON object.';
    }
} else {
    die(http_response_code(405));
}
?>
