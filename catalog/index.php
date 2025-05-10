<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}
error_reporting(E_ERROR | E_PARSE);

$loadamountnomultiply = $_GET["page"];

$loadamount = ($_GET["page"] + 1) * 28 ?? 28;
$loadoffset = $loadamount -  28;

if ($loadamount >= 10000000) {
  header("Location: /error");
}

// default value
$itemfilter = "ORDER BY id DESC";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["filterDropdown"])) {
    switch ($_POST["filterDropdown"]) {
      case "iddesc":
        $itemfilter = "ORDER BY id DESC";
        break;
      
      case "idasc":
        $itemfilter = "ORDER BY id ASC";
        break;

      case "salesdesc":
        //nothing... yet
        break;
      
      case "rspriceasc":
        $itemfilter = "ORDER BY rsprice ASC";
        break;
      
      case "rspricedesc":
        $itemfilter = "ORDER BY rsprice DESC";
        break;

      case "whar":
        echo "<script>alert('bro are you stupid? it literally says to select a filter, but you didnt do that....')</script>";
        break;

      default:
        $itemfilter = "ORDER BY id DESC";
        echo "<script>alert('yo something happened maybe tell this to the developers or sumthin')</script>";
        break;
    }
}

$loadamount = intval($loadamount);
$loadoffset = intval($loadoffset);
$query = "SELECT * FROM asset 
    WHERE approved = '1' 
    AND public = '1' 
    AND itemtype != 'dev' 
    AND itemtype != 'gamepass' 
    AND itemtype != 'script' 
    AND itemtype != 'animation' 
    AND itemtype != 'place' 
    AND itemtype != 'advertisement' 
    AND itemtype != 'CoreScript' 
    AND itemtype != 'Model'
    AND itemtype != 'Decal'
    $itemfilter
    LIMIT :loadoffset, :loadamount;
";

$CatalogFetch = $MainDB->prepare($query);
$CatalogFetch->bindParam(":loadamount", $loadamount, PDO::PARAM_INT);
$CatalogFetch->bindParam(":loadoffset", $loadoffset, PDO::PARAM_INT);

try {
    $CatalogFetch->execute();
    $FetchItems = $CatalogFetch->fetchAll();
} catch (PDOException $e) {
    echo "PDO Exception: " . $e->getMessage();
}

//echo "loadamount: $loadamount, loadoffset: $loadoffset";



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - Catalog</title>
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)); ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
    
</head>

<body>

<?php 
  if ($backgroundEnabled == 0) {
    echo '<video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>';
  } else {
    echo '';
  }
  
  ?>

  <div class="main-div-container">
    <h1 class="main-title">Catalog</h1>

 <a href='http://unixfr.xyz/games/start?placeid=2'>
              <button class="main-game-button">
                  Play
              </button>
          </a>
    <div class="section-container">

      <!--<p class="section-text">Items</p>-->

      <div class="catalog-topbar">
      <div class="catalog-pagination-container">
          <a href="<?= $_SERVER["PHP_SELF"]."?page=".$loadamountnomultiply - 1 ?>" id="loader"><button class="catalog-load-shit"><</button></a>
          <p class="catalog-load-p"><?php echo $_GET['page']+1?></p>
          <a href="<?= $_SERVER["PHP_SELF"]."?page=".$loadamountnomultiply + 1 ?>" id="loader">
            <button class="catalog-load-shit">></button>
          </a>  
      </div>
      <div class="catalog-dropdown-filter" style="display:flex;">
          <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post" class="catalog-filter-form"> <!-- i hate this so much im tired of writing the same shit over and over again so fuck it im gonna make it a form -->
              <select name="filterDropdown">
                <option value="whar">Select a Filter</option>
                <option value="iddesc">Newest First</option>
                <option value="idasc">Oldest First</option>
                <!--<option value="salesdesc">Most Sales</option>-->
                <option value="rspriceasc">Least Expensive</option>
                <option value="rspricedesc">Most Expensive</option>
              </select>
              <input type="submit" value="Apply" style="" class="filter-apply-button">
          </form>
      </div>
      
      
      </div>
      
      <div class="item-container">
        <?php
            switch(true){
                    case($FetchItems):
                      foreach($FetchItems as $ItemInfo){
                    
                        echo '
                    

                          <div class="item-card" title="'. $ItemInfo['name'] .'">
                            <a class="item-card-link" href="http://unixfr.xyz/viewitem?id='.$ItemInfo['id'] .'">
                            <img loading="lazy" src="https://unixfr.xyz/Thumbs/asset.ashx?id='.$ItemInfo['id'] .'&x=320&y=320" alt="" height="125px" width="125px" class="item-card-img" />
                            <p class="item-card-p">
                             '. $ItemInfo['name'] .'
                            </p>
                            <div class="item-card-info-div">

                              <img src="/media/images/robuxicon.png" alt=":thumbs_up:" width="18px" height="18px"
                                class="game-card-likes-img" />
                              <p class="item-card-robux-p">'. $ItemInfo['rsprice'] .'</p>
                              
                            </div>
                          </div>
          

                        ';
                      }
                      break;
                      default:
                      echo '<p class="no-players-found-text">No items found.</p>';
                      break;
                      }
          ?>
          
          </div>
          
          </a>
        </div>
      </div>

      
    </div>
  </div>
</body>

</html>