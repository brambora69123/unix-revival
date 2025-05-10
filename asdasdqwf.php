<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

$gameid = (int) ($_GET['id'] ?? die(header('Location: ' . $baseUrl . '/error.php')));

$GameFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :pid ");
$GameFetch->execute([":pid" => $gameid]);
$Results = $GameFetch->fetch(PDO::FETCH_ASSOC);

switch (true) {
  case (!$Results):
    die(header('Location: ' . $baseUrl . '/error.php'));
    break;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>
  <title>Unix -
    <?php echo $Results['name']; ?>
  </title>
  <meta content="Unix - <?php echo $Results['name']; ?>" property="og:title" />
  <meta content="A user-generated game created by <?php $Results['creatorname']; ?>" property="og:description" />
  <meta content="https://unixfr.xyz/" property="og:url" />
  <meta content="https://unixfr.xyz/media/images/elogo.png" property="og:image" />
  <meta content="#800080" data-react-helmet="true" name="theme-color" />
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50));
  ; ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
  <video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>
  <div class="main-div-container">
    <div class="main-game-div-container">

      <div>
        <img src="https://unixfr.xyz/Thumbs/asset.ashx?id=<?php echo $gameid; ?>&x=320&y=320" alt=""
          class="main-item-page-img" id="main-item-image">
      </div>

      <div class="main-game-info-div">
        <div class="main-game-info-top-div">
          <p class="main-item-title" title="<?php echo $Results['name']; ?>">
            <?php echo $Results['name']; ?>
          </p>
          
          <p class="main-game-creator">
              
            <span class="main-game-creator-gray-span">By</span> <a href="">
             <?php echo $Results['creatorname']; ?>
            </a>
          </p>

          <hr class="main-game-hr">

          <div class="main-item-details-div">
            <div><p class="main-item-details-p"><span class="main-game-creator-gray-span">Price:</span></p></div>
            
            <div>
            <?php
              if ($Results["offsale"] == 1) {
                echo '
                <p class="off-sale-item-price" style="width=100%">
                    Off Sale
                </p>';
              } else {
                echo '
                <p class="main-item-price style="width=100%">
                    <img src="./media/images/robuxicon.png" alt="" width="15px" height="15px">
                    '. $Results["rsprice"] .'
                </p>
                ';
              }
              
            ?>

            <?php
              if ($Results["offsale"] == 1) {
               // do nothing
              } else {
                $shitdapants = ($Results["rsprice"] > 0) ? "Robux" : "Free";
                echo '
                  <a href="/api/buyitem?id=1&method=$shitdapants">
                ';
                /* 
                <a href="/api/buyitem?id='. $gameid . '&method='. $shitdapants . ' >
                <button class="main-item-button" style="display:block;">
                  '. ($Results["rsprice"] > 0) ? "Purchase" : "Get" .'
                </button>
              </a>
                */
              }
              
            ?>

              

            </div>
            
          </div>

          <div class="main-item-details-div">
            
            
            <div><p class="main-item-details-p"><span class="main-game-creator-gray-span">Description:</span></p></div>
            <div>
              <p class="main-item-description-text">
              <?php echo $Results['moreinfo']; ?>
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