<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

$terms = array(
    '0',
	'idk',
    'banned1',
    'banned3',
    'banned7',
    'banned14',
    'terminated'
);


if (!in_array($termtype, $terms)) {
    header('Location: /');
    exit();
}
$termtext = '';


switch ($termtype) {
    case '0':
        $termtext = 'Warning';
        break;
	case 'idk':
        $termtext = 'Warning';
        break;
    case 'terminated':
        $termtext = 'Account Deleted';
        break;
    case preg_match('/^banned(\d+)$/', $termtype, $matches) ? true : false:
        $termtext = "Banned for {$matches[1]} Days";
        break;
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - Banned</title>
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)); ?>">
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
  
  <div class="main-div-container">
    
    <div class="section-container">
     

        <div class="ban-container">
            <div class="ban-type-container">
                <h1 class="ban-type-text"><?= $termtext ?></h1>
            </div>

            <div class="ban-misc-text-container">
                <p class="ban-misc-text">
                    <?php
                    if ($termtype === "terminated") {
                        echo "Our moderators have determined that your behavior at Unix has been in violation of our standards. You may or may not appeal this ban.";
                    } else {
                        echo "Our moderators have determined that your behavior at Unix has been in violation of our standards. We will terminate your account if you do not abide by the rules.";
                    }
                    ?>    
                </p>
                <? // Moderated at note was here before. Removed to prevent error. ?>
            </div>

            <div class="ban-modnote-text-container">
                <p class="ban-modnote-text">
                    Moderator Note: <b><?= $termnote ?></b>
                </p>
            </div>
            
            <div class="ban-reason-and-offsensive-item-text-container">
                <div class="ban-reason-text-container">
                    <p class="ban-reason-text">
                        <b>Reason:</b> <?= $termreason ?>
                    </p>
                </div>

                <div class="offensive-item-text-container">
                    <p class="offensive-item-text">
                        <b>Offensive Item:</b> <?php echo $toi; ?>
                        
                    </p>
                </div>
            </div>
            
            <br>

            <div class="ban-bottom-text-container">
                    <p class="ban-bottom-text">
                        Please abide by our community guidelines so that Unix can be fun for everyone.
                    </p>
                    <p class="ban-bottom-text">
                        Your account has been terminated. You will not be able to reactivate your account, unless you send an appeal and it gets accepted.
                    </p>
                    <p class="ban-bottom-text">
                        If you wish to appeal, well u cant do that yet
                    </p>
                    <center>
                        <button onclick="document.location = '/api/logout.php?rUrl=/'">Log Out</button>
                        <button onclick="document.location = '/api/reactivate.php'">Re-Activate my Account</button>
                    </center>
            </div>
        </div>



  </div>
</body>
  