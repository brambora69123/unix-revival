<?php
die("i decided to not do this because reports are stupid<br><p onclick='history.back()' style='cursor:pointer;color:blue;text-decoration:underline;'>press me to go back</p>");
// WARNING: this is just the frontend, no backend shit here
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;

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
  <title>Index - Admin</title>

  <link rel="stylesheet" href="./index.css?v=<?php echo (rand(1, 50)); ?>">
  <link rel="stylesheet" href="../admindex.css?v=<?php echo (rand(1, 50)); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
</head>

<body>
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/admintopbar.php'); ?>
  <div class="main-div-container">
    <h1 class="main-title">Reports</h1>
    <h2 class="admin-subheader">Seach Reports</h2>
    <div class="admin-dashboard-div-section">
      <div class="admin-button-div">
        <input type="number" placeholder="Search the contents of a report (ex. “slur”)" id="search-reports" class="admin-text-input">
      </div>
      <form action="" method="post">
        <button name="search-reports-button" class="admin-button-submit">Search Reports</button>
      </form>
    </div>

    <h2 class="admin-subheader">All Reports</h2>
    <div class="admin-dashboard-div-section">
      <table class="admin-table">
	  <tr class="admin-tr-cell">
          <th class="admin-th-cell-start">Reporter Name</th>
          <th class="admin-th-cell">Reporter ID</th>
		  <th class="admin-th-cell">Creation Date</th>
          <th class="admin-th-cell">Offender Name</th>
          <th class="admin-th-cell">Offender ID</th>
          <th class="admin-th-cell">Type of Abuse</th>
          <th class="admin-th-cell">Report Description</th>
          <th class="admin-th-cell-end">Action</th>
        </tr>
      
 
      <?php
      $query = "SELECT * FROM user_reports";
      $statement = $MainDB->prepare($query);
      $statement->execute();
      
      
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $report = $row["report"];
         
          echo '<tr>
            <td>Someone</td>
            <td>69</td>
		        <td>'.$row["WhenReported"].'</td>
            <td>ihateblacks</td>
            <td>420</td>
            <td>Harrasment</td>
            <td>'.$report.'</td>
    
            <td>
              <button type="submit" name="ban_player" class="admin-table-button admin-moderation-offender-ban">Ban</button>
              <!--<button type="submit" name="tempban_player" class="admin-table-button">Tempban</button>-->
              <button type="submit" name="warn_player" class="admin-table-button admin-moderation-offender-warn">Warn</button>
            </td>
        </tr>';
      } 
        ?>
		
		
      </table>
    </div>
  </div>

</body>

</html>