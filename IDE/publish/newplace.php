<?php include_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
$logged = false;
if (isset($_COOKIE['ROBLOSECURITY'])) {
    $roblosec = filter_var($_COOKIE['ROBLOSECURITY'], FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
    $usrquery = $MainDB->prepare("SELECT * FROM `users` WHERE `token` = :ROBLOSECURITY");
    $usrquery->execute(['ROBLOSECURITY' => $roblosec]);
    $usr = $usrquery->fetch();
    if ($usr != 0) {
        $logged = true;
    }
}

function getFreeId(){
	include_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
$gamesQuery = $MainDB->prepare("SELECT MAX(id) AS max_id FROM asset");
$gamesQuery->execute();
$gamesMaxId = $gamesQuery->fetch(PDO::FETCH_ASSOC)['max_id'];
$maxId = $gamesMaxId;
$nextId = $maxId + 1;
return $nextId;
}
$placeId = getFreeId();
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
<head data-machine-id="WEB1262">

<title>Publish New Place</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,requiresActiveX=true" />
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="Roblox Corporation" />
<meta name="description" content="Roblox is a global platform that brings people together through play." />
<meta name="keywords" content="free games, online games, building games, virtual worlds, free mmo, gaming cloud, physics engine" />
<meta name="apple-itunes-app" content="app-id=431946152" />
<meta ng-csp="no-unsafe-eval">
<link onerror='Roblox.BundleDetector && Roblox.BundleDetector.reportBundleError(this)' rel='stylesheet' href='http://www.unixfr.xyz/css/publish.css' />
<link onerror='Roblox.BundleDetector && Roblox.BundleDetector.reportBundleError(this)' rel='stylesheet' href='http://www.unixfr.xyz/css/publishupdate.css' />
<script>
//Set if it browser's do not track flag is enabled
var Roblox = Roblox || {};
(function () {
var dnt = navigator.doNotTrack || window.doNotTrack || navigator.msDoNotTrack;
if (typeof window.external !== "undefined" &&
typeof window.external.msTrackingProtectionEnabled !== "undefined") {
dnt = dnt || window.external.msTrackingProtectionEnabled();
}
Roblox.browserDoNotTrack = dnt == "1" || dnt == "yes" || dnt === true;
})();
</script>
<script onerror='Roblox.BundleDetector && Roblox.BundleDetector.reportBundleError(this)' data-monitor='true' data-bundlename='studio' type='text/javascript' src='http://www.unixfr.xyz/js/3719f3fb35135d05cf6b72d5b0f46333.js'>
</script>
<script type='text/javascript'>
Roblox.config.externalResources = [];
Roblox.config.paths['Pages.Catalog'] = 'http://www.unixfr.xyz/js/109d883fe3988fca757e26e341ed0fe8.js';
Roblox.config.paths['Pages.CatalogShared'] = 'http://www.unixfr.xyz/js/33126cd3e259a404a2563594f55a3f06.js';
Roblox.config.paths['Widgets.AvatarImage'] = 'http://www.unixfr.xyz/js/7d49ac94271bd506077acc9d0130eebb.js';
Roblox.config.paths['Widgets.DropdownMenu'] = 'http://www.unixfr.xyz/js/da553e6b77b3d79bec37441b5fb317e7.js';
Roblox.config.paths['Widgets.HierarchicalDropdown'] = 'http://www.unixfr.xyz/js/4a0af9989732810851e9e12809aeb8ad.js';
Roblox.config.paths['Widgets.ItemImage'] = 'http://www.unixfr.xyz/js/61a0490ba23afa17f9ecca2a079a6a57.js';
Roblox.config.paths['Widgets.PlaceImage'] = 'http://www.unixfr.xyz/js/a6df74a754523e097cab747621643c98.js';
</script>
<script onerror='Roblox.BundleDetector && Roblox.BundleDetector.reportBundleError(this)' data-monitor='true' data-bundlename='page' type='text/javascript' src='http://www.unixfr.xyz/js/a1ec30f16d5202192e1d89ed4aca2c58.js'>
</script>
<script type="text/javascript">
if (typeof (Roblox) === "undefined") {
Roblox = {};
}
Roblox.Endpoints = Roblox.Endpoints || {};
Roblox.Endpoints.Urls = Roblox.Endpoints.Urls || {};
Roblox.Endpoints.Urls['/api/item.ashx'] = 'http://www.unixfr.xyz/api/item.ashx';
Roblox.Endpoints.Urls['/asset/'] = 'http://www.unixfr.xyz/asset/';
Roblox.Endpoints.Urls['/client-status/set'] = 'http://www.unixfr.xyz/client-status/set';
Roblox.Endpoints.Urls['/client-status'] = 'http://www.unixfr.xyz/client-status';
Roblox.Endpoints.Urls['/game/'] = 'http://www.unixfr.xyz/game/';
Roblox.Endpoints.Urls['/game-auth/getauthticket'] = 'http://www.unixfr.xyz/game-auth/getauthticket';
Roblox.Endpoints.Urls['/game/edit.ashx'] = 'http://www.unixfr.xyz/game/edit.ashx';
Roblox.Endpoints.Urls['/game/getauthticket'] = 'http://www.unixfr.xyz/game/getauthticket';
Roblox.Endpoints.Urls['/game/placelauncher.ashx'] = 'http://www.unixfr.xyz/game/placelauncher.ashx';
Roblox.Endpoints.Urls['/game/preloader'] = 'http://www.unixfr.xyz/game/preloader';
Roblox.Endpoints.Urls['/game/report-stats'] = 'http://www.unixfr.xyz/game/report-stats';
Roblox.Endpoints.Urls['/game/report-event'] = 'http://www.unixfr.xyz/game/report-event';
Roblox.Endpoints.Urls['/game/updateprerollcount'] = 'http://www.unixfr.xyz/game/updateprerollcount';
Roblox.Endpoints.Urls['/login/default.aspx'] = 'http://www.unixfr.xyz/login/default.aspx';
Roblox.Endpoints.Urls['/my/character.aspx'] = 'http://www.unixfr.xyz/my/character.aspx';
Roblox.Endpoints.Urls['/my/money.aspx'] = 'http://www.unixfr.xyz/my/money.aspx';
Roblox.Endpoints.Urls['/chat/chat'] = 'http://www.unixfr.xyz/chat/chat';
Roblox.Endpoints.Urls['/presence/users'] = 'http://www.unixfr.xyz/presence/users';
Roblox.Endpoints.Urls['/presence/user'] = 'http://www.unixfr.xyz/presence/user';
Roblox.Endpoints.Urls['/friends/list'] = 'http://www.unixfr.xyz/friends/list';
Roblox.Endpoints.Urls['/navigation/getCount'] = 'http://www.unixfr.xyz/navigation/getCount';
Roblox.Endpoints.Urls['/catalog/browse.aspx'] = 'http://www.unixfr.xyz/catalog/browse.aspx';
Roblox.Endpoints.Urls['/catalog/html'] = 'http://www.unixfr.xyz/catalog/html';
Roblox.Endpoints.Urls['/catalog/json'] = 'http://www.unixfr.xyz/catalog/json';
Roblox.Endpoints.Urls['/catalog/contents'] = 'http://www.unixfr.xyz/catalog/contents';
Roblox.Endpoints.Urls['/catalog/lists.aspx'] = 'http://www.unixfr.xyz/catalog/lists.aspx';
Roblox.Endpoints.Urls['/asset-hash-thumbnail/image'] = 'http://www.unixfr.xyz/asset-hash-thumbnail/image';
Roblox.Endpoints.Urls['/asset-hash-thumbnail/json'] = 'http://www.unixfr.xyz/asset-hash-thumbnail/json';
Roblox.Endpoints.Urls['/asset-thumbnail-3d/json'] = 'http://www.unixfr.xyz/asset-thumbnail-3d/json';
Roblox.Endpoints.Urls['/asset-thumbnail/image'] = 'http://www.unixfr.xyz/asset-thumbnail/image';
Roblox.Endpoints.Urls['/asset-thumbnail/json'] = 'http://www.unixfr.xyz/asset-thumbnail/json';
Roblox.Endpoints.Urls['/asset-thumbnail/url'] = 'http://www.unixfr.xyz/asset-thumbnail/url';
Roblox.Endpoints.Urls['/asset/request-thumbnail-fix'] = 'http://www.unixfr.xyz/asset/request-thumbnail-fix';
Roblox.Endpoints.Urls['/avatar-thumbnail-3d/json'] = 'http://www.unixfr.xyz/avatar-thumbnail-3d/json';
Roblox.Endpoints.Urls['/avatar-thumbnail/image'] = 'http://www.unixfr.xyz/avatar-thumbnail/image';
Roblox.Endpoints.Urls['/avatar-thumbnail/json'] = 'http://www.unixfr.xyz/avatar-thumbnail/json';
Roblox.Endpoints.Urls['/avatar-thumbnails'] = 'http://www.unixfr.xyz/avatar-thumbnails';
Roblox.Endpoints.Urls['/avatar/request-thumbnail-fix'] = 'http://www.unixfr.xyz/avatar/request-thumbnail-fix';
Roblox.Endpoints.Urls['/bust-thumbnail/json'] = 'http://www.unixfr.xyz/bust-thumbnail/json';
Roblox.Endpoints.Urls['/group-thumbnails'] = 'http://www.unixfr.xyz/group-thumbnails';
Roblox.Endpoints.Urls['/groups/getprimarygroupinfo.ashx'] = 'http://www.unixfr.xyz/groups/getprimarygroupinfo.ashx';
Roblox.Endpoints.Urls['/headshot-thumbnail/json'] = 'http://www.unixfr.xyz/headshot-thumbnail/json';
Roblox.Endpoints.Urls['/item-thumbnails'] = 'http://www.unixfr.xyz/item-thumbnails';
Roblox.Endpoints.Urls['/outfit-thumbnail/json'] = 'http://www.unixfr.xyz/outfit-thumbnail/json';
Roblox.Endpoints.Urls['/place-thumbnails'] = 'http://www.unixfr.xyz/place-thumbnails';
Roblox.Endpoints.Urls['/thumbnail/asset/'] = 'http://www.unixfr.xyz/thumbnail/asset/';
Roblox.Endpoints.Urls['/thumbnail/avatar-headshot'] = 'http://www.unixfr.xyz/thumbnail/avatar-headshot';
Roblox.Endpoints.Urls['/thumbnail/avatar-headshots'] = 'http://www.unixfr.xyz/thumbnail/avatar-headshots';
Roblox.Endpoints.Urls['/thumbnail/user-avatar'] = 'http://www.unixfr.xyz/thumbnail/user-avatar';
Roblox.Endpoints.Urls['/thumbnail/resolve-hash'] = 'http://www.unixfr.xyz/thumbnail/resolve-hash';
Roblox.Endpoints.Urls['/thumbnail/place'] = 'http://www.unixfr.xyz/thumbnail/place';
Roblox.Endpoints.Urls['/thumbnail/get-asset-media'] = 'http://www.unixfr.xyz/thumbnail/get-asset-media';
Roblox.Endpoints.Urls['/thumbnail/remove-asset-media'] = 'http://www.unixfr.xyz/thumbnail/remove-asset-media';
Roblox.Endpoints.Urls['/thumbnail/set-asset-media-sort-order'] = 'http://www.unixfr.xyz/thumbnail/set-asset-media-sort-order';
Roblox.Endpoints.Urls['/thumbnail/place-thumbnails'] = 'http://www.unixfr.xyz/thumbnail/place-thumbnails';
Roblox.Endpoints.Urls['/thumbnail/place-thumbnails-partial'] = 'http://www.unixfr.xyz/thumbnail/place-thumbnails-partial';
Roblox.Endpoints.Urls['/thumbnail_holder/g'] = 'http://www.unixfr.xyz/thumbnail_holder/g';
Roblox.Endpoints.Urls['/users/{id}/profile'] = 'http://www.unixfr.xyz/users/{id}/profile';
Roblox.Endpoints.Urls['/service-workers/push-notifications'] = 'http://www.unixfr.xyz/service-workers/push-notifications';
Roblox.Endpoints.Urls['/notification-stream/notification-stream-data'] = 'http://www.unixfr.xyz/notification-stream/notification-stream-data';
Roblox.Endpoints.Urls['/api/friends/acceptfriendrequest'] = 'http://www.unixfr.xyz/api/friends/acceptfriendrequest';
Roblox.Endpoints.Urls['/api/friends/declinefriendrequest'] = 'http://www.unixfr.xyz/api/friends/declinefriendrequest';
Roblox.Endpoints.addCrossDomainOptionsToAllRequests = true;
</script>
<script type="text/javascript">
if (typeof (Roblox) === "undefined") {
Roblox = {};
}
Roblox.Endpoints = Roblox.Endpoints || {};
Roblox.Endpoints.Urls = Roblox.Endpoints.Urls || {};
</script>
</head>
<body>
<div class="boxed-body" data-return-url="http://www.unixfr.xyz/ide/publishas/">
<div id="placeForm">
<div class="headline">
<h2>Basic Settings</h2>
</div>
<div class="form-container">
    <form id="basicSettingsForm" method="post" action="http://www.unixfr.xyz/ide/publish/editplace">
        <input type="hidden" id="GroupId" name="GroupId" value="" />
        <input type="hidden" id="placeId" name="placeId" value="<?= htmlspecialchars($placeId) ?>" />

        <label class="form-label" for="Name">Name:</label>
        <input type="text" id="Name" name="Name" class="text-box text-box-medium" data-val="true" data-val-required="Name is required" />
        <span id="nameRow"><span class="field-validation-valid" data-valmsg-for="Name" data-valmsg-replace="true"></span></span>

        <label class="form-label" for="Description">Description:</label>
        <textarea id="Description" name="Description" class="text-box text-area-medium" cols="80" rows="6"></textarea>
        <span class="field-validation-valid" data-valmsg-for="Description" data-valmsg-replace="true"></span>

        <label class="form-label" for="Genre">Genre:</label>
        <select id="Genre" name="Genre" class="form-select" data-val="true" data-val-required="Genre is required">
            <option value="All">All</option>
            <option value="Adventure">Adventure</option>
            <option value="Building">Building</option>
            <option value="Comedy">Comedy</option>
            <option value="Fighting">Fighting</option>
            <option value="FPS">FPS</option>
            <option value="Horror">Horror</option>
            <option value="Medieval">Medieval</option>
            <option value="Military">Military</option>
            <option value="Naval">Naval</option>
            <option value="RPG">RPG</option>
            <option value="Sci-Fi">Sci-Fi</option>
            <option value="Sports">Sports</option>
            <option value="Town and City">Town and City</option>
            <option value="Western">Western</option>
        </select>
        <span class="field-validation-valid" data-valmsg-for="Genre" data-valmsg-replace="true"></span>

        <div class="footer-button-container divider-top">
            <button type="submit" class="btn-medium btn-primary" id="finishButton">Finish</button>
            <button type="button" class="btn-medium btn-negative" id="cancelButton">Cancel</button>
        </div>
    </form>
</div>

<script type="text/javascript">
function urchinTracker() {}
</script>
<div class="ConfirmationModal modalPopup unifiedModal smallModal" data-modal-handle="confirmation" style="display:none;">
<a class="genericmodal-close ImageButton closeBtnCircle_20h"></a>
<div class="Title"></div>
<div class="GenericModalBody">
<div class="TopBody">
<div class="ImageContainer roblox-item-image" data-image-size="small" data-no-overlays data-no-click>
<img class="GenericModalImage" alt="generic image" />
</div>
<div class="Message"></div>
</div>
<div class="ConfirmationModalButtonContainer GenericModalButtonContainer">
<a href id="roblox-confirm-btn"><span></span></a>
<a href id="roblox-decline-btn"><span></span></a>
</div>
<div class="ConfirmationModalFooter">
</div>
</div>
<script type="text/javascript">
Roblox = Roblox || {};
Roblox.Resources = Roblox.Resources || {};
Roblox.Resources.GenericConfirmation = {
yes: "Yes",
No: "No",
Confirm: "Confirm",
Cancel: "Cancel"
};
</script>
</div>
</body>
</html>