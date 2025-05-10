<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');
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

if (isset($_POST['webhook-button'])) {
  $message = $_POST['message-payload'];
  $urlLocation = $_POST['url-location'];

  sendLog($message);
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
    <h1 class="main-title">Webhook</h1>

    <div class="admin-dashboard-div-section ">
      <form action="" method="post" class="please-dont-have-flexbox-i-beg-you" style="margin-bottom: 0;">
        <div class="admin-button-div ">
          <input type="text" placeholder="Message" name="message-payload" class="admin-text-input margin-right">
          <select name="url-location" class="admin-text-input admin-text-input ">
            <option value=https://discord.com/api/webhooks/1253029930829086812/wnQ3vDgxQchboybiXPJ4NX5nFGlKbtAUCLlYeAcL_kpAahK2inlS2shYM7uOROzWy__4>verified (info)</option>
            <option value="https://discord.com/api/webhooks/1251643398235095182/vG6VCxrKRhDfBwqUCkJ4JyFYUy5hnIZZS02R9Gef431d7hHrzVxNBdsKg5s_PqWPl8oX">admin logs</option>
          </select>
        </div>
      
        <button name="webhook-button" class="admin-button-submit">Send message</button>
      </form>
    </div>

</body>

</html>