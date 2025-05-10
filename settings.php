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
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Unix - Settings</title>
    <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous" />
</head>

<body>

    <div class="main-div-container">
        <h1 class="main-title">Settings</h1>
      
<?php 
  if ($backgroundEnabled == 0) {
    echo '<video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>';
  } else {
    echo '<img id="video-background" src="/media/images/background.png"></img>';
  }

  
  ?>

        <div class="section-container">
            <!--
            <div class="settings-container-left">
                <ul>
                    <li><a href=""><button>Acount Info</button></a></li>
                    <li><a href=""><button>Acount Info</button></a></li>
                </ul>
            </div>-->

            <div class="settings-container-right">

                <div class="section-container-inside">
                    <div class="setting-info-div">
                        <p class="setting-info-header">Info</p>
                        <div class="setting-info-detail">

                            <p class="setting-detail-p"><span class="main-game-creator-gray-span">Username:</span></p>

                            <input class="setting-detail-info-input" type="text" placeholder=<?php echo $name;?> >

                        </div>

                        <div class="setting-info-detail">

                            <p class="setting-detail-p"><span class="main-game-creator-gray-span">Display Name:</span></p>

                            <input class="setting-detail-info-input" type="text" placeholder=<?php echo $name; ?>>
                        
                        </div>

                        <div class="setting-info-detail">
                    
                            <p class="setting-detail-p"><span class="main-game-creator-gray-span">Password:</span></p>
                            <input class="setting-detail-info-input" type="password" placeholder="••••••••" maxlength="20" minlength="8">
                        
                        </div>

                    
                    
                    
                    </div>
                </div>
                
                <div class="section-container-inside">
                    
                    <div class="setting-info-textarea">

                        <p class="setting-info-header">About</p>

                        <form action="/api/statusandabout.php" method="GET">
                            <textarea type="text" name="about" id="about-textarea" class="settings-textarea" rows="3" maxlength="255"><?php echo $about ?></textarea>
                            <p class="setting-info-header">Status</p>
                            <input type="text" name="status" id="about-textarea" value="<?php echo $status ?>" class="settings-textarea" maxlength="80" rows="1">
                            <button class="settings-info-button" type="submit">Save</button>
                        </form>

                    </div>
                </div>
                <!--
                <div class="section-container-inside">
                    
                    <div class="setting-info-textarea">
                        <p class="setting-info-header">Status</p>
                        <form action="/api/status.php" method="GET">
                            <input type="text" name="text" id="about-textarea" value="<?php echo $status ?>" class="settings-textarea" maxlength="80" rows="1">
                            <button class="settings-info-button" type="submit">Save</button>
                        </form>

                    </div>
                </div>
                -->
                <div class="section-container-inside">
                    <div class="setting-info-div">
                        <p class="setting-info-header">Miscellaneous</p>
                        <div class="setting-info-detail" style="display: block !important;">
                            <form action="/api/togglebg" method="post">    
                                <p class="setting-detail-p">Animated Background</p>
                                <input type="checkbox" class="form-check-input" name="be" id="lol" value="0" <?php if ($backgroundEnabled == 0) echo "checked"; ?>>
                                <label class="form-check-label" for="lol">Enabled</label><br>
                                <input type="submit" class="settings-info-button" value="Save">
                            </form>
                        </div>

                       

                    
                    
                    
                    </div>
                </div>


                
            </div>
        
        </div>

    </div>



</body>

</html>