<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

error_reporting(E_ERROR | E_PARSE);

$currentpage = $_GET["page"];

if ($currentpage < 0 || !intval($currentpage)){
  $currentpage = 0;
}

$loadamount = ($currentpage + 1) * 28 ?? 28;
$loadoffset = $loadamount  -  28;
$pageamount = 28;

if (isset($_GET['order'])) {
  $order = $_GET['order'];
} else {
  $order = "iddesc";
}

if (isset($_GET['type'])) {
  $type = $_GET['type'];
} else {
  $type = "all";
}

if (isset($_GET['search'])) {
  $search = $_GET['search'];
} else {
  $search = "";
}

if ($loadamount >= 10000000) {
  header("Location: /error");
}

// default value
$itemfilter = "ORDER BY id DESC";
$sortfilter = ""; // everything

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["order"]) && isset($_GET["type"]) ) {

  switch ($_GET["order"]) {
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
      $itemfilter = "ORDER BY rsprice ASC, id ASC";
      break;
    
    case "rspricedesc":
      $itemfilter = "ORDER BY rsprice DESC, id ASC";
      break;

    case "whar":
      echo "<script>alert('bro are you stupid? it literally says to select a filter, but you didnt do that....')</script>";
      break;

    default:
      $itemfilter = "ORDER BY id DESC";
      echo "<script>alert('yo something happened maybe tell this to the developers or sumthin')</script>";
      break;
  }

  switch ($_GET["type"]) {
    case "all":
      $sortfilter = "";
      break;
    
    case "hat":
      $sortfilter = "AND itemtype = 'Hat'";
      break;
    
    case "head":
      $sortfilter = "AND itemtype = 'Head'";
    break;

    case "shirts":
      $sortfilter = "AND itemtype = 'Shirt'";
      break;
    
    case "pants":
      $sortfilter = "AND itemtype = 'Pants'";
      break;
    
    case "teeshirts":
      $sortfilter = "AND itemtype = 'TeeShirt'";
      break;

    case "gear":
      $sortfilter = "AND itemtype = 'Gear'";
      break;

    case "packagebundle":
      $sortfilter = "AND itemtype = 'PackageBundle'";
      break;


    case "whar":
      echo "<script>alert('bro are you stupid? it literally says to select an item type, but you didnt do that....')</script>";
      break;

    default:
      $sortfilter = "";
      echo "<script>alert('yo something happened maybe tell this to the developers or sumthin')</script>";
      break;
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["typeDropdown"])) {
  
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
    AND itemtype != 'Mesh'
    AND offsale IS NULL

    $sortfilter
    $itemfilter
    LIMIT :pageamount OFFSET :loadoffset
";

$CatalogFetch = $MainDB->prepare($query);
$CatalogFetch->bindParam(":pageamount", $pageamount, PDO::PARAM_INT);
$CatalogFetch->bindParam(":loadoffset", $loadoffset, PDO::PARAM_INT);


try {
    $CatalogFetch->execute();
    $FetchItems = $CatalogFetch->fetchAll();
} catch (PDOException $e) {
    echo "PDO Exception: " . $e->getMessage();
}

$lethingo = null;

//echo "loadamount: $loadamount, loadoffset: $loadoffset";



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unix - Catalog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
    
</head>

<body>

  <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>
  <?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php'); ?>

  <?php 
  if ($backgroundEnabled == 0) {
    echo '<video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>';
  } else {
    echo '<img id="video-background" src="/media/images/background.png"></img>';
  }
  
  ?>

  <div class="main-div-container">
    <h1 class="main-title">Catalog</h1>


    <div class="section-container">

      <!--<p class="section-text">Items</p>-->

      <div class="catalog-topbar">
        <div class="catalog-pagination-container">
            <a href="<?= $_SERVER["PHP_SELF"]."?page=".$currentpage - 1 . "&search=". $search . "&year=" . $year . "&order=". $order ."&type=". $type ?>" id="loader"><button <?php if($currentpage <= 0){ echo "disabled"; } ?> class="catalog-load-shit"><</button></a>
            <p class="catalog-load-p"><?php echo $currentpage+1?></p>
            <a href="<?= $_SERVER["PHP_SELF"]."?page=".$currentpage + 1 . "&search=". $search ."&order=". $order ."&type=". $type ?>" id="loader">
              <button class="catalog-load-shit">></button>
            </a>  
        </div>
        <div class="catalog-dropdown-filter" style="display:flex;">
          <form action="<?= $_SERVER["PHP_SELF"]?>" method="get" class="catalog-filter-form"> <!-- i hate this so much im tired of writing the same shit over and over again so fuck it im gonna make it a form -->
              

              <input name="search" type="text" class="catalog-search-filter" placeholder="Search">


              <select name="order" style="margin-right: 10px;">
                <option value="iddesc">Newest First</option>
                <option value="idasc">Oldest First</option>
                <!--<option value="salesdesc">Most Sales</option>-->
                <option value="rspriceasc">Least Expensive</option>
                <option value="rspricedesc">Most Expensive</option>
                
              </select>

              <select name="type">
                <option value="all">All Items</option>
                <option value="hat">Hat</option>
                <option value="head">Face</option>
                <option value="shirts">Shirts</option>
                <option value="pants">Pants</option>
                <option value="teeshirts">T-Shirts</option>
                <option value="gear">Gear</option>
                <option value="packagebundle">Package Bundle</option>
              </select>

              <input type="submit" value="Apply" style="" class="filter-apply-button">

          </form>
          <!--
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post" class="catalog-itemtype-form">
              <select name="typeDropdown">
                <option value="all">All Items</option>
                <option value="hat">Hat</option>
                <option value="shirts">Shirts</option>
                <option value="pants">Pants</option>
                <option value="teeshirts">T-Shirts</option>
                <option value="gear">Gear</option>
                <option value="packagebundle">Package Bundle</option>
              </select>
              <input type="submit" value="Apply" style="" class="filter-apply-button">
            </form>
          -->
          
        </div>
      </div>
      
      <div class="item-container">
        <?php
            switch(true){
                    case($FetchItems):
                      foreach($FetchItems as $ItemInfo){
                        if ($ItemInfo["offsale"] ) {
                          //$lethingo = "<p class=\"item-card-robux-p\">". $ItemInfo['rsprice'];
                          $lethingo = "<p class='off-sale-text'>Off Sale";
                        } else {
                          $lethingo = '<img src="/media/images/robuxicon.png" alt=":thumbs_up:" width="18px" height="18px"
                          class="game-card-likes-img" /><p class="item-card-robux-p">'. $ItemInfo["rsprice"];
                        }

                        if (strpos(strtolower($ItemInfo['name']), strtolower($search)) !== false){
                          echo '
                    

                          <div class="item-card" title="'. $ItemInfo['name'] .'">
                            <a class="item-card-link" href="http://unixfr.xyz/viewitem?id='.$ItemInfo['id'] .'">
                            <img loading="lazy" src="https://unixfr.xyz/Thumbs/asset.ashx?id='.$ItemInfo['id'] .'&x=320&y=320" alt="" height="125px" width="125px" class="item-card-img" />
                            <p class="item-card-p">
                             '. $ItemInfo['name'] .'
                            </p>
                            <div class="item-card-info-div">

                              
                              '. $lethingo .'</p>
                              
                            </div>
                          </div>
          

                          ';
                        }
                        
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