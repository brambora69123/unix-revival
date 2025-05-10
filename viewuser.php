<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}
$userid = ($_GET['id'] ?? die(header('Location: ' . $baseUrl . '/error')));

$check = $MainDB->prepare("SELECT * FROM friends WHERE user1 = :userid OR user2 = :userid2");
$check->bindParam(":userid", $userid, PDO::PARAM_INT);
$check->bindParam(":userid2", $userid, PDO::PARAM_INT);
$check->execute();
$ActionRows = $check->fetchAll(PDO::FETCH_ASSOC);


$GetInfoe = $MainDB->prepare("SELECT * FROM users WHERE id = :id");
$GetInfoe->bindParam(":id", $userid);
$GetInfoe->execute();
$Infoe = $GetInfoe->fetch(PDO::FETCH_ASSOC);

$WearingSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id AND wearing = '1' ORDER BY id DESC");
$WearingSrh->execute([":id" => $userid]);
$ReWRS = $WearingSrh->fetchAll();

$GetGameServer = $MainDB->prepare("SELECT * FROM open_servers WHERE jobid = :currentGame");
$GetGameServer->execute([":currentGame" => $Infoe["currentGame"]]);
$GameServer = $GetGameServer->fetch(PDO::FETCH_ASSOC);

if ($GameServer) {
  $GetGame = $MainDB->prepare("SELECT * FROM asset WHERE id = :gameid");
  $GetGame->execute([":gameid" => $GameServer["gameID"]]);
  $Game = $GetGame->fetch(PDO::FETCH_ASSOC);
}



$buildersClubImageShow = false;
$buildersClubImage = "./media/images/buildersclub";
$admininstrationstatusImageShow = false;
$admininstrationstatusImage = "No way? No way!";

if ($Infoe === false) {
    header('Location: ' . $baseUrl . '/error400.php');
    exit();
}

switch($Infoe["membership"]){
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
switch ($Infoe["admin"]) {
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

$getFriends = $MainDB->prepare("SELECT * FROM friends WHERE user1 = $userid OR user2 = $userid");
$getFriends->execute();
$FriendsArr = $getFriends->fetchAll(PDO::FETCH_ASSOC);

$userFriends = 0;

if (!empty($FriendsArr)) {
  foreach($FriendsArr as $row) {
    $userFriends++;
  } 
}

$check2 = $MainDB->prepare("SELECT a.*, IFNULL(os.totalPlayerCount, 0) AS playersOnline
                          FROM asset AS a
                          LEFT JOIN (
                              SELECT gameID, SUM(playerCount) AS totalPlayerCount
                              FROM open_servers
                              WHERE playerCount > 0
                              GROUP BY gameID
                          ) AS os ON a.id = os.gameID
                          WHERE a.approved = '1' AND a.itemtype = 'Place' AND a.creatorid = :id
                          ORDER BY playersOnline DESC
                          LIMIT 6");
$check2->bindParam(':id', $userid, PDO::PARAM_INT);
$check2->execute();
$ActionRows2 = $check2->fetchAll(PDO::FETCH_ASSOC);

$presenceIcon = "offline-img";

if (time() <= intval($Infoe["lastSeenOnline"])+600) {
  $presenceIcon = "online-img";
} 

if (strlen($Infoe["currentGame"]) != 0) {
  $presenceIcon = "ingame-img";
}

$sentFriendRequestFetchSent = $MainDB->prepare("SELECT * FROM friend_requests WHERE user1 = $id AND user2 = $userid");
$sentFriendRequestFetchSent->execute();
$sentFriendRequestSent = $sentFriendRequestFetchSent->fetch(PDO::FETCH_ASSOC);

$haveSentRequest = false;

if ($sentFriendRequestSent) {
  $haveSentRequest = true;
}

$sentFriendRequestFetchRecieved = $MainDB->prepare("SELECT * FROM friend_requests WHERE user1 = $userid AND user2 = $id");
$sentFriendRequestFetchRecieved->execute();
$sentFriendRequestSentRecieved = $sentFriendRequestFetchRecieved->fetch(PDO::FETCH_ASSOC);

$haveRequestRecieved = false;

if ($sentFriendRequestSentRecieved) {
  $haveRequestRecieved = true;
}

$isFriendsFetch = $MainDB->prepare("SELECT * FROM friends WHERE user1 = $id OR user2 = $id
                                      INTERSECT
                                      SELECT * FROM friends WHERE user1 = $userid OR user2 = $userid;");
$isFriendsFetch->execute();
$isFriends = $isFriendsFetch->fetch(PDO::FETCH_ASSOC);

$areFriends = false;

if ($isFriends) {
  $areFriends = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)) ?>">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - <?php echo $Infoe['name'];?></title>
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

    <div class="section-container">

      <div class="friends-container">
        <div style="position: relative;">
          <img src="https://unixfr.xyz/Thumbs/Avatar.ashx?userId=<?php echo $userid; ?>&x=48&y=48" alt="" class="main-player-img"
          id="main-player-img" height="150px" />
          <div class="presence-div">
            <?php
              if  (strlen($Infoe["currentGame"]) != 0 && $areFriends){
                echo '<a href="https://unixfr.xyz/viewgame?id=' .$Game["id"]. '" title="'.$Game["name"].'" >';
              }
            ?>
            
              <span class="<?php echo $presenceIcon; ?>" alt="" ></span>
            <?php
              if  (strlen($Infoe["currentGame"]) != 0 && $areFriends){
                echo '</a>';
              }
            ?>
          </div>
          
        </div>
        
      <div class="user-profile-left-div">
        <div class="user-main-info-div">
          <div class="user-main-name-div" style="display: flex;">
            <h1><?php echo nx($Infoe['name']); ?></h1>
            <?php
if ($buildersClubImageShow == true) {
  echo '<img src="' . $buildersClubImage . '" alt="" height="30" style="margin-top: auto; margin-bottom: auto; margin-right: 5px; margin-left: 5px;">';
}
if ($admininstrationstatusImageShow == true) {
  echo '<img src="' . $admininstrationstatusImage . '" alt="" height="30" style="margin-top: auto; margin-bottom: auto; margin-left: 5px;">';
}
            ;
            ?>
           
          </div>
          
          <?php
            if ($userid == $id){
              
              echo '
                    <form action="/api/status.php" method="GET">
                      <input type="text" name="status" size="80" class="user-status-input-text" value="'. nx($Infoe["status"]).' ">
                    </form>
                      
              ';
            } else {
              echo '<p class="user-status">"'. nx($Infoe["status"]).'"</p>';
            }
          
          ?>
          
          
        </div>

        <div class="user-general-info-div">
          <div class="user-general-info">
            <p class="user-friends-text">Friends</p>
            <p class="user-friends"><?php echo intval($userFriends); ?></p>
          </div>
          <div class="user-general-info">
            <p class="user-friends-text">Following</p>
            <p class="user-friends">1123</p>
          </div>
          <div class="user-general-info">
            <p class="user-friends-text">Followers</p>
            <p class="user-friends">1123</p>
          </div>
        </div>

        
      </div>
        
      <?php
      
            if ($id != $userid) {

              if (!$haveSentRequest) {

                if (!$haveRequestRecieved) {

                  if (!$areFriends) {

                  echo '<div class="user-profile-right-div">
                          <a href="https://unixfr.xyz/api/friendrequest?id='. $userid .'"><button class="user-button">Add Friend</button></a>
                        </div>';

                  } else{

                    echo '<div class="user-profile-right-div">
                            <a href="https://unixfr.xyz/api/friendrequest?id='. $userid .'"><button class="user-button">Remove Friend</button></a>
                          </div>';
                  }

                } else{

                  echo '<div class="user-profile-right-div">
                        <a href="https://unixfr.xyz/api/friendrequest?id='. $userid .'"><button class="user-button">Accept Request</button></a>
                      </div>';

                }

              } else{

                echo '<div class="user-profile-right-div">
                        <a href="https://unixfr.xyz/api/friendrequest?id='. $userid .'"><button class="user-button">Remove Request</button></a>
                      </div>';
              }
              
            }
      
      ?>
        
        
      
    </div>
  </div>

    
    <div class="section-container">
      
      <div class="user-tabs-container">

        <div class="user-button-switch-div" role="group" aria-label="Basic example">
              <a href="#about" style="display: contents; margin-right: 0;"><button type="button" class="user-button-switch" id="about-button">About</button></a>
              <a href="#creations" style="display: contents; margin-right: 0;"><button type="button" class="user-button-switch" id="creations-button">Creations</button></a>
              
        </div>
        
      </div>

    </div>

    <div id="about-div">

      <div class="section-container" >
        <p class="section-text">About </p>
        <div class="user-about-container">
  
          <p class="user-description-p"><?php echo nx($Infoe['about']); ?></p>
          
        </div>

      </div>
      
      <div class="section-container" >
        <p class="section-text">Currently Wearing </p>
        <div class="user-profile-container">
          <div class="user-profile-image-container">
		  
            <img class="avatar-profile-image" src="https://unixfr.xyz/Thumbs/Avatar.ashx?userId=<?php echo $Infoe['id']; ?>" alt="">
          </div>
          <div class="user-profile-wearing-container">
 <?php
switch (true) {
    case ($ReWRS):
        $i = 0;
        foreach ($ReWRS as $AssetInfo) {
            $i++;
            switch (true) {
                case ($i == 6):
                    echo "</br><tr>";
                    break;
            }
            $AssetId = $AssetInfo["boughtid"];
            $AssetName = $AssetInfo["boughtname"];
            $AssetWearing = $AssetInfo["wearing"];

            $buttonText = ($AssetWearing == 1) ? 'Remove' : 'Wear';
            $buttonLink = ($AssetWearing == 1) ? 'removeitem.php' : 'wearitem.php';

            echo '<div class="item-image-card">
              <a href="https://unixfr.xyz/viewitem?id='.$AssetId.'">
                <img src="https://unixfr.xyz/Thumbs/asset.ashx?id='.$AssetId.'&x=320&y=320" alt="'.$AssetName.'" title="'.$AssetName.'" class="item-image-card-img">
              </a>
            </div>';
        }
        break;
    default:
        echo '';
    break;
}
?>
          </div>
        </div>
        
      </div>

      <div class="section-container">
        <p class="section-text">Friends </p>
        <div class="friends-container">
	    <?php
		      if (!empty($ActionRows)) {
          foreach ($ActionRows as $row) {
            $otherUserId = ($row['user1'] == $userid) ? $row['user2'] : $row['user1'];

            if ($otherUserId != $userid) {
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
            $ImageUrl = "https://unixfr.xyz/Thumbs/Avatar.ashx?userId=" . $Results['id'] . "";
              echo '
            <div class="player-card">
                <a style="position:relative;" class="player-card-link" href="http://unixfr.xyz/viewuser?id='. $Results['id'] .' " title="' . $Results['name'] . '">
                    <img
                        src="' . $ImageUrl . '"
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
        
      </div>
      
      
    </div>

    
    <div id="creation-div">
      <div class="section-container">
        <p class="section-text">Games</p>
        
        <div class="continue-games-container">
<?php
if (!empty($ActionRows2)) {
    foreach ($ActionRows2 as $row) {

      echo '
          <div class="game-card">
              <a class="game-card-link" href="http://unixfr.xyz/viewgame?id=' . $row["id"] . '" title="' . $row["name"] . '">
                  <div class="game-card-img-div-square">
                      <img
                          loading="lazy"
                          src="https://unixfr.xyz/Thumbs/AssetIcon.ashx?id=' . $row["id"] . '"
                          alt=""
                          height="150px"
                          width="150px"
                          class="game-card-img"
                      />
                      <p>2017M</p>
                  </div>
                  <p class="game-card-p">
                      ' . nx($row["name"]) . '
                  </p>
                  <p class="game-card-online"> <span class="white-span"> ' . $row['playersOnline'] . ' </span> online</p>
              </a>
          </div>';
            

    }
} else {
    echo '<p class="no-players-found-text">No games found</p>';
}
?>

          
        </div>
      </div> 

      <div class="section-container">
        <p class="section-text">Catalog Items</p>

        <?php

          $query = "SELECT * FROM asset 
              WHERE approved = '1' 
              AND public = '1'
              AND itemtype != 'dev' 
              AND itemtype != 'gamepass' 
              AND itemtype != 'script' 
              AND itemtype != 'animation' 
              AND itemtype != 'place' 
              AND itemtype != 'advertisement' 
              AND itemtype != 'CoreScript' 
              AND itemtype != 'Model'
              AND itemtype != 'Decal'
              AND itemtype != 'Mesh'
              AND creatorid = :uid
              AND offsale IS NULL
              LIMIT 7
          ";

          $CatalogFetch = $MainDB->prepare($query);
          $CatalogFetch->bindParam(":uid", $userid, PDO::PARAM_INT);


          try {
              $CatalogFetch->execute();
              $FetchItems = $CatalogFetch->fetchAll();
          } catch (PDOException $e) {
              echo "PDO Exception: " . $e->getMessage();
          }

          ?>
          
          <div class="item-container">
            <?php
            switch(true){
              case($FetchItems):
                foreach($FetchItems as $ItemInfo){
                  if ($ItemInfo["offsale"] ) {
                    //$lethingo = "<p class=\"item-card-robux-p\">". $ItemInfo['rsprice'];
                    $lethingo = "<p class='off-sale-text'>Off Sale";
                  } else {
                    $lethingo = '<img src="/media/images/robuxicon.png" alt=":thumbs_up:" width="18px" height="18px"
                    class="game-card-likes-img" /><p class="item-card-robux-p">'. $ItemInfo["rsprice"];
                  }


                  echo '
            

                  <div class="item-card" title="'. $ItemInfo['name'] .'">
                    <a class="item-card-link" href="http://unixfr.xyz/viewitem?id='.$ItemInfo['id'] .'">
                    <img loading="lazy" src="https://unixfr.xyz/Thumbs/asset.ashx?id='.$ItemInfo['id'] .'&x=320&y=320" alt="" height="125px" width="125px" class="item-card-img" />
                    <p class="item-card-p">
                      '. $ItemInfo['name'] .'
                    </p>
                    <div class="item-card-info-div">

                      
                      '. $lethingo .'</p>
                      
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

    </div>

    
            
    
  </div>

  

  <script>
    const aboutDiv = document.getElementById("about-div");
    const creationDiv = document.getElementById("creation-div");

    const aboutButton = document.getElementById("about-button");
    const creationButton = document.getElementById("creations-button");

    function toggleElementVisibility(){
      const hash = window.location.hash;
      if (hash === "#about" || hash=="") {
        console.log("about")
        aboutDiv.style.display = "block";
        aboutButton.classList.add("button-selected");

        creationDiv.style.display = "none";
        creationButton.classList.remove("button-selected");
      }
      if (hash === "#creations") {
        console.log("creations")
        aboutDiv.style.display = "none";
        aboutButton.classList.remove("button-selected");

        creationDiv.style.display = "block";
        creationButton.classList.add("button-selected");
      }
    }
    

    window.addEventListener("hashchange", toggleElementVisibility);
    toggleElementVisibility();
  </script>
</body>

</html>