<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');

switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}
$gameid = (int) ($_GET['id'] ?? die(header('Location: ' . $baseUrl . '/error')));

$GameFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid AND itemtype = 'place'");
$GameFetch->execute([":pid" => $gameid]);
$Results = $GameFetch->fetch(PDO::FETCH_ASSOC);

$favoritesFetch = $MainDB->prepare("SELECT * FROM favorites WHERE userid = :userId AND gameid = :gameId");
$favoritesFetch->bindParam(":userId", $id, PDO::PARAM_INT);
$favoritesFetch->bindParam(":gameId", $gameid, PDO::PARAM_INT);
$favoritesFetch->execute();

$favoritesResults = $favoritesFetch->fetchAll(PDO::FETCH_ASSOC);


$likeRatingsFetch = $MainDB->prepare("SELECT * FROM ratings WHERE userid = :userId AND gameid = :gameId AND ratetype = 0");
$likeRatingsFetch->bindParam(":userId", $id, PDO::PARAM_INT);
$likeRatingsFetch->bindParam(":gameId", $gameid, PDO::PARAM_INT);
$likeRatingsFetch->execute();

$likeRatingsResults = $likeRatingsFetch->fetchAll(PDO::FETCH_ASSOC);

$dislikeRatingsFetch = $MainDB->prepare("SELECT * FROM ratings WHERE userid = :userId AND gameid = :gameId AND ratetype = 1");
$dislikeRatingsFetch->bindParam(":userId", $id, PDO::PARAM_INT);
$dislikeRatingsFetch->bindParam(":gameId", $gameid, PDO::PARAM_INT);
$dislikeRatingsFetch->execute();

$dislikeRatingsResults = $dislikeRatingsFetch->fetchAll(PDO::FETCH_ASSOC);

$favouriteImage = "./media/images/favouriteoutlined.png";

$likeImage = "./media/images/likeoutlined.png";
$dislikeImage = "./media/images/dislikeoutlined.png";

if ($favoritesFetch->rowCount() > 0) {
  $favouriteImage = "./media/images/favouritefilled.png";
}

if ($likeRatingsFetch->rowCount() > 0) {
  $likeImage = "./media/images/likefilled.png";
}

if ($dislikeRatingsFetch->rowCount() > 0) {
  $dislikeImage = "./media/images/dislikefilled.png";
}



$allLikeRatingsFetch = $MainDB->prepare("SELECT * FROM ratings WHERE gameid = :gameId AND ratetype = 0");
$allLikeRatingsFetch->bindParam(":gameId", $gameid, PDO::PARAM_INT);
$allLikeRatingsFetch->execute();

$allLikeRatingsResults = $allLikeRatingsFetch->fetchAll(PDO::FETCH_ASSOC);

$totalLikes = count($allLikeRatingsResults);


$allDislikeRatingsFetch = $MainDB->prepare("SELECT * FROM ratings WHERE gameid = :gameId AND ratetype = 1");
$allDislikeRatingsFetch->bindParam(":gameId", $gameid, PDO::PARAM_INT);
$allDislikeRatingsFetch->execute();

$allDislikeRatingsResults = $allDislikeRatingsFetch->fetchAll(PDO::FETCH_ASSOC);

$totalDislikes = count($allDislikeRatingsResults);

$plusVotes = ($totalLikes + $totalDislikes);

$totalVotes =  round($totalLikes / ($plusVotes ?: 1) * 100);


switch (true) {
  case (!$Results):
    die(header('Location: ' . $baseUrl . '/error.php'));
    break;
}

function isMobile() {
  $userAgent = $_SERVER['HTTP_USER_AGENT'];

  // Check if the user agent contains common strings used by mobile devices
  $mobileKeywords = array('Android', 'iPhone', 'iPad', 'Windows Phone', 'BlackBerry', 'Opera Mini', 'Mobile', 'Tablet');

  foreach ($mobileKeywords as $keyword) {
      if (stripos($userAgent, $keyword) !== false) {
          return true;
      }
  }

  return false;
}

$badgesFetch = $MainDB->prepare("SELECT * FROM badges WHERE AwardingPlaceID = :gameId AND isEnabled = 1");
$badgesFetch->bindParam(":gameId", $gameid, PDO::PARAM_INT);
$badgesFetch->execute();
$badges = $badgesFetch->fetchAll(PDO::FETCH_ASSOC);


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

                      $ActionFetch->bindParam(':id', $gameid, PDO::PARAM_INT);
                      $ActionFetch->execute();
                      $gameInfo = $ActionFetch->fetch(PDO::FETCH_ASSOC);



$year = $Results["year"];

          if ($year == 2019) {
              $string = "2019M";
          } elseif ($year == 2017) {
            $string = "2017M";
          } elseif ($year == 2021) {
              $string = "2021E";
          } elseif ($year == 2015) {
			  $string = "2015M";
		  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix -
    <?php echo nxe($Results['name']); ?>
  </title>
  <meta content="Unix - <?php echo nxe($Results['name']); ?>" property="og:title" />
  <meta content="A user-generated game created by <?php echo $Results['creatorname']; ?>" property="og:description" />
  <meta content="https://unixfr.xyz/" property="og:url" />
  <meta content="https://unixfr.xyz/media/images/elogo.png" property="og:image" />
  <meta content="#800080" data-react-helmet="true" name="theme-color" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>
  <?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php'); ?>

<?php 
  if ($backgroundEnabled == 0) {
    echo '<video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>';
  } else {
    echo '';
  }
  
  ?>
  <div class="main-div-container">
    <div class="main-game-div-container">

      <div class="game-card-img-div">
        <img src="https://unixfr.xyz/Thumbs/GameIcon.ashx?assetId=<?php echo $gameid; ?>&width=576&height=324&version=<?= rand(0,123) ?>" alt=""
          class="main-game-page-img" id="main-game-image">
      </div>
      <div class="main-game-info-div">
        <div class="main-game-info-top-div">
          <p class="main-game-title">
            <?php echo nxe($Results['name']); ?>
          </p>
          <p class="main-game-creator">
            <span class="main-game-creator-gray-span">By</span> <a href="http://unixfr.xyz/viewuser?id=<?php echo  $Results['creatorid'];?> ">
              <?php echo $Results['creatorname']; ?>
            </a>
          </p>

        </div>
    
        <div class="main-game-info-bottom-div">
          <?php
          
          $url = isMobile() ?
              "http://unixfr.xyz/games/start?placeid=$gameid" :
              "/api/joingame?gameid=$gameid";
          ?>

          <div class="main-game-actions-div">
            <div class="main-game-actions-div-left">
              <a href=<?php echo "https://unixfr.xyz/api/favourite?id=$gameid"?>><img src=<?php echo $favouriteImage?>  alt="" width="25px" class="favourite-button" title="Favorite the game"></a>
            </div>
            
            <div class="main-game-actions-div-right">
              <a href=<?php echo "https://unixfr.xyz/api/rate?type=0&id=$gameid"?>>
                <img src=<?php echo $likeImage ?>  alt="" width="25px" class="like-button" title="Like the game">
              </a>
              
              <div class="like-percentage-div">
                <p class="like-percentage-p"><?php echo $totalVotes?>%</p>
                <!--<progress value="87" max="100"></progress>-->
              </div>
              
              <a href=<?php echo "https://unixfr.xyz/api/rate?type=1&id=$gameid"?>>
                <img src=<?php echo $dislikeImage ?>  alt="" width="25px" class="dislike-button" title="Dislike the game">
              </a>

            </div>
            
          </div>
          

          <a href='<?php echo $url; ?>'>
              <button class="main-game-button">
                  Play
              </button>
          </a>
          
        </div>


      </div>

    </div>

    

    <div class="section-container">
      
      <div class="user-tabs-container">

        <div class="user-button-switch-div" role="group" aria-label="Basic example">

              <a href="#about" style="display: contents; margin-right: 0;"><button type="button" id="about-button" class="user-button-switch button-selected">About</button></a>
              <a href="#store" style="display: contents; margin-right: 0;"><button type="button" id="store-button" class="user-button-switch">Store</button></a>
              <a href="#servers" style="display: contents; margin-right: 0;"><button type="button" id="server-button" class="user-button-switch">Servers</button></a>
              
        </div>
        
      </div>

    </div>

    
    <div id="about-div">

      <!--<div class="section-text-div">
        <p class="section-text">About</p>
      </div>-->

      <div class="main-game-other-info-div">
        <div class="main-game-info-chip">
          <p>Active</p>
          <p><?php echo $gameInfo['playersOnline']; ?></p>
        </div>
        <div class="main-game-info-chip">
          <p>Server Size</p>
          <p> <?php echo $gameInfo['maxPlayers']; ?> </p>
        </div>
        <div class="main-game-info-chip">
          <p>Favourites</p>
          <p><?php echo $Results['favorited']; ?></p>
        </div>
        <div class="main-game-info-chip">
          <p>Visits</p>
          <p><?php echo $Results['visits']; ?></p>
        </div>
        <div class="main-game-info-chip">
          <p>Created</p>
          <p><?php echo $Results['createdon']; ?></p>
        </div>
        <div class="main-game-info-chip">
          <p>Updated</p>
          <p><?php echo $Results['updatedon']; ?></p>
        </div>
        <div class="main-game-info-chip">
          <p>Year</p>
          <p><?php echo $string; ?></p>
        </div>
        <div class="main-game-info-chip">
          <p>Genre</p>
          <p>All</p>
        </div>
      </div>

      <div class="main-game-bottom-div">
        <p class="main-game-description-text"> <?php echo nxe($Results["moreinfo"]) ?: "No description"; ?> </p>
      </div>

      <div class="main-game-bottom-div">


        <?php
        
          switch(true){
            case($badges):
              foreach($badges as $badge){

                $badgeDesc = $badge["description"] ?: "No description";

                  echo '
            

                  <div class="badge-card">

                      <div class="badge-img-div">
                        <img src="/renderedassets/'.$badge["id"].'.png" alt="">
                      </div>

                      <div class="badge-main-div">
                        <p class="badge-name">'.$badge["name"].' - {text that says if the player completed it}</p>
                        <p class="badge-description">'.$badgeDesc.'</p>
                      
                        <div class="user-general-info-div" style="display: flex; justify-content: center;">
                          <div class="user-general-info" style="margin-left: auto; margin-right: auto;">
                            <p class="user-friends-text">Rarity</p>
                            <p class="user-friends">100%</p>
                          </div>
                          <div class="user-general-info" style="margin-left: auto; margin-right: auto;">
                            <p class="user-friends-text">Won Ever</p>
                            <p class="user-friends">84123</p>
                          </div>
                          <div class="user-general-info" style="margin-left: auto; margin-right: auto;">
                            <p class="user-friends-text">Won Yesterday</p>
                            <p class="user-friends">84123</p>
                          </div>
                        </div>

                      </div>
                      
                    </div>


                  ';
                
                
              }
            break;
            default:
            echo '<p class="no-players-found-text">No items found.</p>';
            break;
          }

        ?>

        
      </div>

    </div>
<?php
$ServerFetch = $MainDB->prepare("
    SELECT * 
    FROM open_servers 
    WHERE gameID = :gameId
");
$ServerFetch->bindParam(":gameId", $gameid, PDO::PARAM_INT);
$ServerFetch->execute();
$servers = $ServerFetch->fetchAll(PDO::FETCH_ASSOC);
?>

<div id="server-div">
  <!--<div class="section-text-div">
    <p class="section-text">Servers</p>
  </div>-->
  <div class="main-game-bottom-div">
    <?php foreach ($servers as $server): ?>
      <div class="server-card">
        <div class="server-left">
          <p><?php echo $server['playerCount']; ?> of <?php echo $server['maxPlayers']; ?> players max</p>
          <a href="/api/joinserver.php?serverId=<?php echo $server['jobid']; ?>&gameid=<?php echo $gameid;?>"><button class="server-button">Join</button></a>
        </div>
        <div class="server-right">
          <?php
          $UserFetch = $MainDB->prepare("
              SELECT u.* 
              FROM users u 
              WHERE u.currentGame = :serverId
          ");
          $UserFetch->bindParam(":serverId", $server['jobid'], PDO::PARAM_INT);
          $UserFetch->execute();
          $users = $UserFetch->fetchAll(PDO::FETCH_ASSOC);
          ?>
          <?php foreach ($users as $user): ?>
            <div class="small-user-card">
              <img src="./renders/<?php echo $user['id']; ?>-closeup.png" alt="" width="50px">
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
    
    <div id="store-div">
      <!--<div class="section-text-div">
        <p class="section-text">Store</p>
      </div>-->
      <div class="main-game-bottom-div" style="padding-left: 0; padding-bottom: 1px;">
        <div class="gamepass-card">
          <a class="gamepass-card-link" href="http://unixfr.xyz/viewgame?id=">

            <div class="gamepass-card-img-div-square">
              <img
                loading="lazy"
                alt=""
                height="150px"
                width="150px"
                class="gamepass-card-img"
              />

            </div>
            
            <p class="gamepass-card-p">
              gamepass nameaaaaaaaaaaaaaaaaaaaaaa
            </p>

            <div class="item-card-info-div">         
              <img src="/media/images/robuxicon.png" alt=":thumbs_up:" width="18px" height="18px" class="game-card-likes-img" />
              <p class="item-card-robux-p">15aaaaaaaaaaaaaa</p>                    
            </div>

            <button class="gamepass-card-button">Buy</button>
            
          </a>
        </div>
      </div>
    </div>

    <script>
      //tab switch handler script thingy
      const aboutDiv = document.getElementById("about-div");
      const storeDiv = document.getElementById("store-div");
      const serverDiv = document.getElementById("server-div");

      const aboutButton = document.getElementById("about-button");
      const storeButton = document.getElementById("store-button");
      const serverButton = document.getElementById("server-button");

      function toggleElementVisibility(){
        const hash = window.location.hash;
        if (hash === "#about" || hash=="") {
          console.log("about")
          aboutDiv.style.display = "block";
          aboutButton.classList.add("button-selected");
          storeDiv.style.display = "none";
          storeButton.classList.remove("button-selected");
          serverDiv.style.display = "none";
          serverButton.classList.remove("button-selected");
        }
        if (hash === "#store") {
          console.log("store")
          aboutDiv.style.display = "none";
          aboutButton.classList.remove("button-selected");
          storeDiv.style.display = "block";
          storeButton.classList.add("button-selected");
          serverDiv.style.display = "none";
          serverButton.classList.remove("button-selected");
        }
        if (hash === "#servers") {
          console.log("servers")
          aboutDiv.style.display = "none";
          aboutButton.classList.remove("button-selected");
          storeDiv.style.display = "none";
          storeButton.classList.remove("button-selected");
          serverDiv.style.display = "block";
          serverButton.classList.add("button-selected");
        }
      }
      

      window.addEventListener("hashchange", toggleElementVisibility);
      toggleElementVisibility();
    </script>
    

    <?php 
    if ($Results["creatorname"] == $name) {
      echo '<div class="main-game-bottom-div">
      <p class="main-game-description-text">You own this game! Click <a href="/editgame/?id='.$Results["id"].'">here</a> to configure it.</p>
    </div>';
    }
    ?>
    



  </div>
</body>

</html>