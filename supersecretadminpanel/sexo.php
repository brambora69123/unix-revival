<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;
error_reporting(E_ERROR | E_WARNING | E_PARSE);



switch (true) {
  case ($RBXTICKET == null):
    header("Location: " . $baseUrl . "/");
    die();
    break;
  default:
    if ($admin < 1) {
      header("Location: " . $baseUrl . "/");
      die();
    }
    break;
}


?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Assets - Admin</title>

  <link rel="stylesheet" href="./index.css?v=<?php echo (rand(1, 50)); ?>">
  <link rel="stylesheet" href="../admindex.css?v=<?php echo (rand(1, 50)); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
</head>

<body>
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/admintopbar.php'); ?>
  <div class="main-div-container">
    <h1 class="main-title">Asset Creation</h1>
H
<h2 class="admin-subheader">Asset Ripper</h2>
<div class="admin-dashboard-div-section">


    <form action="/api/ripasset" method="post" class="please-dont-have-flexbox-i-beg-you">
      <div class="admin-button-div">
        <input type="text" placeholder="Asset" name="asset" id="ballsacks" class="admin-text-input" required>
        <input type="text" placeholder="Price" name="price" id="moneymoney" class="admin-text-input admin-text-input-end" required>
		    <input type="text" placeholder="Version(put nothing for normal)" name="version" id="version" class="admin-text-input admin-text-input-end">
      </div>
      <button type="submit" name="asset_rip" class="admin-button-submit">Rip Asset</button>
    </form>
</div>
<h2 class="admin-subheader">Upload Game</h2>
<div class="admin-dashboard-div-section">
    <div class="admin-game-uploader-div">
            
            <form action="/api/publicgameuploader" method="post" enctype="multipart/form-data" class="please-dont-have-flexbox-i-beg-you">
              <div class="please-dont-have-flexbox-i-beg-you">
                <div class="admin-game-uploader-inputs">
                        <input type="text" placeholder="Game Name" name="naem" id="ballsack" class="admin-text-input" required>
                        <input type="text" placeholder="Game Description" name="daesc" id="needletorture" class="admin-text-input admin-text-input-end" required>
                </div>
                <label for="placefile">RBXL File</label>
                <input type="file" id="placefile" class="admin-placefile-uploader" name="placefile" accept=".rbxl" required />
                <label for="iconfile">Icon Image File (512x512 recommnended)</label>
                <input type="file" id="pokethisballsack" class="admin-placefile-uploader" name="iconfile" accept="image/png" required />
                <label for="thumbnailfile">Thumbnail Image File</label>
                <input type="file" id="no" class="admin-placefile-uploader" name="thumbnailfile" accept="image/png"  />
              </div>
              <button type="submit" name="asset_rip" class="admin-button-submit">Upload Game</button>
            </form>
    </div>
</div>
<h2 class="admin-subheader">Upload Custom Item</h2>
<div class="admin-dashboard-div-section">
    <div class="admin-game-uploader-div">
            
            <form action="/api/uploadcitem" method="post" enctype="multipart/form-data" class="please-dont-have-flexbox-i-beg-you">
              <div class="please-dont-have-flexbox-i-beg-you">
                <div class="admin-game-uploader-inputs">
                        <input type="text" placeholder="Custom Item Name" name="naem" id="ballsack" class="admin-text-input" required>
                        <input type="text" placeholder="Custom Item Description" name="daesc" id="needletorture" class="admin-text-input admin-text-input-end" required>
                        <input type="text" placeholder="Custom Item Price" name="muhney" id="kickmynuts" class="admin-text-input admin-text-input-end" required>
                        <input type="text" placeholder="Custom Item Type (Hat, etc.)" name="tayp" id="kickmynuts" class="admin-text-input admin-text-input-end" required>
                </div>
                <label for="placefile">RBXM File (needs to be in xml format!)</label>
                <input type="file" id="assetfile" class="admin-placefile-uploader" name="assetfile" accept=".rbxm, .rbxmx" required />
              </div>
              <button type="submit" name="asset_rip" class="admin-button-submit">Upload Custom Item</button>
            </form>
    </div>
</div>

    </div>
  </div>

</body>

</html>


