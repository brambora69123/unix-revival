<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$requestUri = $_SERVER['REQUEST_URI'];
$uriSegments = explode('/', trim(parse_url($requestUri, PHP_URL_PATH), '/'));

$uriSegments = array_filter($uriSegments);

$uriSegments = array_values($uriSegments);
$doubleSlash = false;

// Check if the request is for user management or user info
if (
    count($uriSegments) === 4 && 
    $uriSegments[0] === 'users' && 
    $uriSegments[2] === 'canmanage' &&
    is_numeric($uriSegments[1]) &&
    is_numeric($uriSegments[3])
) {
    $id = $uriSegments[1];
    $anotherId = $uriSegments[3];
} elseif (
    count($uriSegments) === 3 && 
    $uriSegments[1] === 'canmanage' &&
    is_numeric($uriSegments[0]) &&
    is_numeric($uriSegments[2])
) {  
    $id = $uriSegments[0];
    $anotherId = $uriSegments[2];
    $doubleSlash = true;
} elseif (
    count($uriSegments) === 2 && 
    is_numeric($uriSegments[1])
) {
	
    $userId = $uriSegments[1];

    // Fetch user info from the database
    $UserFetch = $MainDB->prepare("SELECT * FROM users WHERE id = :uid");
    $UserFetch->execute([":uid" => $userId]);
    $User = $UserFetch->fetch(PDO::FETCH_ASSOC);
    
    if ($User) {
       $data = array(
    "Id" => $User['id'],
    "Username" => $User['name'],
    "AvatarUri" => null,
    "AvatarFinal" => false,
    "IsOnline" => false
);
$json = json_encode($data);
echo $json;
    } else {
        echo json_encode(["Success" => false, "Message" => "User not found"]);
    }
    exit();
} else {
    http_response_code(200);
    echo json_encode(["Success" => true, "CanManage" => false]);
    exit();
}

if ($doubleSlash) {
    $usera = $uriSegments[0];    
    $place = $uriSegments[2];
} else {
    $usera = $uriSegments[1];    
    $place = $uriSegments[3];
}

$GameFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid AND itemtype = 'place'");
$GameFetch->execute([":pid" => $place]);
$Results = $GameFetch->fetch(PDO::FETCH_ASSOC);
if ($Results) {
    if ($Results['creatorid'] === $usera) {
        echo json_encode(["Success" => true, "CanManage" => true]);
    } else {
        echo json_encode(["Success" => true, "CanManage" => false]);
    }
} else {
    echo json_encode(["Success" => false, "CanManage" => false]);
}
exit();
?>
