<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

$check = $MainDB->prepare("SELECT * FROM friends WHERE user1 = $id OR user2 = $id LIMIT 7");

$check->execute();

$ActionRows = $check->fetchAll(PDO::FETCH_ASSOC);


$check2 = $MainDB->prepare("SELECT * FROM recentplayed WHERE userid = :id ORDER BY id DESC LIMIT 6");
$check2->bindParam(':id', $id, PDO::PARAM_INT);
$check2->execute();
$ActionRows2 = $check2->fetchAll(PDO::FETCH_ASSOC);

$buildersClubImageShow = false;
$admininstrationstatusImageShow = false;
$buildersClubImage = "./media/images/buildersclub";
$admininstrationstatusImage = "No way? No way!";

switch ($Info["membership"]) {
  case 1:
    $buildersClubImage = "./media/images/buildersclub.png";
    $buildersClubImageShow = true;
    break;
  case 2:
    $buildersClubImage = "./media/images/buildersclubturbo.png";
    $buildersClubImageShow = true;
    break;
  case 3:
    $buildersClubImage = "./media/images/builderscluboutragous.png";
    $buildersClubImageShow = true;
    break;
  case 0:
    $buildersClubImageShow = false;
  default:
    $buildersClubImageShow = false;
}
switch ($Info["admin"]) {
  case 1:
    $admininstrationstatusImageShow = true;
    $admininstrationstatusImage = "./media/images/admin.png";
    break;
  case 2:
    $admininstrationstatusImageShow = true;
    $admininstrationstatusImage = "./media/images/admin.png";
    break;
  default:
    $admininstrationstatusImageShow = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)) ?>">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
</head>

<body>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>
  <?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php'); ?>

  <?php
    if ($backgroundEnabled == 0) {
      echo '<video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>';
    } else {
      echo '<img id="video-background" src="/media/images/background.png"></img>';
    }
  ?>

  <div class="main-div-container">
    <img src="https://unixfr.xyz/Thumbs/Avatar.ashx?userId=<?php echo $id; ?>&x=48&y=48" alt="" class="main-player-img"
      id="main-player-img" height="150px" />
      <h1 class="main-header">
      <span class="thin-span">Hello,</span>
      <?php echo nx($name); ?>
      <?php
if ($buildersClubImageShow == true) {
    echo '<img src="' . $buildersClubImage . '" alt="" height="30" style="margin-top: auto; margin-bottom: auto; margin-right: 5px;">';
}
if ($admininstrationstatusImageShow == true) {
    echo '<img src="' . $admininstrationstatusImage . '" alt="" height="30" style="margin-top: auto; margin-bottom: auto; margin-left: 5px;">';
}
?>

    </h1>
    <div class="section-container">
      <div class="section-text-div">
        <p class="section-text">Friends (<?php echo $friendCount; ?>)
        </p>
        <a href="/friends" class="section-a-text">See All</a>
      </div>
      <p class="section-text"></p>


      <div class="friends-container">






        <?php

        if (!empty($ActionRows)) {
          foreach ($ActionRows as $row) {
            $otherUserId = ($row['user1'] == $id) ? $row['user2'] : $row['user1'];

            if ($otherUserId != $id) {
              $GameFetch = $MainDB->prepare("SELECT * FROM users WHERE id = :pid");
              $GameFetch->bindParam(":pid", $otherUserId, PDO::PARAM_INT);
              $GameFetch->execute();
              $Results = $GameFetch->fetch(PDO::FETCH_ASSOC);

              $presenceIcon = "offline-img";

              if (time() <= intval($Results["lastSeenOnline"])+600) {
                $presenceIcon = "online-img-small";
              } 


              if (strlen($Results["currentGame"]) != 0) {
                $presenceIcon = "ingame-img-small";
              }


              echo '
            <div class="player-card">
                <a style="position:relative;" class="player-card-link" href="http://unixfr.xyz/viewuser?id='. $Results['id'] .' " title="' . $Results['name'] . '">
                    <img
                        src="https://unixfr.xyz/Thumbs/Avatar.ashx?userId=' . $Results['id'] . '"
                        alt=""
                        class="player-img"
                        id="main-player-img"
                        height="100px"
                    />

                    <div class="presence-div-small">
                      <span class='.$presenceIcon.' alt="" title=""></span>
                    </div>
                    <p class="player-card-p">' . nx($Results['name']) . '</p>
                    
                </a>
            </div>
            ';
            }
          }
        } else {
          echo '<p class="no-players-found-text"> friendless </p>';
        }
        ?>
      
      </div>
    </div>



    <div class="section-container">
      <div class="section-text-div">
        <p class="section-text">Continue</p>
        <a href="./continue" class="section-a-text">See All</a>
      </div>
      <div class="continue-games-container">

<?php
if (!empty($ActionRows2)) {
    foreach ($ActionRows2 as $row) {
        try {
            $ActionFetch = $MainDB->prepare("
                SELECT a.*, IFNULL(os.totalPlayerCount, 0) AS playersOnline
                FROM asset AS a
                LEFT JOIN (
                    SELECT gameID, SUM(playerCount) AS totalPlayerCount
                    FROM open_servers
                    WHERE playerCount > 0
                    GROUP BY gameID
                ) AS os ON a.id = os.gameID
                WHERE a.approved = '1' AND a.itemtype = 'place' AND a.id = :id
                ORDER BY playersOnline DESC
            ");

            $ActionFetch->bindParam(':id', $row['gameid'], PDO::PARAM_INT);
            $ActionFetch->execute();
            $gameInfo = $ActionFetch->fetch(PDO::FETCH_ASSOC);

            if (!empty($gameInfo)) {
                $year = $gameInfo["year"];
                $string = '';

                if ($year == 2019) {
                    $string = "2019M";
                } elseif ($year == 2017) {
                    $string = "2017M";
                } elseif ($year == 2021) {
                    $string = "2021E";
                } elseif ($year == 2015) {
                    $string = "2015M";
                }

                if ($gameInfo['playersOnline'] !== null && $gameInfo['playersOnline'] !== '') {
                    echo '
                    <div class="game-card">
                        <a class="game-card-link" href="http://unixfr.xyz/viewgame?id=' . $gameInfo["id"] . '" title="' . $gameInfo["name"] . '">
                            <div class="game-card-img-div-square">
                                <img
                                    loading="lazy"
                                    src="https://unixfr.xyz/Thumbs/AssetIcon.ashx?id='. $gameInfo["id"] .'  "
                                    alt=""
                                    height="150px"
                                    width="150px"
                                    class="game-card-img"
                                />
                                <p>' . $string . '</p>
                            </div>
                            <p class="game-card-p">
                                ' . nx($gameInfo["name"]) . '
                            </p>
                            <p class="game-card-online"> <span class="white-span"> ' . $gameInfo['playersOnline'] . ' </span> online</p>
                        </a>
                    </div>';
                } else {
                    continue;
                }
            } else {
                continue;
            }
        } catch (PDOException $e) {
            continue;
        }
    }
} else {
    echo '<p class="no-players-found-text">Find more games&nbsp;<a href="./games" class="section-a-text">here</a></p>';
}
?>






      </div>
    </div>

  
    
<div class="section-container">
  <div class="section-text-div">
    <p class="section-text">Favourites</p>
    <a href="./favourites" class="section-a-text">See All</a>
  </div>
  <div class="continue-games-container">

    <?php
      $check3 = $MainDB->prepare("SELECT * FROM favorites WHERE userid = :id ORDER BY id DESC LIMIT 6");
      $check3->bindParam(':id', $id, PDO::PARAM_INT);
      $check3->execute();
      $ActionRows3 = $check3->fetchAll(PDO::FETCH_ASSOC);

      if (!empty($ActionRows3)) {
          foreach ($ActionRows3 as $row) {
              try {
                  $ActionFetch = $MainDB->prepare("
                      SELECT a.*, IFNULL(os.totalPlayerCount, 0) AS playersOnline
                      FROM asset AS a
                      LEFT JOIN (
                          SELECT gameID, SUM(playerCount) AS totalPlayerCount
                          FROM open_servers
                          WHERE playerCount > 0
                          GROUP BY gameID
                      ) AS os ON a.id = os.gameID
                      WHERE a.approved = '1' AND a.itemtype = 'place' AND a.id = :id
                      ORDER BY playersOnline DESC
                  ");

                  $ActionFetch->bindParam(':id', $row['gameid'], PDO::PARAM_INT);
                  $ActionFetch->execute();
                  $gameInfo = $ActionFetch->fetch(PDO::FETCH_ASSOC);
                  
                  $year = $gameInfo["year"];

          if ($year == 2019) {
              $string = "2019M";
          } elseif ($year == 2017) {
            $string = "2017M";
          } elseif ($year == 2021) {
              $string = "2021E";
          } elseif ($year == 2015) {
			  $string = "2015M";
		  }

                  if (!empty($gameInfo)) {
                      echo '
                      <div class="game-card">
                          <a class="game-card-link" href="http://unixfr.xyz/viewgame?id=' . $gameInfo["id"] . '" title="' . $gameInfo["name"] . '">
                            <div class="game-card-img-div-square">
                              <img
                                loading="lazy"
                                src="https://unixfr.xyz/Thumbs/AssetIcon.ashx?id='. $gameInfo["id"] .'  "
                                alt=""
                                height="150px"
                                width="150px"
                                class="game-card-img"
                              />
                              <p>' . $string . '</p>
                            </div>
                              <p class="game-card-p">
                                  ' . nx($gameInfo["name"]) . '
                              </p>
                              <p class="game-card-online"> <span class="white-span"> ' . $gameInfo['playersOnline'] . ' </span> online</p>
                          </a>
                      </div>';
                  } else {
					  // goofy
                  }
              } catch (PDOException $e) {
                  die("Error fetching game info: " . $e->getMessage());
              }
          }
      } else {
          echo '<p class="no-players-found-text">No favourite games</p>';
      }
    ?>

  </div>
</div>

  
    
    </div>

    </div>
  </div>
  
</body>

</html>