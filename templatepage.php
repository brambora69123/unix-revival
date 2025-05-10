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

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - Page</title>
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>

<body>
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>

  <div class="main-div-container">
    <h1 class="main-title">Page</h1>

    <?php
    if ($backgroundEnabled == 0) {
      echo '<video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>';
    } else {
      echo '<img id="video-background" src="/media/images/background.png"></img>';
    }


    ?>

    <div class="section-container">
      <div class="item-container">
        <p>Stuff goes here...</a>
      </div>
    </div>

  </div>
</body>

</html>