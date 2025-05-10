<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

$gameid = (int) ($_GET['id'] ?? die(header('Location: ' . $baseUrl . '/error.php')));

$GameFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid");
$GameFetch->execute([":pid" => $gameid]);
$Results = $GameFetch->fetch(PDO::FETCH_ASSOC);

$IsBought = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :pid and boughtid = :gid");
$IsBought->bindParam(":gid", $gameid, PDO::PARAM_INT);
$IsBought->bindParam(":pid", $id, PDO::PARAM_INT);
$IsBought->execute();
$IsBoughtResults = $IsBought->fetch(PDO::FETCH_ASSOC);

$boughtItem = false;

if ($IsBoughtResults) {
  if ($IsBoughtResults["boughtby"] == $id) {
    $boughtItem = true;
  }
  
}


switch (true) {
  case (!$Results):
    die(header('Location: ' . $baseUrl . '/error.php'));
    break;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50));
  ; ?>">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix -
    <?php echo nx($Results['name']); ?>
  </title>
  <meta content="Unix - <?php echo nx($Results['name']); ?>" property="og:title" />
  <meta content="A user-generated game created by <?php $Results['creatorname']; ?>" property="og:description" />
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

      <div>
        <img src="https://unixfr.xyz/Thumbs/asset.ashx?id=<?php echo $gameid; ?>&x=320&y=320" alt=""
          class="main-item-page-img" id="main-item-image">
      </div>

      <div class="main-game-info-div">
        <div class="main-game-info-top-div">
          <p class="main-item-title" title="<?php echo nx($Results['name']); ?>">
            <?php echo nx($Results['name']); ?>
          </p>
          
          <p class="main-game-creator">
              
            <span class="main-game-creator-gray-span">By</span> <a href="http://unixfr.xyz/viewuser?id=<?php echo  $Results['creatorid'];?> ">
              <?php echo $Results['creatorname']; ?>
            </a>
          </p>

          <hr class="main-game-hr">

          <div class="main-item-details-div">
            <div><p class="main-item-details-p"><span class="main-game-creator-gray-span">Price:</span></p></div>

            <?php
              if ($Results["offsale"] == 1) {
                echo '
                  <div>

                    <p class="off-sale-item-price" style="width=100%">
                      Off Sale
                    </p>

                  </div>
                ';
              } else {
                
                $thing1 = null;
                $thing2 = null;
                if ($Results["rsprice"] >= 0) {
                  $thing1 = "Robux";
                } else {
                  $thing1 = "Free";
                }
                if ($Results["rsprice"] >= 0) {
                  $thing2 = "Purchase";
                } else {
                  $thing2 = "Get";
                }
                
                if (!$boughtItem) {
                  echo '
                  <div>

                    <p class="main-item-price" style="width=100%">
                        <img src="./media/images/robuxicon.png" alt="" width="15px" height="15px">
                        '. $Results["rsprice"] .'
                    </p>
                    ' . /* ($Results["rsprice"] > 0) ? "Purchase" : "Get"; */ '
                    <a href="/api/buyitem?id='.$gameid.'&method='.$thing1.'">
                      <button class="main-item-button" >
                        '.$thing2 .'
                      </button>
                    </a>

                  </div>
                  ';
                } else{
                  echo '
                  <div>

                    <p class="main-item-price" style="width=100%">
                        <img src="./media/images/robuxicon.png" alt="" width="15px" height="15px">
                        '. $Results["rsprice"] .'
                    </p>
                    ' . /* ($Results["rsprice"] > 0) ? "Purchase" : "Get"; */ '
                    
                    <p class="main-item-button-bought" disabled " >
                      Purchased
                    </p>
                    

                  </div>
                  ';
                }
                
              }
                
            ?>
            
          </div>

          <div class="main-item-details-div">
            
            
            <div><p class="main-item-details-p"><span class="main-game-creator-gray-span">Description:</span></p></div>
            <div>
              <p class="main-item-description-text">
              <?php echo nx($Results['moreinfo']); ?>
              </p>
            </div>
           
          </div>

          <div class="main-item-details-div">
            <div><p class="main-item-details-p"><span class="main-game-creator-gray-span">Type:</span></p></div>
            
            <div>
              <p class="main-item-description-text">
              <?php echo $Results['itemtype']; ?>
              </p>
            </div>
            
          </div>
          
          
         
        </div>


        <div class="main-game-info-bottom-div">
          
        </div>
      </div>




    </div>




  </div>
</body>

</html>