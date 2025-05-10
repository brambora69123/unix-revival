<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
header('Content-Type: application/json');

$logged = false;

if (isset($_COOKIE['ROBLOSECURITY'])) {
    $roblosec = filter_var($_COOKIE['ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

    $usrquery = $MainDB->prepare("SELECT * FROM `users` WHERE `token` = :roblosec");
    $usrquery->execute(['roblosec' => $roblosec]);
    $usr = $usrquery->fetch(PDO::FETCH_ASSOC);

    if ($usr) {
        $logged = true;
        $uID = $usr['id'];
    }
}

if ($logged) {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (isset($data['templatePlaceIdToUse']) && $data['templatePlaceIdToUse'] == 95206881) {
        $url = 'https://unixfr.xyz/asset/?id=95206881';

        $options = [
            "http" => [
                "header" => "accesskey: $AccessKey\r\n"
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response && !empty($response)) {
            $placeId = 95206881;
            $description = "Edit this place!";

            $gamequery = $MainDB->prepare("SELECT COUNT(id) FROM `asset` WHERE `creatorid` = :creatorid AND `itemtype` = 'Place'");
            $gamequery->execute(['creatorid' => $uID]);
            $gamecount = $gamequery->fetchColumn();

            $name = "{$usr['name']}'s Place $gamecount";

            if ($gamecount < 1000) {
                $date = date("Y-m-d");

                $sql2 = "INSERT INTO `asset` (`name`, `moreinfo`, `creatorid`, `visits`, `createdon`, `updatedon`, `itemtype`, `creatorname`, `approved`,`year`) 
                         VALUES (:name, :description, :uID, '0', :date, :date2, 'Place', :username, '1','2021')";
                $stmt = $MainDB->prepare($sql2);
                $stmt->execute([
                    ':name' => $name,
                    ':description' => $description,
                    ':uID' => $uID,
                    ':date' => $date,
                    ':date2' => $date,
                    ':username' => $usr['name']
                ]);

                $newPlaceId = $MainDB->lastInsertId();

                $gamequerye = $MainDB->prepare("SELECT * FROM `asset` WHERE `id` = :id AND `creatorid` = :creatorid AND `itemtype` = 'Place'");
                $gamequerye->execute(['id' => $newPlaceId, 'creatorid' => $uID]);
                $games = $gamequerye->fetch(PDO::FETCH_ASSOC);

                $placeName = isset($games['name']) ? (string) $games['name'] : '';
                $placeId = $newPlaceId;

                $gzipData = gzencode($response);

                $postUrl = "http://www.unixfr.xyz/Data/Upload.ashx?assetid=$placeId&type=Place&ispublic=False";
                $postOptions = [
                    "http" => [
                        "header" => "Content-Type: application/x-gzip\r\n" .
                                    "accesskey: $AccessKey\r\n" .
                                    "Cookie: ROBLOSECURITY=$roblosec\r\n",
                        "method" => "POST",
                        "content" => $gzipData
                    ]
                ];
                $postContext = stream_context_create($postOptions);
                $postResponse = file_get_contents($postUrl, false, $postContext);

                $jsonData = [
                    "UniverseId" => $games['id'],
                    "RootPlaceId" => $games['id'],
                ];
                $jsonString = json_encode($jsonData, JSON_PRETTY_PRINT);
                die($jsonString);
            }
        }
    }
}
?>