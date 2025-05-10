<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header("content-type: application/json");

if (isset($_COOKIE['ROBLOSECURITY'])) {
    $token = $_COOKIE['ROBLOSECURITY'];

    $tokenFetch = $MainDB->prepare("SELECT * FROM users WHERE token = :token");
    $tokenFetch->execute([":token" => $token]);
    $tokenResults = $tokenFetch->fetch(PDO::FETCH_ASSOC);

    if ($tokenResults) {
        $userId = (int)$tokenResults['id'];
    } else {
        die(json_encode(["success" => false, "message" => "Invalid token"]));
    }
} else {
    $userId = (int)($_GET['userId'] ?? die(json_encode(["success" => true, "message" => "Success", "count" => 0])));
}

$GameFetch = $MainDB->prepare("SELECT * FROM users WHERE id = :pid");
$GameFetch->execute([":pid" => $userId]);
$Results = $GameFetch->fetch(PDO::FETCH_ASSOC);

if (!$Results) {
    die(json_encode(["success" => false]));
}

echo json_encode(["success" => true, "message" => "Success", "count" => $Results['friends']]);
?>
