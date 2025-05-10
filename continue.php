<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>
    <title>Unix - Continue</title>
    <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
</head>
<body>
<?php 
  if ($backgroundEnabled == 0) {
    echo '<video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>';
  } else {
    echo '';
  }
  
  ?>
    <div class="main-div-container">
        <div class="section-text-div">
            <p class="section-text">Continue</p>
            <a href="./home" class="section-a-text">Go Back</a>
        </div>
        <div class="section-container">
            <div class="section-text-div">
                
            </div>
            <div class="games-container">
                <?php
             $ActionFetch = $MainDB->prepare("
    SELECT a.*, IFNULL(os.totalPlayerCount, 0) AS playersOnline
    FROM asset AS a
    LEFT JOIN (
        SELECT gameID, SUM(playerCount) AS totalPlayerCount
        FROM open_servers
        WHERE playerCount > 0
        GROUP BY gameID
    ) AS os ON a.id = os.gameID
    JOIN (
        SELECT gameID, MAX(played) AS max_played
        FROM recentplayed
        WHERE userid = :userid
        GROUP BY gameID
    ) AS rp ON a.id = rp.gameID
    WHERE a.approved = '1' AND a.public = '1' AND a.itemtype = 'place'
    ORDER BY rp.max_played DESC
    LIMIT 24
");

$ActionFetch->bindParam(':userid', $id, PDO::PARAM_INT);
$ActionFetch->execute();
$ActionRows = $ActionFetch->fetchAll();

                foreach ($ActionRows as $GameInfo) {
                $playersOnline = intval($GameInfo['playersOnline']);

                echo '
                                                    <div class="game-card">
                    <a class="game-card-link" href="http://unixfr.xyz/viewgame?id=' . $GameInfo["id"] . '" title="' . $GameInfo["name"] . '">
                    
                    <div class="game-card-img-div-square">
                      <img
                        loading="lazy"
                        src="https://unixfr.xyz/Thumbs/AssetIcon.ashx?id='. $GameInfo["id"] .'  "
                        alt=""
                        height="150px"
                        width="150px"
                        class="game-card-img"
                      />
                      <p>2017M</p>
                    </div>
                    <p class="game-card-p">
                        ' . $GameInfo["name"] . '
                    </p>
                    <p class="game-card-online"> <span class="white-span"> '. $GameInfo['playersOnline'] .' </span> online</p>
                    </a>
                </div>
                                            ';
                }

                if (empty($ActionRows)) {
                echo "<span>No games.</span>";
                }
                ?>


            </div>
            </div>
        </div>
    </div>
    
</body>
</html>