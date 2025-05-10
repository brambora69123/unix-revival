<?php
require ($_SERVER['DOCUMENT_ROOT'].'/config.php');

$roblosec = filter_var($_COOKIE['ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

$usrquery = $MainDB->prepare("SELECT * FROM `users` WHERE `token` = :ROBLOSECURITY");
$usrquery->execute(['ROBLOSECURITY' => $roblosec]);
$usr = $usrquery->fetch(PDO::FETCH_ASSOC);

if (!$usr) {
    header("Location: https://www.unixfr.xyz/");
    exit();
}

$timey = time();
$uID = $usr['id'];

$post = file_get_contents('php://input');
logData("Input: " . $post);

parse_str($post, $parsedInput);

if (!isset($parsedInput['followedUserId']) || !is_numeric($parsedInput['followedUserId'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid input'));
    exit();
}

$friendId = (int)$parsedInput['followedUserId'];

$checkSql = "SELECT COUNT(*) FROM `following` WHERE `toid` = :friendId AND `fromid` = :uID";
$checkStmt = $MainDB->prepare($checkSql);
$checkStmt->bindValue(':friendId', $friendId, PDO::PARAM_INT);
$checkStmt->bindValue(':uID', $uID, PDO::PARAM_INT);
$checkStmt->execute();
$exists = $checkStmt->fetchColumn();

if ($exists) {
    $sql = "DELETE FROM `following` WHERE `toid` = :friendId AND `fromid` = :uID";
    $stmt = $MainDB->prepare($sql);
    $stmt->bindValue(':friendId', $friendId, PDO::PARAM_INT);
    $stmt->bindValue(':uID', $uID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $data = array('success' => 'true');
    } else {
        logData("SQL Error: " . implode(" - ", $stmt->errorInfo()));
        $data = array('success' => 'false', 'error' => 'Database error');
    }
} else {
    $data = array('success' => 'false', 'error' => 'Follow relationship not found');
}

echo json_encode($data);

function logData($data) {
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/user/aaaaa3.txt', $data . PHP_EOL, FILE_APPEND);
}
?>
