<?php
$userid = $_GET['userid'];

if (!isset($userid)) {
    echo json_encode(array("error" => "No userid provided"));
    exit;
}

include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');
header("content-type: application/json");
 include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

    $urlParts = explode('/', $currentUrl);
    $id = $userid;

    $friendsQuery = "SELECT * FROM friends WHERE user1 = ? OR user2 = ?";
    $friendsStatement = $MainDB->prepare($friendsQuery);
    $friendsStatement->execute([$id, $id]);

    $data = ["data" => []];

    // Fetch all friend rows
    $friendsRows = $friendsStatement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($friendsRows as $row) {
        // Determine the friend's ID
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
?>
