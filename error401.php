

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - Error</title>
    <link rel="stylesheet" href="http://unixfr.xyz/index.css?v=<?php echo (rand(1, 50)); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
</head>

<body>

<?php 
  if ($backgroundEnabled == 0) {
    echo '<video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>';
  } else {
    echo '';
  }
  
  ?>
  

    

     

        <div class="error-container" >
            <div class="ban-type-container" >
                <h1 class="error-text">Error</h1>
            </div>

            <div class="ban-misc-text-container" >
                <p class="ban-misc-text" style="margin-bottom: 0px;">
                    Something unexpected happen. Anything can cause this error page such as a page that doesn't exist or an internal server error.
                    <br><br>
                    <center><p class="ban-misc-text">Le error code is 401. Unauthorized.</p></center>
                </p>
                <p class="kaomoji-text">
                ¯\_(ツ)_/¯
                </p>
            </div>



        </div>


   




</body>
  