<?php
include_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');

function jsonResponse($data, $code = 200) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code($code);
    echo json_encode($data);
    exit();
}

$headers = getallheaders();
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

if (strpos($user_agent, 'Roblox') !== false) {
    $options = ['cost' => 10];
}

header('X-Robots-Tag: noindex');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = urldecode($_POST['username']);
        $password = urldecode($_POST['password']);
    } elseif (isset($data["username"]) && isset($data["password"])) {
        $username = urldecode($data['username']);
        $password = urldecode($data['password']);
    } elseif (isset($data["cvalue"]) && isset($data["password"])) {
        $username = urldecode($data['cvalue']);
        $password = urldecode($data['password']);
    } else {
        jsonResponse(['message' => 'Username and password are required.'], 400);
    }

    $checkquery = $MainDB->prepare("SELECT * FROM `users` WHERE `name`= :username");
    $checkquery->execute(['username' => $username]);
    $check = $checkquery->fetch();

    if (!$check) {
        jsonResponse(['message' => 'Incorrect username.'], 403);
    }

    $hash = $check['password'];
    if (!password_verify($password, $hash)) {
        jsonResponse(['message' => 'Incorrect password. Please try again.'], 403);
    }


    $roblosec = $check['token'];
    $uID = $check['id'];

    setcookie("ROBLOSECURITY", $roblosec, time() + (460800 * 30), "/", '.unixfr.xyz');
	setcookie(".ROBLOSECURITY", $roblosec, time() + (460800 * 30), "/", '.unixfr.xyz');
	setcookie("_ROBLOSECURITY", $roblosec, time() + (460800 * 30), "/", '.unixfr.xyz');
    setcookie("access", "yes", time() + 86400, "/", '.unixfr.xyz');
    setcookie(".RBXID", $roblosec, time() + (460800 * 30), "/", '.unixfr.xyz');

    $response = [
        'membershipType' => 4,
        'username' => strip_tags($username),
        'isUnder13' => false,
        'countryCode' => "US",
        'userId' => $uID,
        'displayName' => strip_tags($username)
    ];

    if (strpos($headers['User-Agent'], "Android") !== false || strpos($headers['User-Agent'], "iPhone") !== false) {
        jsonResponse(['user' => ['id' => $uID, 'name' => strip_tags($username), 'displayName' => strip_tags($username)], 'isBanned' => false]);
    }

    jsonResponse($response);
}
?>
