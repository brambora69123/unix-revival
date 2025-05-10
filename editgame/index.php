<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}
$gameid = $_GET["id"] ?? die("Enter a game id");
$text = $_GET["text"] ?? "e";

$getgameinfo = $MainDB->prepare("SELECT * FROM asset WHERE id = :id AND itemtype = 'Place'");
$getgameinfo->bindParam(":id", $gameid, PDO::PARAM_INT);
$getgameinfo->execute();
$results = $getgameinfo->fetch(PDO::FETCH_ASSOC);

if ($results["creatorname"] !== $name) {
    die("You don't own this game.");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - Configure</title>
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

  <div class="main-div-container">
    <h1 class="main-title">Configure Game</h1>
  
<?php 
  if ($backgroundEnabled == 0) {
    echo '<video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>';
  } else {
    echo '<img id="video-background" src="/media/images/background.png"></img>';
  }

  
  ?>
    
    <div class="flex-section-container" >
      <div class="create-createwhat-container" style="max-width: 127px; max-height: 488px;">
        <p class="create-createwhat" onclick="hideEverythingBut(document.getElementById('basicsettings'))">Basic Settings</p>
        <p class="create-createwhat" onclick="hideEverythingBut(document.getElementById('icon'))">Icon</p>
        <p class="create-createwhat" onclick="hideEverythingBut(document.getElementById('thumbnail'))">Thumbnail</p>
      </div>

      <div class="create-actualthing please-dont-have-flexbox-i-beg-you" id="basicsettings">
        <h1 class="" onclick="">Basic Settings</h1>
        
        <form action="changeBasicSettings" method="post" enctype="multipart/form-data">
          <input name="gameid" type="hidden" value="<?= $gameid ?>">
          <p>Name:</p>
          <input name="naem" class="create-text-input" value="<?= nx($results["name"]) ?>" required/>
          <p>Description:</p>
          <textarea rows="5" cols="50" class="create-text-input" placeholder="Describe what your game is about, put updates, put info, etc..." name="daesc" required><?= nx($results["moreinfo"]) ?></textarea>
          <p>Year:</p>
          <select name="year" required>
		  	    <option value="2015" <?php if ($results["year"] == 2015) echo "selected"; ?>>2015M</option>
                <option value="2017" <?php if ($results["year"] == 2017) echo "selected"; ?>>2017M</option>
                <option value="2019" <?php if ($results["year"] == 2019) echo "selected"; ?>>2019M</option>
				<option value="2021" <?php if ($results["year"] == 2021) echo "selected"; ?>>2021E</option>
              </select>
          <input type="submit" name="create_game" class="create-button-submit" value="Save"></input>
        </form>
        <h1 class="" onclick="">Access</h1>
        
        <form action="changeAccess" method="post" enctype="multipart/form-data">
        <input name="gameid" type="hidden" value="<?= $gameid ?>">
          <input type="radio" class="form-check-input" id="access" name="access" value="public" <?php if ($results["public"] == 1) echo "checked"; ?>>
          <label for="access">Public</label>
          <br>
          <input type="radio" class="form-check-input" id="access" name="access" value="private" <?php if ($results["public"] == 0) echo "checked"; ?>>
          <label for="access">Private</label>
          <input type="submit" name="create_game" class="create-button-submit" value="Save"></input>
        </form>
        <br>
      </div>
      <div class="create-actualthing please-dont-have-flexbox-i-beg-you hidden" id="icon">
        <h1 class="" onclick="">Game Icon</h1>
        
        <img src="/Thumbs/AssetIcon.ashx?id=<?= $results["id"] ?>" width="256">
        <hr>
        <form action="changeIcon" method="post" enctype="multipart/form-data">
          <input name="gameid" type="hidden" value="<?= $gameid ?>">
          <p class="no-margin">Select new icon:</p>
          <input type="file" id="placefile" class="placefile-uploader" name="iconfile" accept="image/png" required />
          <p class="tiny-text no-margin"><i>Icon should be square.</i></p>
          <input type="submit" name="create_game" class="create-button-submit" value="Save"></input>
        </form>
        <br>
      </div>
      <div class="create-actualthing please-dont-have-flexbox-i-beg-you hidden" id="thumbnail">
      <h1 class="" onclick="">Thumbnail</h1>
        
        <img src="/Thumbs/GameIcon.ashx?id=<?= $results["id"] ?>&width=576&height=324&version=<?= rand(0,123) ?>" width="512">
        <hr>
        <form action="changeThumb" method="post" enctype="multipart/form-data">
          <input name="gameid" type="hidden" value="<?= $gameid ?>">
          <p class="no-margin">Select new thumbnail:</p>
          <input type="file" id="placefile" class="placefile-uploader" name="thumbfile" accept="image/png" required />
          <input type="submit" name="create_game" class="create-button-submit" value="Save"></input>
        </form>
        <br>
      </div>
    </div>
  </div>
  <script src="thing.js"></script>
</body>

</html>
