<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/reghandler.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
  case ($RBXTICKET !== null):
    header("Location: " . $baseUrl . "/home");
    break;
}
$NameErrors = null;
$PassSHOW = null;
$EmailSHOW = null;
$KeySHOW = null;

?>

<head>
  <meta charset="utf-8">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta content="Unix" property="og:title" />
  <meta name="description" content="A ROBLOX private server with 2017m and 2019m (with Android support)." property="og:description" />
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">


  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.0/gsap.min.js"></script>
  <title>Unix - Index</title>
</head>

<style>
.text-red {
    color: #eb5064;
    
}


</style>
<body>
  <div id="root">

    <title>Unix - Index</title>
    </head>

    <body>
      <video id="video-background" src="/media/videos/background.mp4" autoplay muted loop></video>

      <div class="main-img-div-container">
        <img src="/media/images/elogo.png" alt="Unix Logo" id="main-logo" title="Unix! A 2017e ROBLOX revival" />
        <div class="main-container" id="main-container">
          <div>
            <div class="main-list-container" id="main-list-container">
              <a href="#sign-up" class="button-a-tag">
                <button id="register-button" class="main-button">Sign up</button>
              </a>
              <a href="#log-in" class="button-a-tag">
                <button id="login-button" class="main-button">Log in</button>
              </a>

            </div>

            <div class="main-list-container align-items-end" id="sign-up-container">
              <form id="registrationForm" class="form-div list-group list-group-vertical" method="post"
                action="<?php echo $CurrPage; ?>">
                <input
                  class="form-input"
                  type="text"
                  id="username"
                  name="username" 
                  value=""
                  maxlength="20" 
                  placeholder="Username"
                />

				
                <input 
                  class="form-input" 
                  type="password" 
                  id="password"  
                  minlength="8" 
                  maxlength="75" 
                  placeholder="Password (8-75)"
                  name="password" 

				        />

                <input class="form-input" type="password" id="confpass" placeholder="Password Confirm" name="confpass" />
                <input class="form-input" type="text" id="key" placeholder="Registration Key" name="key" style="margin-bottom:0px;"/> <!--aaa-->
        
        <div style='margin-bottom: 10px; margin-top: 5px;'>
				<?php
          switch(true){
            case(count($UserErrors) > 0):
              foreach($UserErrors as $Info){
                $NameErrors = $NameErrors . $Info;
              }
                echo '<div class="validation">
              <table id="UsernameError" class="validator-container" style="display: block;">
                <tbody>
                  <tr>
                    <td>
                      <div class="validator-tooltip-top"></div>
                        <div class="validator-tooltip-main">
                        <div id="usernameErrorMessage" class="text-red"> '. $NameErrors .'</div>
                      </div>
                      <div class="validator-tooltip-bottom"></div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>';
            break;
          }
        ?>
        <?php
        switch(true){
          case(count($PassErrors) > 0):
            foreach($PassErrors as $Info){
              $PassSHOW = $PassSHOW . $Info;
            }
              echo '<div class="validation">
              <table id="UsernameError" class="validator-container" style="display: block;">
                <tbody>
                  <tr>
                    <td>
                      <div class="validator-tooltip-top"></div>
                        <div class="validator-tooltip-main">
                        <div id="usernameErrorMessage" class="text-red">'. $PassSHOW .'</div>
                      </div>
                      <div class="validator-tooltip-bottom"></div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>';
            break;
        }
        ?>
        </div>
        <input class="main-button form-button" type="submit" value="Register" name="registerunx"
          id="registerunx" onclick="registerButtonClicked()" />
        </form>
      </div>

      <div class="main-list-container align-items-end" id="log-in-container">
      <form class="form-div list-group list-group-vertical" method="post" action="#log-in">
        <input 
          class="form-input" 
          type="text" 
          id="user" 
          name="user" 
          value="" 
          placeholder="Username" 
				/>

        <input 
          class="form-input" 
          type="password" 
          id="pass" name="pass" 
          placeholder="Password" 
          style="margin-bottom:0px;"
				/>

        <div  style='margin-bottom: 10px; margin-top: 5px;'>

        <?php
          switch(true){
            case (count($errors) > 0):
              foreach ($errors as $error){
                echo "<div id='usernameErrorMessage' class='text-red'>". $error ."</div>";
              }
              break;
          }
        ?>

        </div>
                
                <input class="main-button form-button" type="submit" value="Login" name="loginunx" id="loginunx" />
              </form>
            </div>
          </div>

          <div class="links-list">

            <a href="https://discord.gg/RV33HUjXdr" class="lists-icon"><img src="/media//images//discordicon.png"
                height="20px" alt="" title="Join our official Discord server!" /></a>

            <a href="/" class="lists-icon"><img src="/media//images//informationicon.png" height="20px" alt=""
                title="Get more information about Unix" /></a>
            <a href="/" class="lists-icon"><img src="/media//images//copyrighticon.png" height="20px" alt=""
                title="See who contributed to the creation of Unix" /></a>
          </div>
        </div>
      </div>
      
      <script>



        let vid = document.getElementById("video-background");
        vid.playbackRate = 0.8;

        function wait(sec) {
          const date = Date.now();
          let currentDate = null;
          do {
            currentDate = Date.now();
          } while (currentDate - date < sec * 1000);
        }

        function sleep(ms) {
          return new Promise((resolve) => setTimeout(resolve, ms));
        }

        // Function to show/hide the element based on the hash fragment

        var usedButton = false;

        var previousHash = window.location.hash;

        function toggleElementVisibility() {
          const signupContainer = document.getElementById("sign-up-container");
          const loginContainer = document.getElementById("log-in-container");
          const mainContainer = document.getElementById("main-container");
          
          const mainContainerHeight = mainContainer.offsetHeight

          const loginContainerHeight = "224px"
          const signupContainerHeight = "336px"
          const indexContainerHeight = "179px"

          mainContainer.style.height = "auto"

          const mainListContainer = document.getElementById(
            "main-list-container"
          );

          const hash = window.location.hash;
          if (hash === "#sign-up") {
            console.log(hash);
            // Show the element
            mainContainer.style.display = "block";
            mainListContainer.style.position = "relative";
            loginContainer.style.position = "relative";
            signupContainer.style.display = "block";
            mainContainer.style.height = mainContainerHeight;
            signupContainer.style.left = "250px";
            loginContainer.style.left = "250px";
            gsap.to(mainListContainer, {
              duration: 0.4,
              right: "250px",
              ease: "power3.out",
            });

            gsap.to(loginContainer, {
              duration: 0.4,
              right: "250px",
              ease: "power3.out",
            });

            sleep(400).then(() => {
              if (hash === "#sign-up") {
                gsap.to(mainContainer, {
                  duration: 0.4,
                  height: signupContainerHeight,
                  ease: "power3.out",
                });
              }
            });

            sleep(800).then(() => {
              if (hash === "#sign-up") {
                signupContainer.style.display = "block";
                mainContainer.style.height = "auto";
                gsap.to(signupContainer, {
                  duration: 0.4,
                  left: "0",
                  ease: "power3.out",
                });
                mainListContainer.style.display = "none";
                loginContainer.style.display = "none";
              }
              
            });

            
          } else if (hash === "") {
            console.log(hash);
            // Hide the element
            mainContainer.style.height = mainContainerHeight;
            mainContainer.style.display = "block";

            gsap.to(signupContainer, {
              duration: 0.4,
              left: "250px",
              ease: "power3.out",
            });

            gsap.to(loginContainer, {
              duration: 0.4,
              left: "250px",
              ease: "power3.out",
            });

            sleep(400).then(() => {
              if (hash === "") {
                gsap.to(mainContainer, {
                  duration: 0.4,
                  width: "250px",
                  height: indexContainerHeight,
                  ease: "power3.out",
                });

                mainListContainer.style.display = "inline";
              }
            });

            sleep(800).then(() => {
              if (hash === "") {
                signupContainer.style.display = "none";
                loginContainer.style.display = "none";
                loginContainer.style.height = "auto";
                mainContainer.style.height = "auto";
                gsap.to(mainListContainer, {
                  duration: 0.4,
                  right: "0",
                  ease: "power3.out",
                });
              }
            });

          } else if (hash === "#log-in") {
            console.log(hash);
            // Hide the element
            mainContainer.style.display = "block";
            mainListContainer.style.position = "relative";
            signupContainer.style.position = "relative";
            loginContainer.style.display = "block";
            mainContainer.style.height = mainContainerHeight;
            loginContainer.style.left = "250px";

            gsap.to(mainListContainer, {
              duration: 0.4,
              right: "250px",
              ease: "power3.out",
            });

            gsap.to(signupContainer, {
              duration: 0.4,
              left: "250px",
              ease: "power3.out",
            });

            sleep(400).then(() => {
              if (hash === "#log-in") {
                gsap.to(mainContainer, {
                  duration: 0.4,
                  width: "auto",
                  height: loginContainerHeight,
                  ease: "power3.out",
                });
              }
            });

            sleep(800).then(() => {
              if (hash === "#log-in") {
                loginContainer.style.display = "block";
                mainContainer.style.height = "auto";
                signupContainer.style.height = "auto";
                gsap.to(loginContainer, {
                  duration: 0.4,
                  left: "0",
                  ease: "power3.out",
                });
                mainListContainer.style.display = "none";
                signupContainer.style.display = "none";
              }
            });
          }
        }
        
        


        window.addEventListener("hashchange", toggleElementVisibility);
        toggleElementVisibility();


      </script>

    </body>
  </div>
</body>
