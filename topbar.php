<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

if ($RBXTICKET == null) {
    die(header("Location: " . $baseUrl . "/"));
}

$UpdateLastSeen = $MainDB->prepare("UPDATE users SET lastSeenOnline = :lastSeenOnline WHERE id = :id");

$currentTimestamp = time();

$UpdateLastSeen->bindParam(":lastSeenOnline", $currentTimestamp, PDO::PARAM_INT);
$UpdateLastSeen->bindParam(":id", $id, PDO::PARAM_INT);

$UpdateLastSeen->execute();

$check = $MainDB->prepare("SELECT * FROM notification WHERE userId = :id OR userId = 0");
$check->bindParam(":id", $id, PDO::PARAM_INT);
$check->execute();
$ActioneRowes = $check->fetchAll(PDO::FETCH_ASSOC);

$checkFriends = $MainDB->prepare("SELECT * FROM friend_requests WHERE user2 = :id");
$checkFriends->bindParam(":id", $id, PDO::PARAM_INT);
$checkFriends->execute();
$friendRequests = $checkFriends->fetchAll(PDO::FETCH_ASSOC);


$userQuery = $MainDB->prepare("SELECT nextrobuxgive, membership, robux FROM users WHERE id = :id");
$userQuery->bindParam(":id", $id, PDO::PARAM_INT);
$userQuery->execute();
$user = $userQuery->fetch(PDO::FETCH_ASSOC);

$nextRobuxGive = $user['nextrobuxgive'];
$membershipLevel = $user['membership'];
$currentRobux = $user['robux'];

if ($currentTimestamp >= $nextRobuxGive) {
    switch ($membershipLevel) {
        case 0:
            $newRobux = 30;
            break;
        case 1:
            $newRobux = 50;
            break;
        case 2:
            $newRobux = 70;
            break;
        case 3:
            $newRobux = 90;
            break;
        default:
            $newRobux = 0;
            break;
    }

    $newRobuxBalance = $currentRobux + $newRobux;
    $nextRobuxGiveTimestamp = $currentTimestamp + 86400;
    $updateRobuxQuery = $MainDB->prepare("UPDATE users SET robux = :robux, nextRobuxGive = :nextRobuxGive WHERE id = :id");
    $updateRobuxQuery->bindParam(":robux", $newRobuxBalance, PDO::PARAM_INT);
    $updateRobuxQuery->bindParam(":nextRobuxGive", $nextRobuxGiveTimestamp, PDO::PARAM_INT);
    $updateRobuxQuery->bindParam(":id", $id, PDO::PARAM_INT);
    $updateRobuxQuery->execute();
}
?>


  <nav class="main-navbar">
    <div class="main-navbar-content">
      <div class="main-navbar-left-div">
        <a href="/home">
          <img class="main-navbar-logo" src="/media/images/elogo.png" alt="Logo" title="Unix!">
        </a>
        <a href="/home">
          <button class="main-navbar-button">Home</button>
        </a>
        <a href="/games">
          <button class="main-navbar-button">Games</button>
        </a>
        <a href="/catalog">
          <button class="main-navbar-button">Catalog</button>
        </a>
        <!--<a href="/players">
          <button class="main-navbar-button">Users</button>
        </a>-->
        <a href="/avatar">
          <button class="main-navbar-button">Avatar</button>
        </a>
        <!--<a href="/viewuser?id=<?php echo $id; ?>">
          <button class="main-navbar-button">Profile</button>
        </a>-->
        <!--<a href="/downloadclient">
          <button class="main-navbar-button">Download</button>
        </a>-->
        <!--<a href="/create">
          <button class="main-navbar-button">Create</button>
        </a>-->
        <?php
        if ($admin >= 1) {
          echo '
          <a href="/supersecretadminpanel/">
          <button class="main-navbar-button">Admin</button>
          </a>';
        }
        if ($admin >= 2) {
          echo '
          <a href="/catalogmanagerpanelxdsorealnofake/">
          <button class="main-navbar-button">Panel</button>
          </a>';
        }
        ?>
      </div>

      <div class="main-navbar-center-div">
        <div class="search-bar-div">
          <input type="text" class="navbar-search" id="search-bar" onFocus="onFocusFunc()" onfocusout placeholder="Search" onchange="onChange();"
            onkeyup="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();" onkeydown="search(this);">
          <ol id="search-list">
            <a href="" id="game-a"><ul> <span id="game-span"></span> in Games</ul></a>
            <a href="" id="catalog-a"><ul> <span id="catalog-span"></span > in Catalog</ul></a>
            <a href="" id="user-a"><ul style="border-bottom: none !important;"> <span id="user-span"></span> in Users</ul></a>
          </ol>
        </div>
      </div>

      <div class="main-navbar-right-div">
        <div class="smaller-user-card">
          <img src="./renders/<?php echo $id; ?>-closeup.png" alt="" width="40px">
        </div>
        
        <p class="main-navbar-text">
          <?php echo $name; ?>
        </p>

        <p class="main-navbar-line">
          |
        </p>

        <p class="main-navbar-text" style="text-align: center;">
          <img src="/media/images/whiterobuxicon.png" alt="" width="25px">
          <?php echo $robux; ?>
        </p>

        <a href="/settings">
          <button class="main-navbar-button">Settings</button>
        </a>

        <a href="/api/logout?rUrl=/">
          <button class="main-navbar-button">Logout</button>
        </a>

        <button class="main-navbar-button" onclick="notificationFunc()">Notifications 
            <span class="notification-badge" style="display: none;" id="notification-badge">1</span>
        </button>
      </div>
    </div>
  </nav>

  <div class="notification-div" id="notification-div">
    <?php
      if (!empty($ActioneRowes)) {
        foreach ($ActioneRowes as $row) {
          if ($row["type"] == "info") {
            echo '<div class="notification-card info">
                    <p>' . $row["text"] . ' <br> <span class="date-span">'. date("M j Y" , $row["time"]) .' at '. date("G:i" ,$row["time"]) .'</span></p>
                    <img src="./media/images/info.png" title="Info" alt="" width="30px" height="30px">
                  </div>';
          } elseif ($row["type"] == "warn") {
            echo '<div class="notification-card warn">
                    <p>' . $row["text"] . ' <br> <span class="date-span">'. date("M j Y" , $row["time"]) .' at '. date("G:i" ,$row["time"]) .'</span></p>
                    <img src="./media/images/warn.png" title="Info" alt="" width="30px" height="30px">
                  </div>';
          }
        }
      } else {
        echo '<p class="no-players-found-text" style="margin-top: 50%"> No notifications. </p>';
      }

      if (!empty($friendRequests)) {
        foreach ($friendRequests as $row) {
          $userid = $row["user1"];

          $getUser = $MainDB->prepare("SELECT * FROM users WHERE id = $userid ");
          $getUser->execute();
          $user = $getUser->fetch(PDO::FETCH_ASSOC);

          echo '<div class="notification-card friend">

                  <div class="notification-card-main">
                    <a href="viewuser.php?id='. $user["id"] .'">
                      <div class="small-user-card" style="margin-right:10px;">
                        <img src="./renders/'. $user["id"] .'-closeup.png" alt="" width="50px">
                      </div>
                    </a>
                      <div class="friend-notification-p-div">
                        <p> '. $user["name"] .' wants to be friends with you!</p>
                        <p><span class="date-span">'. date("M j Y" , $row["time"]) .' at '. date("G:i" ,$row["time"]) .'</span></p>
                      </div>
                    <img src="./media/images/friend.png" title="Friend Request" alt="" width="30px" height="30px">
                  </div>

                  <div class="friend-notification-button-div">
                    <a href="https://unixfr.xyz/api/ignorerequest?id='.$user["id"].'">Ignore</a>
                    <a href="https://unixfr.xyz/api/friendrequest?id='.$user["id"].'" style="margin-right: 0;">Accept</a>
                  </div>

                </div>';
        }
      }
        
    ?>
  </div>

  <div class=""></div>
<script>


const searchBar = document.getElementById("search-bar");
var searchValue = "";

const gameSpan = document.getElementById("game-span");
const catalogSpan = document.getElementById("catalog-span");
const userSpan = document.getElementById("user-span");

const gameA = document.getElementById("game-a");
const catalogA = document.getElementById("catalog-a");
const userA = document.getElementById("user-a");

function search(ele) {
    if(event.key === 'Enter') {
        window.location.replace(`https://unixfr.xyz/games?search=${searchValue}`);     
    }
}

function onChange() {
  searchValue = searchBar.value;
  onFocusFunc();

  gameSpan.innerHTML = searchValue;
  catalogSpan.innerHTML = searchValue;
  userSpan.innerHTML = searchValue;

  gameA.href = `https://unixfr.xyz/games?search=${searchValue}`
  catalogA.href = `https://unixfr.xyz/catalog?search=${searchValue}`
  userA.href = `https://unixfr.xyz/players?search=${searchValue}`

}

searchBar.addEventListener("focusout", (event) => {
  setTimeout(function(){
      document.getElementById("search-list").style.display = "none";
  }, 300);

});

function onFocusFunc(){
  if (searchValue != "") {
    document.getElementById("search-list").style.display = "block";

  } else{
    document.getElementById("search-list").style.display = "none";
  }
}

async function fetchNotificationCount() {
    try {
        let response = await fetch('https://unixfr.xyz/api/getunreadnotifs');
        if (response.ok) {
            let data = await response.json();
            if (data.notificationcount == 0){
              document.getElementById('notification-badge').style.display = "none"; 
            } else{
              document.getElementById('notification-badge').style.display = "flex"; 
            }
            
            document.getElementById('notification-badge').textContent = data.notificationcount;
        } else {
            console.error('Failed to fetch notification count:', response.statusText);
        }
    } catch (error) {
        console.error('Error fetching notification count:', error);
    }
}

async function readAllNotifications() {
  try {
        let response = await fetch('https://unixfr.xyz/api/readallnotifs');
        if (response.ok) {
            let data = await response.json();
            document.getElementById('notification-badge').textContent = 0;
            document.getElementById('notification-badge').style.display = "none";
        } else {
            console.error('Failed to fetch notification count:', response.statusText);
        }
    } catch (error) {
        console.error('Error fetching notification count:', error);
    }
}


fetchNotificationCount();

</script>
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
  <script src="/hoverbuttons.js?version=100"></script>

  <script>
    var notificationOpen = true;
    const notification = document.getElementById("notification-div")

    function notificationFunc() {
      if (notificationOpen == true) {
        fetchNotificationCount()
        notificationOpen = false;

        gsap.to(notification, {
        duration: 0.2,
        right: "10px",
        ease: "circ.out",
        });

      } else {
        notificationOpen = true;

        gsap.to(notification, {
        duration: 0.2,
        right: "-510px",
        ease: "circ.out",
        });

        readAllNotifications()
      }
    }

  </script>

  <!--
  
  <div class="notification-card friend">

    <div class="notification-card-main">
      <a href="viewuser.php?id=37">
        <div class="small-user-card" style="margin-right:10px;">
          <img src="./renders/37-closeup.png" alt="" width="50px">
        </div>
      </a>
        <div class="friend-notification-p-div">
          <p>bin guy wants to be friends with you!</p>
          <p>Request sent at 9/11/2001</p>
        </div>
      <img src="./media/images/friend.png" title="Friend Request" alt="" width="30px" height="30px">
    </div>

    <div class="friend-notification-button-div">
      <button>Ignore</button>
      <button>Accept</button>
    </div>

  </div>
  
  -->
</body>

</html>
