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

if ($currentpage < 0 || !intval($currentpage)) {
    $currentpage = 0;
}

$loadamount = ($currentpage + 1) * 24 ?? 24;
$loadoffset = $loadamount  -  24;
$pageamount = 24;

if (isset($_GET['order'])) {
    $order = $_GET['order'];
} else {
    $order = "playeron";
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

if (isset($_GET['year'])) {
    $year = $_GET['year'];
} else {
    $year = "all";
}

if ($loadamount >= 10000000) {
    header("Location: /error");
}

// default value
$itemfilter = "ORDER BY playersOnline DESC, lastGameJoin DESC";
$sortfilter = ""; // everything
$yearfilter = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["order"]) && isset($_GET["type"])) {

    switch ($_GET["order"]) {

        case "playeron":
            $itemfilter = "ORDER BY playersOnline DESC, lastGameJoin DESC";
            break;

        case "iddesc":
            $itemfilter = "ORDER BY id DESC";
            break;

        case "idasc":
            $itemfilter = "ORDER BY id ASC";
            break;

        case "likeasc":
            $itemfilter = "ORDER BY liked DESC, id DESC";
            break;

        case "favasc":
            $itemfilter = "ORDER BY favorited DESC, id DESC";
            break;

        case "visasc":
            $itemfilter = "ORDER BY visits DESC, id DESC";
            break;

        case "whar":
            echo "<script>alert('bro are you stupid? it literally says to select a filter, but you didnt do that....')</script>";
            break;

        default:
            $itemfilter = "ORDER BY playersOnline DESC, lastGameJoin DESC";
            echo "<script>alert('yo something happened maybe tell this to the developers or sumthin')</script>";
            break;
    }

    switch ($_GET["type"]) {
        case "all":
            $sortfilter = "";
            break;

        case "whar":
            echo "<script>alert('bro are you stupid? it literally says to select an item type, but you didnt do that....')</script>";
            break;

        default:
            $sortfilter = "";
            echo "<script>alert('yo something happened maybe tell this to the developers or sumthin')</script>";
            break;
    }

    switch ($_GET["year"]) {
        case "all":
            $sortfilter = "";
            break;

        case "21":
            $yearfilter = "AND a.year = '2021'";
            break;

        case "19":
            $yearfilter = "AND a.year = '2019'";
            break;


        case "17":
            $yearfilter = "AND a.year = '2017'";
            break;

        case "15":
            $yearfilter = "AND a.year = '2015'";
            break;

        default:
            $sortfilter = "";
            echo "<script>alert('yo something happened maybe tell this to the developers or sumthin')</script>";
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)); ?>">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Unix - Games</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>

<body>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/topbar.php'); ?>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php'); ?>

    <?php
    if ($backgroundEnabled == 0) {
        echo '<video id="video-background-no-index" src="/media/videos/background.mp4" autoplay muted loop></video>';
    } else {
        echo '<img id="video-background" src="/media/images/background.png"></img>';
    }
    ?>


    <div class="main-div-container">
        <h1 class="main-title">Games</h1>

        <div class="section-container">

            <div class="catalog-topbar">
                <div class="catalog-pagination-container">
                    <a href="<?= $_SERVER["PHP_SELF"] . "?page=" . $currentpage - 1 . "&search=" . $search . "&year=" . $year . "&order=" . $order . "&type=" . $type ?>" id="loader">
                        <button <?= $currentpage == 0 ? 'disabled' : '' ?> class="catalog-load-shit">
                            &lt;
                        </button>
                    </a>
                    <p class="catalog-load-p"><?php echo $currentpage + 1 ?></p>
                    <a href="<?= $_SERVER["PHP_SELF"] . "?page=" . $currentpage + 1 . "&search=" . $search . "&year=" . $year . "&order=" . $order . "&type=" . $type ?>" id="loader">
                        <button class="catalog-load-shit">&gt;</button>
                    </a>
                </div>
                <div class="catalog-dropdown-filter" style="display:flex;">
                    <form action="<?= $_SERVER["PHP_SELF"] ?>" method="get" class="catalog-filter-form"> <!-- i hate this so much im tired of writing the same shit over and over again so fuck it im gonna make it a form -->

                        <input name="search" type="text" class="catalog-search-filter" placeholder="Search" value="<?= $search ?? $search ?>">

                        <select name="year" style="margin-right: 10px;" onchange="this.form.submit();">
                            <option value="all" <?= $year == 'all' ? 'selected' : "" ?>>All</option>
                            <option value="21" <?= $year == '21' ? 'selected' : "" ?>>2021E</option>
                            <option value="19" <?= $year == '19' ? 'selected' : "" ?>>2019M</option>
                            <option value="17" <?= $year == '17' ? 'selected' : "" ?>>2017M</option>
                            <option value="15" <?= $year == '15' ? 'selected' : "" ?>>2015M</option>
                        </select>

                        <select name="order" style="margin-right: 10px;" onchange="this.form.submit();">
                            <option value="playeron">Current Players</option>
                            <option value="iddesc">Newest First</option>
                            <option value="idasc">Oldest First</option>
                            <option value="likeasc">Most Liked</option>
                            <option value="favasc">Most Favourited</option>
                            <option value="visasc">Most Visits</option>

                        </select>

                        <select name="type" onchange="this.form.submit();">
                            <option value="all">All Items</option>
                        </select>

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

            <!--
      <div class="section-text-div">
        <p class="section-text">All Games</p>
      </div>
      -->
            <div class="games-container">
                <?php
                $ActionFetch = $MainDB->prepare("
                                      SELECT a.*, IFNULL(os.totalPlayerCount, 0) AS playersOnline
                                      FROM asset AS a
                                      LEFT JOIN (
                                          SELECT gameID, SUM(playerCount) AS totalPlayerCount
                                          FROM open_servers
                                          WHERE playerCount > 0
                                          GROUP BY gameID
                                      ) AS os ON a.id = os.gameID
                                      WHERE a.approved = '1' AND a.public = '1' AND a.itemtype = 'place' $yearfilter
                                      $itemfilter
                                      LIMIT 24 OFFSET :loadoffset
                                  ");
                $ActionFetch->bindParam(":loadoffset", $loadoffset, PDO::PARAM_INT);
                $ActionFetch->execute();
                $ActionRows = $ActionFetch->fetchAll();

                foreach ($ActionRows as $GameInfo) {
                    $playersOnline = intval($GameInfo['playersOnline']);

                    $year = $GameInfo["year"];

                    if ($year == 2019) {
                        $string = "2019M";
                    } elseif ($year == 2017) {
                        $string = "2017M";
                    } elseif ($year == 2021) {
                        $string = "2021E";
                    } elseif ($year == 2015) {
                        $string = "2015M";
                    }
                    if (strpos(strtolower($GameInfo['name']), strtolower($search)) !== false) {
                        echo '
        <div class="game-card">
            <a class="game-card-link" href="http://unixfr.xyz/viewgame?id=' . $GameInfo["id"] . '" title="' . $GameInfo["name"] . '">
                <div class="game-card-img-div-square">
                    <img
                        loading="lazy"
                        src="https://unixfr.xyz/Thumbs/AssetIcon.ashx?id=' . $GameInfo["id"] . '"
                        alt=""
                        height="150px"
                        width="150px"
                        class="game-card-img"
                        onload="this.style.display=\'block\'; this.previousElementSibling.style.display=\'none\';"
                    />
                    <p>' . $string . '</p>
                </div>
                <p class="game-card-p">' . nx($GameInfo["name"]) . '</p>
                <p class="game-card-online"> <span class="white-span"> ' . $GameInfo['playersOnline'] . ' </span> online</p>
            </a>
        </div>
    ';
                    }

                    if (empty($ActionRows)) {
                        echo "<span>No games.</span>";
                    }
                }


                ?>


            </div>
        </div>

    </div>
</body>

</html>