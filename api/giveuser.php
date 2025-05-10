<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/api/webhookstuff.php');
$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;
//error_reporting(E_ERROR | E_WARNING | E_PARSE);




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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    if (isset($_POST['usertogive']) && isset($_POST['value']) && isset($_POST["type"])) {

      $user = $_POST['usertogive'];
      $value = $_POST['value'];
      $type = $_POST["type"];

      $checkUser = $MainDB->prepare("SELECT * FROM users WHERE id = :id");
      $checkUser->bindParam(":id", $user, PDO::PARAM_INT);
      $checkUser->execute();
      $User = $checkUser->fetch(PDO::FETCH_ASSOC);

      if ($User){

        switch ($type){

          case "give-robux":

            if (is_numeric($value)){

              $value = intval($value);

              $amountToGive = $User["robux"] + $value;

              $giveRobux = $MainDB->prepare("UPDATE users SET robux = :amount WHERE id = :id");
              $giveRobux->bindParam(":amount", $amountToGive, PDO::PARAM_INT);
              $giveRobux->bindParam(":id", $user, PDO::PARAM_INT);
              $giveRobux->execute();
              //$robux = $giveRobux->fetch(PDO::FETCH_ASSOC);

            } else{
              echo "NOT INTEGER RAHHHHHHHHHH";
            }

            break;

          case "set-robux":

            if (is_numeric($value)){

              $value = intval($value);

              $amountToGive = $value;

              $giveRobux = $MainDB->prepare("UPDATE users SET robux = :amount WHERE id = :id");
              $giveRobux->bindParam(":amount", $amountToGive, PDO::PARAM_INT);
              $giveRobux->bindParam(":id", $user, PDO::PARAM_INT);
              $giveRobux->execute();
              //$robux = $giveRobux->fetch(PDO::FETCH_ASSOC);
              
            } else{
              echo "NOT INTEGER RAHHHHHHHHHH";
            }

            break;
          
          case "give-item":

            if (is_numeric($value)){

              $value = intval($value);

              $assetsFetch = $MainDB->prepare("SELECT * FROM asset WHERE id = :id");
              $assetsFetch->bindParam(":id", $value, PDO::PARAM_INT);
              $assetsFetch->execute();
              $asset = $assetsFetch->fetch(PDO::FETCH_ASSOC);

              if ($asset){

                $assetId = $asset["id"];
                $assetName = $asset["name"];
                $assetType = $asset["itemtype"];
                $assetCreator = $asset["creatorid"];

                $boughtFetch = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :uid AND boughtid = :id");
                $boughtFetch->bindParam(":uid", $user, PDO::PARAM_INT);
                $boughtFetch->bindParam(":id", $assetId, PDO::PARAM_INT);
                $boughtFetch->execute();
                $bought = $boughtFetch->fetch(PDO::FETCH_ASSOC);

                if (!$bought){
                  
                  $giveItem = $MainDB->prepare("INSERT INTO bought (boughtby, boughtid, boughtname, itemtype, boughtfrom)
                                                VALUES (:buyer, :itemid, :itemname, :itemtype, :creator)");
                  $giveItem->bindParam(":buyer", $user, PDO::PARAM_INT);
                  $giveItem->bindParam(":itemid", $assetId, PDO::PARAM_INT);
                  $giveItem->bindParam(":itemname", $assetName, PDO::PARAM_STR);
                  $giveItem->bindParam(":itemtype", $assetType, PDO::PARAM_STR);
                  $giveItem->bindParam(":creator", $assetCreator, PDO::PARAM_INT);
                  $giveItem->execute();

                  //$bought = $boughtFetch->fetch(PDO::FETCH_ASSOC);
                }else{
                  $removeItem = $MainDB->prepare("DELETE FROM bought WHERE boughtid = :id AND boughtby = :uid");
                  $removeItem->bindParam(":id", $value, PDO::PARAM_INT);
                  $removeItem->bindParam(":uid", $user, PDO::PARAM_INT);
                  $removeItem->execute();
                }

              } else{
                echo "doesn't exist";
              }
              //$robux = $giveRobux->fetch(PDO::FETCH_ASSOC);
              
            } else{
              echo "NOT INTEGER RAHHHHHHHHHH";
            }
            
            break;

          
          case "give-bc":

            if (is_numeric($value)){
              $setMembership = $MainDB->prepare("UPDATE users SET membership = :value WHERE id = :id");
              $setMembership->bindParam(":id", $user, PDO::PARAM_INT);

              if (intval($value) == 0 || intval($value) == 1 || intval($value) == 2 || intval($value) == 3){
                $setMembership->bindValue(":value", intval($value), PDO::PARAM_INT);
                $setMembership->execute();
              } else{
                die("invalid values, 0 for no membership, 1 for bc, 2 for tbc and 3 for obc");
              }
            } else{
              echo "NOT INTEGER RAHHHHHHHHHH";
            }
            
            break;

          case "add-friend":

            if (is_numeric($value)){

              $value = intval($value);

              $getFriends = $MainDB->prepare("SELECT * FROM friends WHERE user1 = :id OR user2 = :id2
                                              INTERSECT
                                              SELECT * FROM friends WHERE user1 = :uid OR user2 = :uid2
                                            ");
              $getFriends->bindParam(":id", $value, PDO::PARAM_INT);
              $getFriends->bindParam(":id2", $value, PDO::PARAM_INT);
              $getFriends->bindParam(":uid", $user, PDO::PARAM_INT);
              $getFriends->bindParam(":uid2", $user, PDO::PARAM_INT);
              $getFriends->execute();
              $friends = $getFriends->fetch(PDO::FETCH_ASSOC);

              if (!$friends){

                $addFriends = $MainDB->prepare("INSERT INTO friends (user1, user2) VALUES (:id, :id2)");
                $addFriends->bindParam(":id", $user, PDO::PARAM_INT);
                $addFriends->bindParam(":id2", $value, PDO::PARAM_INT);
                $addFriends->execute();
                
                $removeRequest = $MainDB->prepare("DELETE FROM friend_requests WHERE user1 = :id AND user2 = :id2");
                $removeRequest->bindParam(":id", $user, PDO::PARAM_INT);
                $removeRequest->bindParam(":id2", $value, PDO::PARAM_INT);
                $removeRequest->execute();

                $removeRequestNotification = $MainDB->prepare("DELETE FROM notification WHERE user1 = :id AND user2 = :id2 AND type = 'friend'");
                $removeRequestNotification->bindParam(":id", $user, PDO::PARAM_INT);
                $removeRequestNotification->bindParam(":id2", $value, PDO::PARAM_INT);
                $removeRequestNotification->execute();

              }else{

                $user1 = $friends["user1"];
                $user2 = $friends["user2"];

                $removeFriends = $MainDB->prepare("DELETE FROM friends WHERE user1 = :id AND user2 = :id2");
                $removeFriends->bindParam(":id", $user1, PDO::PARAM_INT);
                $removeFriends->bindParam(":id2", $user2, PDO::PARAM_INT);
                $removeFriends->execute();
                
              }
            } else{
              echo "NOT INTEGER RAHHHHHHHHHH";
            }

            break;
            

        }

      }else{
        die("user doesn't exist");
      }

    }else{
        die("not enough values specified");
    }
}

?>