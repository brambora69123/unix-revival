<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;

switch (true) {
  case ($RBXTICKET == null):
    header("Location: " . $baseUrl . "/");
    die();
    break;
  default:
    // Redirect to the video if $admin is not set to 1
    if ($admin < 1) {
      header("Location: " . $baseUrl . "/");
      die();
    }
    break;
}

$date = date("l");
$timeOfDay = date('a');
$time = "afternoon";

if ($timeOfDay == "pm") {
  $time = "afternoon";
} else {
  $time = "morning";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Index - Admin</title>

  <link rel="stylesheet" href="./index.css?v=<?php echo (rand(1, 50)); ?>">
  <link rel="stylesheet" href="../admindex.css?v=<?php echo (rand(1, 50)); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
</head>

<body>
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/admintopbar.php'); ?>
  <div class="main-div-container">
    <h1 class="main-title">Users</h1>

    <h2 class="admin-subheader">Find User</h2>
    <div class="admin-dashboard-div-section">
      <div class="admin-button-div">
        <input type="number" placeholder="Find user via ID" id="get-user-id" class="admin-text-input margin-right">
        <input type="text" placeholder="Find user via name" id="get-user-name"
          class="admin-text-input admin-text-input ">
      </div>
      <form action="" method="post">
        <button name="find-user" class="admin-button-submit">Find User</button>
      </form>
    </div>

    <h2 class="admin-subheader">All Users</h2>
    <div class="admin-dashboard-div-section">
      <table class="admin-table">
	  <tr class="admin-tr-cell">
          <th class="admin-th-cell-start">Name</th>
          <th class="admin-th-cell">ID</th>
		  <th class="admin-th-cell">Creation Date</th>
          <th class="admin-th-cell-end">Action</th>
        </tr>
      
        <?php

$ActionFetch = $MainDB->prepare("SELECT * FROM users");
$ActionFetch->execute();
$ActionRows = $ActionFetch->fetchAll();

foreach ($ActionRows as $GameInfo) {
  $isBanned = $GameInfo['termtype'] !== NULL;

  echo ' 
  <tr>
    <td>'.$GameInfo['name'].'</td>
    <td>'.$GameInfo['id'].'</td>
    <td>'.$GameInfo['creationdate'].'</td>
    <td>';

    if ($isBanned) {
      echo '<form method="post" action="https://www.unixfr.xyz/api/banuser.php" style="display:inline;">
              <input type="hidden" name="user_to_unban" value="'.$GameInfo['id'].'">
              <button type="submit" name="unban_player" class="admin-table-button admin-moderation-offender-unban">Unban</button>
            </form>';
  } else {
      echo '<button type="button" name="ban_player" class="admin-table-button admin-moderation-offender-ban" onClick="openBanPopup(\'' . $GameInfo["name"] .'\',\'' . $GameInfo["id"] . '\')">Ban</button>';
  }
  

  echo '<button type="submit" name="give_player" class="admin-table-button" onClick="openGivePopup(\'' . $GameInfo["name"] . '\',\'' . $GameInfo["id"] . '\')">Give</button>
    </td>
  </tr>';
}

?>

      </table>
    </div>

  </div>

  <div class="admin-popup-container" id="ban-things-lol">
        <h1 id="admin-ban-popup-header">Ban player (username)</h1>

        <form action="/api/banuser" method="post">
          <select name="bantype" id="bantypeselect" class="admin-text-selector margin-bottom-10" onchange="handletypechange()">
            <option name="0">Warning</option>
            <option name="banned1">1 Day</option>
            <option name="banned3">3 Days</option>
            <option name="banned7">7 Days</option>
            <option name="banned14">14 Days</option>
            <option name="terminated">Termination</option>
          </select>
          <select name="banreason" id="banreasonselect" class="admin-text-selector margin-bottom-10" onchange="handlereasonchange()">
            <option name="0">Hate Speech</option>
            <option name="1">NSFW/NSFL</option>
            <option name="2">Harrasment</option>
            <option name="3">Spam</option>
            <option name="4">Scamming</option>
            <option name="5">Nazi Content (for some reason)</option>
            <option name="6">DMCA</option>
            <option name="7">Compromised Account</option>
            <option name="8">Discriminatory Content</option>
            <option name="9">Exploiting</option>
            <option name="10">Grooming/Pedophilia</option>
            <option name="11">Doxxing</option>
            <option name="12">Impersonation</option>
            <option name="13">Illegal Content</option>
            <option name="14">Real-Life Threats</option>
            <option name="15">Promoting IRL Dangerous Activities (such as suicide)</option>
            <option name="16">Death Threats</option>
            <option name="17">Death Threats Towards Admins</option>
            <option name="18">Underaged</option>
			      <option name="19">Strong Profanity</option>
			      <option name="20">Alt Account</option>
            <option name="69">Other</option>
          </select>
          <input type="text" class="admin-text-input margin-bottom-10" name="deathnote" placeholder="Note">
          <input type="hidden" name="usertoban" value="idk" id="lesexo">
          <input type="hidden" name="bantype" value="idk" id="elsexo">
          <input type="text" class="admin-text-input margin-bottom-10" name="oitem" id="oitem" placeholder="Offensive Item">

          <div class="admin-button-div">
            <button class="admin-submit-button admin-button" name="ban-button">Ban</button>
            <button class="admin-submit-button admin-button" onclick="closeBanPopup()" type="button" style="margin-left: 10px;">i misclicked close this pls</button>
          </div>
          
         
        </form>
        
  </div>

  <div class="admin-popup-container" id="give-things-lol">
        <h1 id="admin-give-popup-header">Give player (username)</h1>

        <form action="/api/giveuser" method="post">

          <div class="admin-button-div">
            <input type="text" class="admin-text-input" name="value" placeholder="Value">
            <select name="type" id="" class="admin-text-selector" style="margin-left: 10px;">
              <option value="give-robux">Give Robux</option>
              <option value="set-robux">Set Robux</option>
              <option value="give-item">Give / Remove Item</option>
              <option value="give-bc">Give BC (0 None, 1 BC, 2 TBC, 3 OBC)</option>
              <option value="add-friend">Add / Remove Friend</option>
            </select>
          </div>
          
          <input type="hidden" name="usertogive" value="idk" id="usertogive">

          <div class="admin-button-div">
            <button class="admin-submit-button admin-button" name="give-button">Give</button>
            <button class="admin-submit-button admin-button" onclick="closeGivePopup()" type="button" style="margin-left: 10px;">i misclicked close this pls</button>
          </div>
          
         
        </form>
        
  </div>

<script>
  let lethingo = document.getElementById("ban-things-lol");
  let bantypeselect = document.getElementById("bantypeselect");
  let usertoban = document.getElementById("lesexo");
  let bantype = document.getElementById("elsexo");

  let giveDiv = document.getElementById("give-things-lol");
  let usertogive = document.getElementById("usertogive");

  function openBanPopup(name, id) {
    document.getElementById("admin-ban-popup-header").innerHTML = `Ban Player "${name ?? "my ass"}" `;
    //document.getElementById("THIS-IS-REALLY-FUCKING-IMPORTANT-DONT-REMOVE-PLEASE").innerHTML = `${id}`;
    usertoban.value = name;
    lethingo.classList.add("shown");
  }
  
  function openGivePopup(name, id) {
    document.getElementById("admin-give-popup-header").innerHTML = `Give Player "${name ?? "my ass"}" `;
    //document.getElementById("THIS-IS-REALLY-FUCKING-IMPORTANT-DONT-REMOVE-PLEASE").innerHTML = `${id}`;
    usertogive.value = id;
    giveDiv.classList.add("shown");
  }

  function closeBanPopup() {
    if (lethingo.classList.contains("shown")) {
      lethingo.classList.remove("shown");
    }
  }

  function closeGivePopup() {
    if (giveDiv.classList.contains("shown")) {
      giveDiv.classList.remove("shown");
    }
  }

  function handletypechange() {
    bantype.value = bantypeselect.options[bantypeselect.selectedIndex].getAttribute("name");
  }
  function handlereasonchange() {
    bantype.value = bantypeselect.options[bantypeselect.selectedIndex].getAttribute("name");
  }
      
</script>
</body>

</html>
