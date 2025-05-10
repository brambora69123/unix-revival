<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

$check = $MainDB->prepare("SELECT * FROM friends WHERE user1 = $id OR user2 = $id");
$check->execute();
$ActionRows = $check->fetchAll(PDO::FETCH_ASSOC);

$checkRequests = $MainDB->prepare("SELECT * FROM friend_requests WHERE user2 = $id");
$checkRequests->execute();
$ActionRowsRequests = $checkRequests->fetchAll(PDO::FETCH_ASSOC);

$check2 = $MainDB->prepare("SELECT * FROM recentplayed WHERE userid = :id ORDER BY id DESC LIMIT 6");
$check2->bindParam(':id', $id, PDO::PARAM_INT);
$check2->execute();
$ActionRows2 = $check2->fetchAll(PDO::FETCH_ASSOC);

$friendRequestsCountFetch = $MainDB->prepare("SELECT * FROM friend_requests WHERE user2 = $id");
$friendRequestsCountFetch->execute();
$friendRequestsCount = $friendRequestsCountFetch->fetchAll(PDO::FETCH_ASSOC);

$friendRequestsAmount = $friendRequestsCountFetch->rowCount();


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)) ?>">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - Friends</title>
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
    <h1 class="main-title">All Friends</h1>
    <div class="section-container">
      
      <div class="section-container">
      
        <div class="user-tabs-container">

          <div class="user-button-switch-div" role="group" aria-label="Basic example">
                <a href="#friends" style="display: contents;"><button type="button" class="user-button-switch">Friends</button></a>
                <a href="#requests"style="display: contents;"><button type="button" class="user-button-switch">Requests</button></a>
                <a href="#following"style="display: contents;"><button type="button" class="user-button-switch">Following</button></a>
                <a href="#followers"style="display: contents; margin-right: 0;"><button type="button" class="user-button-switch" style="margin-right: 0 !important;">Followers</button></a>
          </div>
          
        </div>

      </div>
      <div style="display: flex;">
      </div>
      


      <div class="section-container">

        <div id="friends-div">

          <p class="section-text" id="friends-text">Friends (<?php echo $friendCount; ?>)</p>

          <div class="friends-container-wrap">

            <?php
              if (!empty($ActionRows)) {
                foreach ($ActionRows as $row) {
                  $otherUserId = ($row['user1'] == $id) ? $row['user2'] : $row['user1'];

                  if ($otherUserId != $id) {
                    $GameFetch = $MainDB->prepare("SELECT * FROM users WHERE id = :pid");
                    $GameFetch->bindParam(":pid", $otherUserId, PDO::PARAM_INT);
                    $GameFetch->execute();
                    $Results = $GameFetch->fetch(PDO::FETCH_ASSOC);

                    $GetGameServer = $MainDB->prepare("SELECT * FROM open_servers WHERE jobid = :currentGame");
                    $GetGameServer->execute([":currentGame" => $Results["currentGame"]]);
                    $GameServer = $GetGameServer->fetch(PDO::FETCH_ASSOC);

                    if ($GameServer) {
                      $GetGame = $MainDB->prepare("SELECT * FROM asset WHERE id = :gameid");
                      $GetGame->execute([":gameid" => $GameServer["gameID"]]);
                      $Game = $GetGame->fetch(PDO::FETCH_ASSOC);
                    }

                    $presenceSpan = "offline-span";
                    $presenceText = "Offline";

                    if (time() <= intval($Results["lastSeenOnline"])+600) {
                      $presenceSpan = "online-span";
                      $presenceText = "Online";
                    } 


                    if (strlen($Results["currentGame"]) != 0) {
                      $presenceSpan = "ingame-span";
                      $presenceText = "Playing " . $Game["name"];
                    }

                    echo '
                <div class="player-square-card">
                    <a class="player-square-card-link" href="http://unixfr.xyz/viewuser?id=' . $Results['id'] . '" title="' . $Results['name'] . '">
                        <img
                            src="https://unixfr.xyz/Thumbs/Avatar.ashx?userId=' . $Results['id'] . '"
                            alt=""
                            class="player-square-img"
                            id="main-player-square-img"
                        />
                        <p class="player-square-card-p">' . $Results['name'] . ' <br> <span class="'.$presenceSpan.'">'.$presenceText.'</span></p>
                        
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
        
        <div id="requests-div"style="display: none;">

          <p class="section-text" id="requests-text">Requests (<?php echo $friendRequestsAmount; ?>)</p>

          <div class="friends-container-wrap">

            <?php
            if (!empty($ActionRowsRequests)) {
              foreach ($ActionRowsRequests as $row) {
                $otherUserId = ($row['user1'] == $id) ? $row['user2'] : $row['user1'];

                if ($otherUserId != $id) {
                  $GameFetch = $MainDB->prepare("SELECT * FROM users WHERE id = :pid");
                  $GameFetch->bindParam(":pid", $otherUserId, PDO::PARAM_INT);
                  $GameFetch->execute();
                  $Results = $GameFetch->fetch(PDO::FETCH_ASSOC);

                  $GetGameServer = $MainDB->prepare("SELECT * FROM open_servers WHERE jobid = :currentGame");
                  $GetGameServer->execute([":currentGame" => $Results["currentGame"]]);
                  $GameServer = $GetGameServer->fetch(PDO::FETCH_ASSOC);

                  if ($GameServer) {
                    $GetGame = $MainDB->prepare("SELECT * FROM asset WHERE id = :gameid");
                    $GetGame->execute([":gameid" => $GameServer["gameID"]]);
                    $Game = $GetGame->fetch(PDO::FETCH_ASSOC);
                  }

                  $presenceSpan = "offline-span";
                  $presenceText = "Offline";

                  if (time() <= intval($Results["lastSeenOnline"])+600) {
                    $presenceSpan = "online-span";
                    $presenceText = "Online";
                  } 


                  if (strlen($Results["currentGame"]) != 0) {
                    $presenceSpan = "ingame-span";
                    $presenceText = "Playing " . $Game["name"];
                  }

                  echo '
                <div class="player-square-card">
                    <a class="player-square-card-link" href="http://unixfr.xyz/viewuser?id=' . $Results['id'] . '" title="' . $Results['name'] . '">
                        <img
                            src="https://unixfr.xyz/Thumbs/Avatar.ashx?userId=' . $Results['id'] . '"
                            alt=""
                            class="player-square-img"
                            id="main-player-square-img"
                        />
                        <p class="player-square-card-p cutoff-text">' . $Results['name'] . ' <br> <span class="'.$presenceSpan.'">'.$presenceText.'</span></p>

                        <div class="player-card-button-divs">
                          <a href="https://unixfr.xyz/api/ignorerequest?id='.$Results["id"].'"><button class="player-card-buttons">Ignore</button></a>
                          <a href="https://unixfr.xyz/api/friendrequest?id='.$Results["id"].'"><button class="player-card-buttons">Accept</button></a>
                        </div>
                        

                        
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

        <div id="following-div"style="display: none;">

          <p class="section-text" id="following-text">Following (<?php echo $friendCount; ?>)</p>

          <div class="friends-container-wrap">

            <?php
            if (!empty($ActionRows)) {
              foreach ($ActionRows as $row) {
                $otherUserId = ($row['user1'] == $id) ? $row['user2'] : $row['user1'];

                if ($otherUserId != $id) {
                  $GameFetch = $MainDB->prepare("SELECT * FROM users WHERE id = :pid");
                  $GameFetch->bindParam(":pid", $otherUserId, PDO::PARAM_INT);
                  $GameFetch->execute();
                  $Results = $GameFetch->fetch(PDO::FETCH_ASSOC);

                  echo '
                <div class="player-square-card">
                    <a class="player-square-card-link" href="http://unixfr.xyz/viewuser?id=' . $Results['id'] . '" title="' . $Results['name'] . '">
                        <img
                            src="https://unixfr.xyz/Thumbs/Avatar.ashx?userId=' . $Results['id'] . '"
                            alt=""
                            class="player-square-img"
                            id="main-player-square-img"
                        />
                        <p class="player-square-card-p">' . $Results['name'] . ' <br> <span class="'.$presenceSpan.'">'.$presenceText.'</span></p>
                        <p class="player-square-card-p">' . $Results['name'] . '</p>
                        
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

        <div id="followers-div" style="display: none;">

          <p class="section-text" id="followers-text">Followers (<?php echo $friendCount; ?>)</p>

          <div class="friends-container-wrap">

            <?php
            if (!empty($ActionRows)) {
              foreach ($ActionRows as $row) {
                $otherUserId = ($row['user1'] == $id) ? $row['user2'] : $row['user1'];

                if ($otherUserId != $id) {
                  $GameFetch = $MainDB->prepare("SELECT * FROM users WHERE id = :pid");
                  $GameFetch->bindParam(":pid", $otherUserId, PDO::PARAM_INT);
                  $GameFetch->execute();
                  $Results = $GameFetch->fetch(PDO::FETCH_ASSOC);

                  echo '
                <div class="player-square-card">
                    <a class="player-square-card-link" href="http://unixfr.xyz/viewuser?id=' . $Results['id'] . '" title="' . $Results['name'] . '">
                        <img
                            src="https://unixfr.xyz/Thumbs/Avatar.ashx?userId=' . $Results['id'] . '"
                            alt=""
                            class="player-square-img"
                            id="main-player-square-img"
                        />
                        <p class="player-square-card-p">' . $Results['name'] . ' <br> <span class="'.$presenceSpan.'">'.$presenceText.'</span></p>
                        <p class="player-square-card-p">' . $Results['name'] . '</p>
                        
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

      </div>

        </div>
      </div>
    </div>
  </div>

  <script>
    

    const friendsDiv = document.getElementById("friends-div");
    const followingDiv = document.getElementById("following-div");
    const followerDiv = document.getElementById("followers-div");
    const requestsDiv = document.getElementById("requests-div");


    function toggleElementVisibility(){
      const hash = window.location.hash;
      if (hash === "#requests") {
        console.log("requests")
        friendsDiv.style.display = "none";

        followingDiv.style.display = "none";

        followerDiv.style.display = "none";

        requestsDiv.style.display = "block";

      }
      if (hash === "#following") {
        console.log("following")
        friendsDiv.style.display = "none";

        followerDiv.style.display = "none";

        requestsDiv.style.display = "none";
        
        followingDiv.style.display = "block";
      }
      if (hash === "#followers") {
        console.log("follower")
        friendsDiv.style.display = "none";

        followingDiv.style.display = "none";

        requestsDiv.style.display = "none";

        followerDiv.style.display = "block";

      }
      if (hash === "#friends" || hash === "") {
        console.log("friends")
        followingDiv.style.display = "none";

        followerDiv.style.display = "none";

        requestsDiv.style.display = "none";

        friendsDiv.style.display = "block";
      }
    }
    

    window.addEventListener("hashchange", toggleElementVisibility);
    toggleElementVisibility();
  </script>
</body>

</html>