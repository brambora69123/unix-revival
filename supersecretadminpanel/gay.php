<?php
// WARNING: this is just the frontend, no backend shit here
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
ini_set("short_open_tag", 0);
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

 

      $query = "SELECT * FROM user_reports";
      $statement = $MainDB->prepare($query);
      $statement->execute();
      
      
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $report = $row["report"];
        //$reportshit = explode(";", $report);
          echo '
            <p>Someone</p>
            <p>69</p>
		        <p>'.$row["WhenReported"].'</p>
            <p>ihateblacks</p>
            <p>420</p>
            <p>Harrasment</p>
            <p>'.$report.'</p>
    
            <p>
              <button type="submit" name="ban_player" class="admin-table-button admin-moderation-offender-ban">Ban</button>
              <!--<button type="submit" name="tempban_player" class="admin-table-button">Tempban</button>-->
              <button type="submit" name="warn_player" class="admin-table-button admin-moderation-offender-warn">Warn</button>
            </p>
        </tr>';
      } 
       