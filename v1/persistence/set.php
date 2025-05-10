<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
header("Content-Type: application/json");
error_reporting(E_ERROR | E_PARSE);

$pid = isset($_GET['placeId']) ? htmlspecialchars($_GET['placeId']) : null;
$scope = isset($_GET['scope']) ? htmlspecialchars($_GET['scope']) : null;
$key = isset($_GET['key']) ? htmlspecialchars($_GET['key']) : null;
$target = isset($_GET['target']) ? htmlspecialchars($_GET['target']) : null;
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : null;
$value = isset($_POST['value']) ? htmlspecialchars($_POST['value']) : null;
$valueLength = isset($_GET['valueLength']) ? htmlspecialchars($_GET['valueLength']) : null;

$errorResponse = [
    "errors" => [
        [
            "code" => 0,
            "message" => "This is the error response, it's great, isnt it."
        ]
    ]
];

if ($pid !== null) {
    $stmt = $MainDB->prepare("
        SELECT COUNT(*) as `count`
        FROM `asset_datastore`
        WHERE `placeId` = ? AND `key` = ? AND `type` = ? AND `scope` = ? AND `target` = ?
    ");
    $stmt->execute([$pid, $key, $type, $scope, $target]);
    $row = $stmt->fetch();

    if ($row['count'] > 0) {
        $stmt = $MainDB->prepare("
            UPDATE `asset_datastore`
            SET `value` = ?
            WHERE `placeId` = ? AND `key` = ? AND `type` = ? AND `scope` = ? AND `target` = ?
        ");
        $stmt->execute([$value, $pid, $key, $type, $scope, $target]);
    } else {
        $stmt = $MainDB->prepare("
            INSERT INTO `asset_datastore` (`placeId`, `key`, `type`, `scope`, `target`, `value`)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$pid, $key, $type, $scope, $target, $value]);
    }
} else {
// placeholder cuz idk
}

$values = [
    array(
        "Value" => $_POST["value"],
        "Scope" => $scope,
        "Key" => $key,
        "Target" => $target
    )
];

die(json_encode(["data" => $values], JSON_NUMERIC_CHECK));
?>
