<?php
$currentUrl = $_SERVER['REQUEST_URI'];

$keywordCanManage = 'friends';
$keywordAccountInfo = 'account-info';
$keywordFriendsCount = 'friends/count';

if (strpos($currentUrl, $keywordCanManage) !== false && strpos($currentUrl, $keywordFriendsCount) === false) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

    $urlParts = explode('/', $currentUrl);
    $id = $urlParts[5];

    $friendsQuery = "SELECT * FROM friends WHERE user1 = ? OR user2 = ?";
    $friendsStatement = $MainDB->prepare($friendsQuery);
    $friendsStatement->execute([$id, $id]);

    $data = ["data" => []];

    $friendsRows = $friendsStatement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($friendsRows as $row) {
        if ($row['user1'] == $id) {
            $friendId = $row['user2'];
        } else {
            $friendId = $row['user1'];
        }

        $usersQuery = "SELECT * FROM users WHERE id = ?";
        $usersStatement = $MainDB->prepare($usersQuery);
        $usersStatement->execute([$friendId]);

        while ($userRow = $usersStatement->fetch(PDO::FETCH_ASSOC)) {
            $created = "0001-01-01T06:00:00Z";
            $data["data"][] = [
                "isOnline" => true,
                "isDeleted" => false,
                "friendFrequentScore" => 1000,
                "friendFrequentRank" => 1000,
                "hasVerifiedBadge" => false,
                "description" => $userRow['status'],
                "created" => $created,
                "isBanned" => false,
                "externalAppDisplayName" => $userRow['name'],
                "id" => $userRow['id'],
                "name" => $userRow['name'],
                "displayName" => $userRow['name'],
            ];
        }
    }

    echo json_encode($data);

    exit();
} elseif (strpos($currentUrl, $keywordFriendsCount) !== false) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

    $urlParts = explode('/', $currentUrl);
    $id = $urlParts[4];

    $friendsCountQuery = "SELECT COUNT(*) as count FROM friends WHERE user1 = ? OR user2 = ?";
    $friendsCountStatement = $MainDB->prepare($friendsCountQuery);
    $friendsCountStatement->execute([$id, $id]);

    $countResult = $friendsCountStatement->fetch(PDO::FETCH_ASSOC);
    $friendsCount = $countResult['count'];

    $data = [
        "count" => $friendsCount
    ];

    echo json_encode($data);

    exit();
} elseif (strpos($currentUrl, $keywordAccountInfo) !== false) {
    // Handle stuff
} else {
    // yea
}
?>
