<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
switch (true) {
  case ($RBXTICKET == null):
    die(header("Location: " . $baseUrl . "/"));
    break;
}

//other

$welcomeStrings = array("Hello there, ", "What's up, ", "Up to anything, ", "Hiya, ", "Doing some tomfoolery? ", "Sup, ", "Got time? ", "Are you hacking us,", "What are you doing here, ");
$selectedWelcomeString = $welcomeStrings[array_rand($welcomeStrings)];

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
?>
<nav class="admin-navbar">
  <div class="admin-navbar-content">
    <div class="admin-navbar-left-div">
      <a href="/home">
        <img class="admin-navbar-logo" src="./media/images/elogo.png" alt="Logo" title="Unix!">
      </a>
      <a href="/supersecretadminpanel/">
        <button class="admin-navbar-button">Dashboard</button>
      </a>
      <a href="/supersecretadminpanel/keygen">
        <button class="admin-navbar-button">Keygen</button>
      </a>
      <a href="/supersecretadminpanel/users">
        <button class="admin-navbar-button">Users</button>
      </a>
      <!--
        <a href="/supersecretadminpanel/reports">
          <button class="admin-navbar-button">Reports</button>
        </a>-->
      <a href="/supersecretadminpanel/servers">
        <button class="admin-navbar-button">Servers</button>
      </a>
      <a href="/supersecretadminpanel/assetcreation">
        <button class="admin-navbar-button">Asset Creation</button>
      </a>
      <a href="/supersecretadminpanel/dbmanage">
        <button class="admin-navbar-button">Database</button>
      </a>
      <a href="/supersecretadminpanel/notification">
        <button class="admin-navbar-button">Notifications</button>
      </a>
      <a href="/supersecretadminpanel/webhook">
        <button class="admin-navbar-button">Webhook</button>
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