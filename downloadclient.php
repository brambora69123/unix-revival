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
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)) ?>">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - Download</title>
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
    <div class="section-text-div">
        <p class="section-text">Download Client</p>
      </div>
      
    <div class="download-container">

    <div class="download-card-group">
      <div class="download-card">
        <div class="float-left">
          <p class="download-year">2015M</p>
          <p>Downloads the 2015M client</p>
          <a href="/unix15.exe"><button class="download-button" >Download .exe</button></a>
          <div class="float-right">   
            <img class="download-logo" src="/media/images/2015logo.png" alt="2015 logo">
            <!--<button class="download-button">Download .apk</button>-->
          </div>
        </div>
      </div>

      <div class="download-card">
        <div class="float-left">
          <p class="download-year">2017M</p>
          <p>Downloads the 2017M client</p>
          <a href="/unixbootstrapper.exe"><button class="download-button" >Download .exe</button></a>
          <a href="/unix17.apk"><button class="download-button" >Download .apk</button></a>
          <div class="float-right">   
            <img class="download-logo" src="/media/images/2017logo.png" alt="2017 logo">
            <!--<button class="download-button">Download .apk</button>-->
          </div>
        </div>
      </div>

      
    </div>
      
    <div class="download-card-group">

      <div class="download-card">
        <div class="float-left">
          <p class="download-year">2019M</p>
          <p>Downloads the 2019M client</p>
          <a href="/unix19bootstrapper.exe"><button class="download-button" >Download .exe</button></a>
          <div class="float-right">   
            <img class="download-logo" src="/media/images/2017logo.png" alt="2017 logo">
            <!--<button class="download-button">Download .apk</button>-->
          </div>
        </div>
      </div>

      <div class="download-card">
        <div class="float-left">
          <p class="download-year">2021E</p>
          <p>Downloads the 2021E client</p>
          <a href="/unix21.exe"><button class="download-button" >Download .exe</button></a>
          <div class="float-right">   
            <img class="download-logo" src="/media/images/2019logo.png" alt="2019 logo">
            <!--<button class="download-button">Download .apk</button>-->
          </div>
        </div>
      </div>

      
    </div>

  </div>

    
  </div>
</body>

</html>