<?php 

try {
    $dbconnectiontestthingballsack = new PDO("mysql:host=$hostdb;dbname=$namedb", $accdb, $passdb);
} catch (Exception $e) {
    header('Location: /maintenance.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbarmaintenance.php'); ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - Maintenance</title>
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
</head>

<body>

  <video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>
  

    

     

        <div class="error-container" >
            <div class="ban-type-container" >
                <h1 class="error-text">Maintenance</h1>
            </div>

            <div class="ban-misc-text-container" >
                <p class="ban-misc-text" style="margin-bottom: 0px; text-align: center;">
                    MySQL had an error, so you are here.
                </p>
                <p class="kaomoji-text">
                ¯\_(ツ)_/¯
                </p>
            </div>



        </div>


   




</body>
  