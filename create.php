<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}
require($_SERVER['DOCUMENT_ROOT'] . '/func.php');

/* 


*/

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)) ?>">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - Create</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>
  <?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php'); ?>

  <!--<div class="jumpscare" id="funny-img"><audio src="/media/audio/subspace-tripmine.mp3"  id="funny-audio"></audio></div>

  <script>
    setTimeout(function(){
      document.body.onmousedown = function() { 
        setTimeout(function(){
          document.getElementById("funny-img").style.display = "block";
          document.getElementById("funny-audio").play();
        }, 1000);
      }
    }, 1000);
  </script>-->

  <div class="main-div-container">
    <h1 class="main-title">Create</h1>
  
<?php 
  if ($backgroundEnabled == 0) {
    echo '<video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>';
  } else {
    echo '<img id="video-background" src="/media/images/background.png"></img>';
  }

  
  ?>
    
    <div class="flex-section-container">
      <div class="create-createwhat-container">
        
        <p class="create-createwhat" onclick="">Clothes</p>
        <p class="create-createwhat" onclick="this.innerHTML = 'WIP'">Models</p>
        <p class="create-createwhat" onclick="this.innerHTML = 'WIP'">Decals</p>
        <p class="create-createwhat" onclick="this.innerHTML = 'WIP'">Badges</p>
        <p class="create-createwhat" onclick="this.innerHTML = 'WIP'">Game Passes</p>
        <p class="create-createwhat" onclick="this.innerHTML = 'WIP'">Audio</p>
        <p class="create-createwhat" onclick="this.innerHTML = 'WIP'">User Ads</p>
        
      </div>

      <div class="create-actualthing please-dont-have-flexbox-i-beg-you ">
        <h1 class="" onclick="">Create clothing</h1>
        <p class="private-thing">Your clothing will be private until you change it to public.</p>

        <form action="/api/publiclothinguploader" method="post" enctype="multipart/form-data">

          <div class="input-flex-div">
            <input name="name" class="create-text-input" style="margin-bottom: 10px; margin-right: 10px;" placeholder="Clothing name"/>
            <input name="price" class="create-text-input" style="margin-bottom: 10px; margin-right: 10px;" placeholder="Clothing price"/>

            <select name="clothingtype" class="select-input" style="margin-bottom:10px;"> 
              <option value="Shirt">Shirt</option>
              <option value="TShirt">T-Shirt</option>
              <option value="Pants">Pants</option>
            </select>
          </div>

<input type="file" id="decalfile" class="placefile-uploader" name="decalfile" accept=".png, .jpg, .jpeg" required />

          <input type="submit" name="create_game" class="create-button-submit" value="Upload Clothing"></input>
        </form>
        <br>
        <br>
        <!--<h1><?php/* 
        $rand = rand(0,50);
        if ($rand == 1) {
          echo "<h1>Our Games</h1>";
        } else {
          echo "<h1>Your Games</h1>";
        }*/
        
        ?></h1>-->

        <!--<div class="create-yourgames-container">
          <?php
          /*$getgamesq = $MainDB->prepare("SELECT * FROM asset WHERE creatorname = :cname AND itemtype = 'Place' ORDER BY id DESC");
          $getgamesq->bindParam(":cname", $name, PDO::PARAM_STR);
          $getgamesq->execute();
          
          if ($getgamesq->rowCount() < 1) {
            echo "<p>Nothing here</p>";
           }
          while ($results = $getgamesq->fetch(PDO::FETCH_ASSOC)) {
            $pee = $results["createdon"];
            $poo = null;
            if (!isset($pee) || $pee == "") {
              $pee = "Unspecified";
            }
            if ($results["approved"] == 1 && $results["public"] == 1) {
              $poo = "Public";
            } else {
              $poo = "Private/Unknown";
            }
            if ($poo == "Public") {
              echo '<div class="create-yourgames-game" style="display:flex;">
              <img src="/Thumbs/AssetIcon.ashx?id='.$results["id"].'" width="64" height="64">
                <div class="create-yourgames-textstuff">
                  <a class="create-yourgames-game-gamename" href="https://unixfr.xyz/viewgame?id='.$results["id"].'">'.nxe($results["name"]).'</a>
                  <p class="create-yourgames-game-createdwhen">Created '.$pee.'</p>
                  <p class="create-yourgames-game-publicity">'.$poo.'</p>
                </div>
            </div>';
            } else {
              echo '<div class="create-yourgames-game" style="display:flex;">
            <img src="/Thumbs/AssetIcon.ashx?id='.$results["id"].'" width="64" height="64">
              <div class="create-yourgames-textstuff">
              <a class="create-yourgames-game-gamename" href="https://unixfr.xyz/viewgame?id='.$results["id"].'">'.nxe($results["name"]).'</a>
                <p class="create-yourgames-game-createdwhen">Created '.$pee.'</p>
                <p class="create-yourgames-game-publicity">'.$poo.'</p>
              </div>
          </div>';
            }
            
          }*/
          ?>
        </div>-->
      </div>
    </div>

  </div>
</body>

</html>