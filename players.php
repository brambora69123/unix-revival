<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

error_reporting(E_ERROR | E_PARSE);

$currentpage = $_GET["page"];

if ($currentpage < 0 || !intval($currentpage)){
  $currentpage = 0;
}

$loadamount = ($currentpage + 1) * 21 ?? 21;
$loadoffset = $loadamount  -  21;
$pageamount = 21;

if (isset($_GET['order'])) {
  $order = $_GET['order'];
} else {
  $order = "iddesc";
}

if (isset($_GET['search'])) {
  $search = $_GET['search'];
} else {
  $search = "";
}

if ($loadamount >= 10000000) {
  header("Location: /error");
}

$itemfilter = "ORDER BY id DESC";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["order"])) {

  switch ($_GET["order"]) {
    case "iddesc":
      $itemfilter = "ORDER BY id DESC";
      break;
    
    case "idasc":
      $itemfilter = "ORDER BY id ASC";
      break;

    case "whar":
      echo "<script>alert('bro are you stupid? it literally says to select a filter, but you didnt do that....')</script>";
      break;

    default:
      $itemfilter = "ORDER BY id DESC";
      echo "<script>alert('yo something happened maybe tell this to the developers or sumthin')</script>";
      break;
  }

}

$loadamount = intval($loadamount);
$loadoffset = intval($loadoffset);
$query = "SELECT * FROM users 
    $itemfilter
    LIMIT :pageamount OFFSET :loadoffset
";

$CatalogFetch = $MainDB->prepare($query);
$CatalogFetch->bindParam(":pageamount", $pageamount, PDO::PARAM_INT);
$CatalogFetch->bindParam(":loadoffset", $loadoffset, PDO::PARAM_INT);

try {
    $CatalogFetch->execute();
    $FetchItems = $CatalogFetch->fetchAll();
} catch (PDOException $e) {
    echo "PDO Exception: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)) ?>">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
</head>

<body>
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php'); ?>
      
  <?php
    if ($backgroundEnabled == 0) {
      echo '<video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>';
    } else {
      echo '<img id="video-background" src="/media/images/background.png"></img>';
    }
  ?>

  <div class="main-div-container">
    <h1 class="main-title">All Users</h1>
    <div class="section-container">
      
      <div class="section-container">
      
        <div class="catalog-topbar">
            <div class="catalog-pagination-container">
              <a href="<?= $_SERVER["PHP_SELF"]."?page=".$currentpage - 1 . "&search=". $search . "&order=". $order?>" id="loader"><button <?php if($currentpage <= 0){ echo "disabled"; } ?> class="catalog-load-shit"><</button></a>
              <p class="catalog-load-p"><?php echo $currentpage+1?></p>
              <a href="<?= $_SERVER["PHP_SELF"]."?page=".$currentpage + 1 . "&search=". $search ."&order=". $order?>" id="loader">
                <button class="catalog-load-shit">></button>
              </a>  
          </div>

          <div class="catalog-dropdown-filter" style="display:flex;">
            <form action="<?= $_SERVER["PHP_SELF"]?>" method="get" class="catalog-filter-form"> <!-- i hate this so much im tired of writing the same shit over and over again so fuck it im gonna make it a form -->
                

                <input name="search" type="text" class="catalog-search-filter" placeholder="Search">


                <select name="order" style="margin-right: 10px;">
                  <option value="iddesc">Newest First</option>
                  <option value="idasc">Oldest First</option>
                  <!--<option value="salesdesc">Most Sales</option>-->
                  
                </select>

                <input type="submit" value="Apply" style="" class="filter-apply-button">

            </form>
            <!--
              <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post" class="catalog-itemtype-form">
                <select name="typeDropdown">
                  <option value="all">All Items</option>
                  <option value="hat">Hat</option>
                  <option value="shirts">Shirts</option>
                  <option value="pants">Pants</option>
                  <option value="teeshirts">T-Shirts</option>
                  <option value="gear">Gear</option>
                  <option value="packagebundle">Package Bundle</option>
                </select>
                <input type="submit" value="Apply" style="" class="filter-apply-button">
              </form>
            -->
            
          </div>
        </div>
        

      </div>
      <div style="display: flex;">
      </div>
      


      <div class="section-container">

        <div id="friends-div">
          <div class="friends-container-wrap">

            <?php
              switch(true){
                    case($FetchItems):
                      foreach($FetchItems as $Results){

                        $name = $Results["id"];

                        $areFriendsFetch = $MainDB->prepare("SELECT * FROM friends WHERE user1 = $id OR user2 = $id
                                                      INTERSECT
                                                      SELECT * FROM friends WHERE user1 = $name OR user2 = $name
                                                      ");
                        $areFriendsFetch->execute();
                        $areFriends = $areFriendsFetch->fetch(PDO::FETCH_ASSOC);

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
                          $presenceText = "Playing";
                          if ($areFriends) {
                            $presenceText = "Playing ".$Game["name"]." ";
                          }
                          
                        }

                        if (strpos(strtolower($Results['name']), strtolower($search)) !== false){
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
                      break;
                        default:
                          echo '<p class="no-players-found-text">No users found.</p>';
                      break;
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