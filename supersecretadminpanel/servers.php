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



if (isset($_POST['jobid']) && isset($_POST['close_game'])) {
  $jobid = $_POST['jobid'];
  $jobid = htmlspecialchars($jobid, ENT_QUOTES, 'UTF-8');

  $reset_url = "http://unixfr.xyz/reset?jobid=" . urlencode($jobid);
  $reset_response = @file_get_contents($reset_url);

  // Determine the correct gameclose URL based on the year
  $currentYear = $_POST['year'];
  switch ($currentYear) {
    case 2017:
      $gamecloseEndpoint = "gameclose";
      break;
    case 2019:
      $gamecloseEndpoint = "gameclose2019";
      break;
    case 2021:
      $gamecloseEndpoint = "gameclose2021";
      break;
	case 2015:
      $gamecloseEndpoint = "gameclose2015";
      break;
    default:
      $gamecloseEndpoint = "gameclose"; // Default to gameclose if the year does not match
  }

  $gameclose_url = "http://unixfr.xyz/soapy/unix/Roblox/$gamecloseEndpoint?job=" . urlencode($jobid) . "&acckey=" . urlencode($AccessKey);
  $gameclose_response = @file_get_contents($gameclose_url);

  if ($gameclose_response === false) {
    // Handle error (optional)
  }
  sendLog("A server with the jobid of ".$jobid." was closed by ".$name.".", "jobclosed");
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
    <h1 class="main-title">Servers</h1>

    <h2 class="admin-subheader">All Servers</h2>
    <div class="admin-dashboard-div-section">
      <table class="admin-table">
        <tr class="admin-tr-cell">
          <th class="admin-th-cell-start">Server</th>
          <th class="admin-th-cell">Players</th>
          <th class="admin-th-cell-end">Action</th>
        </tr>

        <?php

        $ActionFetch = $MainDB->prepare("
            SELECT *
            FROM open_servers
            ORDER BY playerCount DESC
        ");

        $ActionFetch->execute();
        $ActionRows = $ActionFetch->fetchAll();

        foreach ($ActionRows as $GameInfo) {
          $stmt = $MainDB->prepare("SELECT * FROM asset WHERE id = :gameID");
          $stmt->bindParam(':gameID', $GameInfo['gameID'], PDO::PARAM_INT);
          $stmt->execute();
          $resulto = $stmt->fetchAll(PDO::FETCH_ASSOC);

          // Check if the query returned any results
          if (count($resulto) > 0) {
            $playersOnline = intval($GameInfo['playerCount']);
            echo '<tr class="admin-tr-cell">
        <td class="admin-td-cell">' . $resulto[0]['name'] . '</td>
        <td class="admin-td-cell">' . $GameInfo['playerCount'] . '</td>
        <td class="admin-td-cell">
          <form action = "" method = "post">
		  <input type="hidden" name="jobid" value="' . $GameInfo['jobid'] . '">
		  <input type="hidden" name="year" value="' . $resulto[0]['year'] . '">
            <button type="submit" name="close_game" class="admin-table-button">Shutdown</button>
          </form>
          
        </td>
      </tr>';
          } else {

          }
        }

        ?>
      </table>
    </div>
  </div>
</body>

</html>