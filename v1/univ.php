<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

header('Content-Type: application/json');

$requestUri = $_SERVER['REQUEST_URI'];
$uriSegments = explode('/', trim(parse_url($requestUri, PHP_URL_PATH), '/'));

$uriSegments = array_filter($uriSegments);

if (
    count($uriSegments) === 3 &&
    $uriSegments[0] === 'v1' &&
    $uriSegments[1] === 'universes' &&
    is_numeric($uriSegments[2])
) {
    $placeId = $uriSegments[2];
	$UniverseFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :uid");
    $UniverseFetch->execute([":uid" => $placeId]);
    $Universe = $UniverseFetch->fetch(PDO::FETCH_ASSOC);

if ($Universe != null) {

	if ($Universe['public'] == 1) {
		$eee = "Public";
	}else{ 
		$eee = "Private";
	}
	
    $gameData = array(
        "id" => $Universe['id'],
        "name" => $Universe['name'],
        "description" => $Universe['moreinfo'],
        "isArchived" => false,
        "rootPlaceId" => $Universe['id'],
        "isActive" => true,
        "privacyType" => $eee,
        "creatorType" => "User",
        "creatorTargetId" => (int)$Universe['creatorid'],
        "creatorName" => $Universe['creatorname'],
        "created" => "2024-04-28T16:23:36.203Z",
        "updated" => "2024-04-28T18:34:13.853Z"
    );

    echo json_encode($gameData, JSON_UNESCAPED_SLASHES);
    exit();
}
}

if (
    count($uriSegments) === 4 &&
    $uriSegments[0] === 'v1' &&
    $uriSegments[1] === 'universes' &&
    is_numeric($uriSegments[2]) &&
    $uriSegments[3] === 'icon'
) {
    $universeId = $uriSegments[2];

    $UniverseFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :uid");
    $UniverseFetch->execute([":uid" => $universeId]);
    $Universe = $UniverseFetch->fetch(PDO::FETCH_ASSOC);

    if ($Universe) {
        $iconUrl = "http://unixfr.xyz/renderedassets/".$Universe['id'].".png";
        $data = array(
            "imageId" => $iconUrl,
            "isApproved" => true
        );
        echo json_encode($data, JSON_UNESCAPED_SLASHES);
    } else {
        echo json_encode(["Success" => false, "Message" => "Universe not found"]);
    }
    exit();
}

// If no valid endpoint matches, return 400 Bad Request
http_response_code(400);
echo json_encode(["Success" => false, "Message" => "Invalid request"]);
exit();
?>
