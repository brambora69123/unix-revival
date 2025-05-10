<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$placeIds = isset($_GET["placeIds"]) ? $_GET["placeIds"] : [];

    try {
        $stmt = $MainDB->prepare("SELECT * FROM asset WHERE id = :id");
        $stmt->bindParam(':id', $placeIds, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

   $url = "https://unixfr.xyz/viewgame?id=" . $result['id'];

$output = [
    [
        "placeId" => (int)$result['id'],
        "name" => $result['name'],
        "description" => $result['moreinfo'],
        "sourceName" => $result['name'],
        "sourceDescription" => $result['moreinfo'],
        "url" => $url,
        "builder" => $result['creatorname'],
        "builderId" => (int)$result['creatorid'],
        "hasVerifiedBadge" => true,
        "isPlayable" => true,
        "reasonProhibited" => "None",
        "universeId" => (int)$result['id'],
        "universeRootPlaceId" => (int)$result['id'],
        "price" => 0,
        "imageToken" => "T_6516141723_1d6b"
    ]
];


       header('Content-Type: application/json');
echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

?>
