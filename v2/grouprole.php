<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
header("content-type:text/plain");
$userid = $_GET['userid'];

if (!isset($userid)) {
    echo json_encode(array("error" => "No userid provided"));
    return;
}

function getAdminIDs($adminLevel) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

    $stmt = $MainDB->prepare("SELECT id FROM users WHERE admin = :adminLevel");
    $stmt->bindParam(':adminLevel', $adminLevel, PDO::PARAM_INT);
    $stmt->execute();
    $adminIDs = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $adminIDs;
}

$AdminList = getAdminIDs(1);
$InternList = getAdminIDs(2);
$StarList = [51]; // okay i cant be arsed to add this to sql mb -pacman

$GroupListAdmin = [
    1200769,
    //2868472,
    //4199740,
    //4265462,
];

$GroupListIntern = [
    2868472,
];

$GroupListStar = [
    4199740
];

if (in_array($userid, $InternList)) {
    $GroupRank = 255; 
    $GroupList = $GroupListIntern;
} else if (in_array($userid, $AdminList)) {
    $GroupRank = 255;
    $GroupList = $GroupListAdmin;
} else if (in_array($userid, $StarList)) {
    $GroupRank = 1;
    $GroupList = $GroupListStar;
} else {
    echo json_encode(["data" => []]);
    return;
}

$GroupRoles = [];
foreach ($GroupList as $GroupID) {
    $GroupRoles[] = [
        "group" => [
            "id" => $GroupID,
            "name" => "string",
            "memberCount" => 0,
            "hasVerifiedBadge" => true,
        ],
        "role" => [
            "id" => $GroupID,
            "name" => "string",
            "rank" => $GroupRank,
        ],
        "isNotificationsEnabled" => true,
    ];
}

echo json_encode(["data" => $GroupRoles]);
?>
