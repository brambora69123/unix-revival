<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/reghandler.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');


?>

<head>
  <meta charset="utf-8">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta content="Unix" property="og:title" />
  <meta content="A 2017 Roblox private server." property="og:description" />
  <meta content="https://unixfr.xyz/" property="og:url" />
  <meta content="https://unixfr.xyz/media/images/elogo.png" property="og:image" />
  <meta content="#800080" data-react-helmet="true" name="theme-color" />
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)) ?>">

  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300;400;500;600;700&family=Roboto&display=swap"
    rel="stylesheet" />



  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.0/gsap.min.js"></script>
  <title>Unix - Index</title>
</head>

<body>
  <div id="root">

    <title>Unix - Index</title>
    </head>

    <body>
      <img id="video-background" src="/media/images/background.png"></img>

      <div class="main-img-div-container">
        <img src="/media/images/elogo.png" alt="Unix Logo" id="main-logo" title="Unix! A 2017e ROBLOX revival" />
        <div class="main-container" id="main-container">
          <div>
            <div class="main-list-container align-items-end">
              <form class="form-div list-group list-group-vertical" method="post">
                <input class="form-input" type="text" id="user" name="user" value="" placeholder="Username" />
                <input class="form-input" type="password" id="pass" name="pass" placeholder="Password" />
                <input class="main-button form-button" type="submit" value="Login" name="loginunx" id="loginunx" />
              </form>
            </div>
          </div>

        </div>
      </div>
      
     

    </body>
  </div>
</body>
