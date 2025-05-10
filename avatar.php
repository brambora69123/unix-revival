<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');

switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}
$filter = ($_GET['filter'] ?? null);
switch ($filter) {
  case "Hats":
    $WardrobeSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id  AND itemtype = 'Hat' ORDER BY id DESC LIMIT 12");
    break;
  case "Faces":
    $WardrobeSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id  AND itemtype = 'Face' ORDER BY id DESC LIMIT 12");
    break;
  case "Shirts":
    $WardrobeSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id  AND itemtype = 'Shirt' ORDER BY id DESC LIMIT 12");
    break;
  case "TeaShirt":
    $WardrobeSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id  AND itemtype = 'TShirt' ORDER BY id DESC LIMIT 12");
    break;
  case "Pants":
    $WardrobeSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id  AND itemtype = 'Pants' ORDER BY id DESC LIMIT 12");
    break;
  case "Gear":
    $WardrobeSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id  AND itemtype = 'Gear' ORDER BY id DESC LIMIT 12");
    break;
  case "BodyParts":
    $WardrobeSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id AND (itemtype = 'RightLeg' OR itemtype = 'LeftLeg' OR itemtype = 'LeftArm' OR itemtype = 'RightArm' OR itemtype = 'Head' OR itemtype = 'Torso' OR itemtype = 'BodyPart') ORDER BY id DESC LIMIT 24");
    break;
  case "Wearing":
    $WardrobeSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id AND wearing = 1 ORDER BY id DESC LIMIT 12");
    break;
  default:
    $WardrobeSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id AND itemtype = 'Hat' AND itemtype != 'model' AND itemtype != 'advertisement' AND itemtype != 'decal' AND itemtype != 'audio' ORDER BY id DESC LIMIT 12");
    break;
}
$WardrobeSrh->execute([":id" => $id]);
$ReWDS = $WardrobeSrh->fetchAll();

$WearingSrh = $MainDB->prepare("SELECT * FROM bought WHERE boughtby = :id AND wearing = '1' ORDER BY id DESC LIMIT 24");
$WearingSrh->execute([":id" => $id]);
$ReWRS = $WearingSrh->fetchAll();
// stolen from stackoverflow (https://stackoverflow.com/questions/32962624/convert-rgb-to-hex-color-values-in-php)
function fromRGB($R, $G, $B)
{

  $R = dechex($R);
  if (strlen($R) < 2)
    $R = '0' . $R;

  $G = dechex($G);
  if (strlen($G) < 2)
    $G = '0' . $G;

  $B = dechex($B);
  if (strlen($B) < 2)
    $B = '0' . $B;

  return '#' . $R . $G . $B;
}

$brickColorToHexArray = array(
  1    =>  fromRGB(242, 243, 243),
  2    =>  fromRGB(161, 165, 162),
  3    =>  fromRGB(249, 233, 153),
  5    =>  fromRGB(215, 197, 154),
  6    =>  fromRGB(194, 218, 184),
  9    =>  fromRGB(232, 186, 200),
  11   =>  fromRGB(128, 187, 219),
  12   =>  fromRGB(203, 132, 66),
  18   =>  fromRGB(204, 142, 105),
  21   =>  fromRGB(196, 40, 28),
  22   =>  fromRGB(196, 112, 160),
  23   =>  fromRGB(13, 105, 172),
  24   =>  fromRGB(245, 205, 48),
  25   =>  fromRGB(98, 71, 50),
  26   =>  fromRGB(27, 42, 53),
  27   =>  fromRGB(109, 110, 108),
  28   =>  fromRGB(40, 127, 71),
  29   =>  fromRGB(161, 196, 140),
  36   =>  fromRGB(243, 207, 155),
  37   =>  fromRGB(75, 151, 75),
  38   =>  fromRGB(160, 95, 53),
  39   =>  fromRGB(193, 202, 222),
  40   =>  fromRGB(236, 236, 236),
  41   =>  fromRGB(205, 84, 75),
  42   =>  fromRGB(193, 223, 240),
  43   =>  fromRGB(123, 182, 232),
  44   =>  fromRGB(247, 241, 141),
  45   =>  fromRGB(180, 210, 228),
  47   =>  fromRGB(217, 133, 108),
  48   =>  fromRGB(132, 182, 141),
  49   =>  fromRGB(248, 241, 132),
  50   =>  fromRGB(236, 232, 222),
  119  =>  fromRGB(164, 189, 71),
  1019 =>  fromRGB(0, 255, 255),
  127  =>  fromRGB(220, 188, 129),
  134  =>  fromRGB(216, 221, 86)
);

$bodyColorStmt = $MainDB->prepare("SELECT * FROM body_colours WHERE uid = :u");
$bodyColorStmt->bindParam(":u", $id, PDO::PARAM_INT);
$bodyColorStmt->execute();
if ($bodyColorStmt->rowCount() > 0) {
  $bodyColors = $bodyColorStmt->fetch(PDO::FETCH_ASSOC);
  $headColor = $brickColorToHexArray[$bodyColors["h"]];
  $torsoColor = $brickColorToHexArray[$bodyColors["t"]];
  $leftLegColor = $brickColorToHexArray[$bodyColors["ll"]];
  $rightLegColor = $brickColorToHexArray[$bodyColors["rl"]];
  $leftArmColor = $brickColorToHexArray[$bodyColors["la"]];
  $rightArmColor = $brickColorToHexArray[$bodyColors["ra"]];
} else {
  $numba = 5;
  $headColor = 5;
  $torsoColor = 5;
  $leftLegColor = 5;
  $rightLegColor = 5;
  $leftArmColor = 5;
  $rightArmColor = 5;
  $insertStmt = $MainDB->prepare("INSERT INTO body_colours (uid,h, t, ll, rl, la, ra) VALUES (:u,:headColor, :torsoColor, :leftLegColor, :rightLegColor, :leftArmColor, :rightArmColor)");
  $insertStmt->bindParam(":u", $id, PDO::PARAM_STR);
  $insertStmt->bindParam(":headColor", $numba, PDO::PARAM_INT);
  $insertStmt->bindParam(":torsoColor", $numba, PDO::PARAM_INT);
  $insertStmt->bindParam(":leftLegColor", $numba, PDO::PARAM_INT);
  $insertStmt->bindParam(":rightLegColor", $numba, PDO::PARAM_INT);
  $insertStmt->bindParam(":leftArmColor", $numba, PDO::PARAM_INT);
  $insertStmt->bindParam(":rightArmColor", $numba, PDO::PARAM_INT);

  $insertStmt->execute();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)); ?>">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - Avatar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>



<body>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php'); ?>

  <div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <span class="close" id="close">&times;</span>
      <div class="color-button-container">
        <?php
        foreach ($brickColorToHexArray as $colorCode => $hexColor) {
          echo '<button class="color-button" style="background-color: ' . $hexColor . ';" onclick="changeBodyColor(\'' . $colorCode . '\')"></button>';
        }
        ?>
      </div>
    </div>
  </div>





  <?php
  if ($backgroundEnabled == 0) {
    echo '<video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>';
  } else {
    echo '<img id="video-background" src="/media/images/background.png"></img>';
  }


  ?>

  <div class="main-div-container">
    <h1 class="main-title">Avatar</h1>

    <div class="info-div-container">
      <div class="info-left-div-container">
        <div class="avatar-image-div">
          <img class="avatar-image" src="https://unixfr.xyz/Thumbs/Avatar.ashx?userId=<?php echo $id; ?>" alt="">

        </div>
        <div class="avatar-type-div">
          <a href="http://unixfr.xyz/api/changeavatar?type=1" id="r6-button" class="avatar-type-button">R6</a>
          <a href="http://unixfr.xyz/api/changeavatar?type=2" id="r15-button" class="avatar-type-button">R15</a>
          <?php
          if (rand(1, 340) == 1) {
            echo '<button id="r34-button" class="avatar-type-button">R34</button>';
          }
          ?>

        </div>
      </div>
      <div class="info-left-div-container body-color-container">
        <div class="avatar-image-div">
          <div class="body-color-blocks">

            <div class="body-color-block" onclick="" id="body-color-block-t" style="background-color: <?= $torsoColor ?>; position: absolute; width: 110px; height: 100px; margin-top: 150px; margin-left: 75px; top: -84px;"></div>

            <div class="body-color-block" onclick="" id="body-color-block-la" style="background-color: <?= $leftArmColor ?>; position: absolute; width: 100px; height: 50px; margin-top: 150px; margin-left: -7px; top: -59px; transform: rotate(90deg);"></div>

            <div class="body-color-block" onclick="" id="body-color-block-ll" style="background-color: <?= $leftLegColor ?>; position: absolute; width: 100px; height: 50px; margin-top: 150px; margin-left: 50px; top: 48px; transform: rotate(90deg);"></div>

            <div class="body-color-block" onclick="" id="body-color-block-rl" style="background-color: <?= $rightLegColor ?>; position: absolute; width: 100px; height: 50px; margin-top: 150px; margin-left: 110px; top: 48px; transform: rotate(90deg);"></div>

            <div class="body-color-block" onclick="" id="body-color-block-ra" style="background-color: <?= $rightArmColor ?>; position: absolute; width: 100px; height: 50px; margin-top: 150px; margin-left: 167px; top: -59px; transform: rotate(90deg);"></div>

            <div class="body-color-block" onclick="" id="body-color-block-h" style="background-color: <?= $headColor ?>; position: absolute; width: 60px; height: 60px; margin-top: 142px; margin-left: 100px; top: -143px;"></div>



            <script>
              document.getElementById("body-color-block-h").style.backgroundColor = '<?= $headColor ?>';
              document.getElementById("body-color-block-t").style.backgroundColor = '<?= $torsoColor ?>';
              document.getElementById("body-color-block-ll").style.backgroundColor = '<?= $leftLegColor ?>';
              document.getElementById("body-color-block-rl").style.backgroundColor = '<?= $rightLegColor ?>';
              document.getElementById("body-color-block-ra").style.backgroundColor = '<?= $rightArmColor ?>';
              document.getElementById("body-color-block-la").style.backgroundColor = '<?= $leftArmColor ?>';
            </script>
            <script>
              var modal = document.getElementById("myModal");

              var btn = document.getElementById("body-color-block-h");
              var btn2 = document.getElementById("body-color-block-t");
              var btn3 = document.getElementById("body-color-block-la");
              var btn4 = document.getElementById("body-color-block-ll");
              var btn5 = document.getElementById("body-color-block-rl");
              var btn6 = document.getElementById("body-color-block-ra");
              var button1 = "";

              var button2 = document.getElementById("close");

              btn.onclick = function() {
                modal.style.display = "block";
                button1 = "h";
              }
              btn2.onclick = function() {
                modal.style.display = "block";
                button1 = "t";
              }
              btn3.onclick = function() {
                modal.style.display = "block";
                button1 = "la";
              }
              btn4.onclick = function() {
                modal.style.display = "block";
                button1 = "rl";
              }
              btn5.onclick = function() {
                modal.style.display = "block";
                button1 = "ll";
              }
              btn6.onclick = function() {
                modal.style.display = "block";
                button1 = "ra";
              }

              button2.onclick = function() {
                modal.style.display = "none";
              }

              window.onclick = function(event) {
                if (event.target == modal) {
                  modal.style.display = "none";
                }
              }

              function changeBodyColor(colorCode) {
                if (button) {
                  const apiUrl = 'https://unixfr.xyz/api/changeBodyColor?part=' + button1 + '&cid=' + colorCode;

                  fetch(apiUrl)
                    .then(response => {
                      if (!response.ok) {
                        throw new Error('Network response was not ok');
                      }
                      window.location.href = apiUrl;
                    })
                    .catch(error => console.error('Error:', error));

                  modal.style.display = "none";
                } else {
                  console.error('No button clicked');
                }
              }
            </script>

            


          </div>

        </div>
        <div class="avatar-type-div">


        </div>
      </div>

      <div class="info-right-div-container">
        <div class="avatar-filters-container">

          <button class="avatar-filters-button">
            Clothes
            <img src="./media/images/dropdownicon.png" alt="" width="10px">
            <div class="avatar-filters-dropdown-content">
              <a href="https://unixfr.xyz/avatar?filter=Shirts" class="avatar-filters-dropdown-button no-margin-top AttireCategorySelector" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_AttireCategoryRepeater_ctl00_AttireCategorySelector">
                Shirts
              </a>
              <a href="https://unixfr.xyz/avatar?filter=TeaShirt" class="avatar-filters-dropdown-button AttireCategorySelector" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_AttireCategoryRepeater_ctl01_AttireCategorySelector">
                T-Shirts
              </a>
              <a href="https://unixfr.xyz/avatar?filter=Pants" class="avatar-filters-dropdown-button AttireCategorySelector" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_AttireCategoryRepeater_ctl01_AttireCategorySelector">
                Pants
              </a>
            </div>
          </button>

          <button class="avatar-filters-button">
            Accesories
            <img src="./media/images/dropdownicon.png" alt="" width="10px">
            <div class="avatar-filters-dropdown-content">
              <a href="https://unixfr.xyz/avatar?filter=Hats" class="avatar-filters-dropdown-button no-margin-top AttireCategorySelector" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_AttireCategoryRepeater_ctl01_AttireCategorySelector">
                Hats
              </a>
              <a href="https://unixfr.xyz/avatar?filter=Faces" class="avatar-filters-dropdown-button AttireCategorySelector" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_AttireCategoryRepeater_ctl01_AttireCategorySelector">
                Faces
              </a>
              <a href="https://unixfr.xyz/avatar?filter=Gear" class="avatar-filters-dropdown-button AttireCategorySelector" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_AttireCategoryRepeater_ctl01_AttireCategorySelector">
                Gears
              </a>
            </div>
          </button>

          <button class="avatar-filters-button">
            Other
            <img src="./media/images/dropdownicon.png" alt="" width="10px">
            <div class="avatar-filters-dropdown-content">
              <a href="https://unixfr.xyz/avatar?filter=BodyParts" class="avatar-filters-dropdown-button no-margin-top AttireCategorySelector" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_AttireCategoryRepeater_ctl01_AttireCategorySelector">
                Body Parts
              </a>
              <a href="https://unixfr.xyz/avatar?filter=Wearing" class="avatar-filters-dropdown-button AttireCategorySelector" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_AttireCategoryRepeater_ctl01_AttireCategorySelector">
                Wearing
              </a>
              <a href="https://unixfr.xyz/avatar?filter=Gear" class="avatar-filters-dropdown-button AttireCategorySelector" id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_AttireCategoryRepeater_ctl01_AttireCategorySelector">
                Gears
              </a>
            </div>
          </button>





        </div>

        <div class="item-container">
          <?php
          switch (true) {
            case ($ReWDS):
              $i = 0;
              foreach ($ReWDS as $AssetInfo) {
                $i++;
                switch (true) {
                  case ($i == 6):
                    echo "</br><tr>";
                    break;
                }
                $AssetId = $AssetInfo["boughtid"];
                $AssetName = $AssetInfo["boughtname"];
                $AssetWearing = $AssetInfo["wearing"];

                $buttonText = ($AssetWearing == 1) ? 'Remove' : 'Wear';
                $buttonLink = ($AssetWearing == 1) ? 'removeitem.php' : 'wearitem.php';

                echo '        <div class="item-card">
            <a class="item-card-link" href="http://unixfr.xyz/viewitem?id=' . $AssetId . '">
              <img loading="lazy" src="' . $baseUrl . '/Thumbs/Asset.ashx?id=' . $AssetId . '" alt="" height="125px" width="125px" class="item-card-img" />
              <p class="item-card-p">
                ' . $AssetName . '
              </p>
              <div class="item-card-info-div">
                <a href="https://unixfr.xyz/api/' . $buttonLink . '?id=' . $AssetId . '" class="avatar-wear-button AttireCategorySelector"  id="ctl00_ctl00_cphRoblox_cphMyRobloxContent_AttireCategoryRepeater_ctl00_AttireCategorySelector">
                  ' . $buttonText . '
                </a>
              </div>
			  </a>
			      </div>';
              }
              break;
            default:
              echo '<p align="center">No items found.</p>';
              break;
          }
          ?>





          </a>
        </div>

      </div>
    </div>
  </div>

  </div>


  <script src="./bodyColorCustomization.js?v=<?php echo (rand(1, 50)); ?>"></script>


</body>