<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

//other

$welcomeStrings = array("gay", "What's up, ", "Up to anything, ", "Hiya, ", "Doing some tomfoolery? ", "Sup, ", "Got time? ", "Are you hacking us,", "What are you doing here, ");
$selectedWelcomeString = $welcomeStrings[array_rand($welcomeStrings)];

$RBXTICKET = $_COOKIE['ROBLOSECURITY'] ?? null;

switch (true) {
  case ($RBXTICKET == null):
    header("Location: " . $baseUrl . "/");
    die();
    break;
  default:
    // Redirect to the video if $admin is not set to 1
    if ($admin != 2) {
      header("Location: " . $baseUrl . "/");
      die();
    }
    break;
}
?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/index.css?v=<?php echo (rand(1, 50)); ?>">
</head>

<body>

  <nav class="admin-navbar">
    <div class="admin-navbar-content">
      <div class="admin-navbar-left-div">
        <a href="/home">
          <img class="admin-navbar-logo" src="./media/images/elogo.png" alt="Logo" title="Unix!">
        </a>

        <a href="/">
          <button class="admin-navbar-button">Back</button>
        </a>


      </div>

      <div class="admin-navbar-right-div">
        <p class="admin-navbar-text">
          <?php echo "$selectedWelcomeString $name"; ?>
        </p>

        <p class="admin-navbar-text">
          |
        </p>

        <p class="admin-navbar-text">
          R$
          <?php echo $robux; ?>
        </p>

        <a href="/settings">
          <button class="admin-navbar-button">Settings</button>
        </a>
      </div>


    </div>
  </nav>
</body>

</html>