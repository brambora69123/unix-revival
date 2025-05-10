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
    <h1 class="main-title">Notifications</h1>

    <div class="admin-dashboard-div-section">
      <form action="/api/notificationmessenger" method="post" class="please-dont-have-flexbox-i-beg-you">
        <div class="admin-button-div">
          <input type="text" placeholder="Message" name="message" id="message" class="admin-text-input" style="margin-right: 10px;" required>
          <input type="int" placeholder="User ID (0 for all)" name="userid" id="userid" class="admin-text-input" style="margin-right: 10px;" required>
          <input type="text" placeholder="Game ID (if event picked)" name="gameid" id="id" class="admin-text-input" style="margin-right: 10px;" required disabled>
          <select name="type" id="" class="admin-select-input" required>
            <option value="info">Info</option>
            <option value="warn">Warning</option>
            <option value="event">Event</option>
          </select>
        </div>
        <button type="submit" name="asset_rip" class="admin-button-submit">Add Notification</button>
      </form>
    </div>

    <!--<h2 class="main-title" style="color: red;">Send Notification to All (DANGEROUS)</h2>
    <div class="admin-dashboard-div-section">
      <form action="/api/sendnotificationtoall" method="post" class="please-dont-have-flexbox-i-beg-you">
        <div class="admin-button-div">
          <input type="text" placeholder="Message" name="message" id="message" class="admin-text-input" style="margin-right: 10px;" required>
          <select name="type" id="" class="admin-select-input" required>
            <option value="info">Info</option>
            <option value="warn">Warning</option>
          </select>
        </div>
        <button type="submit" name="asset_rip" class="admin-button-submit">Send</button>
      </form>
    </div>-->

  </div>

</body>

</html>