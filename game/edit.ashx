<?php
header('Content-Type: text/plain');
header('X-Robots-Tag: noindex');
$placeId = $_GET['PlaceID'] ?? $_GET['PlaceId'] ?? $_GET['placeID'] ?? $_GET['placeId'] ?? 0;
ob_start();
?>
-- Prepended to Edit.lua and Visit.lua and Studio.lua--
function ifSeleniumThenSetCookie(key, value)
if false then
game:GetService("CookiesService"):SetCookieValue(key, value)
end
end
ifSeleniumThenSetCookie("SeleniumTest1", "Inside the visit lua script")
pcall(function() game:SetPlaceID(<?=$placeId?>) end)
visit = game:GetService("Visit")
local message = Instance.new("Message")
message.Parent = workspace
message.archivable = false
game:GetService("ContentProvider"):SetThreadPool(16)
pcall(function() game:GetService("InsertService"):SetFreeModelUrl("https://www.unixfr.xyz/Game/Tools/InsertAsset.ashx?type=fm&q=%s&pg=%d&rs=%d") end) -- Used for free model search (insert tool)
pcall(function() game:GetService("InsertService"):SetFreeDecalUrl("https://www.unixfr.xyz/Game/Tools/InsertAsset.ashx?type=fd&q=%s&pg=%d&rs=%d") end) -- Used for free decal search (insert tool)
ifSeleniumThenSetCookie("SeleniumTest2", "Set URL service")
settings().Diagnostics:LegacyScriptMode()
game:GetService("InsertService"):SetBaseSetsUrl("https://www.unixfr.xyz/Game/Tools/InsertAsset.ashx?nsets=10&type=base")
game:GetService("InsertService"):SetUserSetsUrl("https://www.unixfr.xyz/Game/Tools/InsertAsset.ashx?nsets=20&type=user&userid=%d")
game:GetService("InsertService"):SetCollectionUrl("https://www.unixfr.xyz/Game/Tools/InsertAsset.ashx?sid=%d")
game:GetService("InsertService"):SetAssetUrl("https://www.unixfr.xyz/Asset/?id=%d")
game:GetService("InsertService"):SetAssetVersionUrl("https://www.unixfr.xyz/Asset/?assetversionid=%d")
-- TODO: move this to a text file to be included with other scripts
pcall(function() game:GetService("SocialService"):SetFriendUrl("https://www.unixfr.xyz/Game/LuaWebService/HandleSocialRequest.ashx?method=IsFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetBestFriendUrl("https://www.unixfr.xyz/Game/LuaWebService/HandleSocialRequest.ashx?method=IsBestFriendsWith&playerid=%d&userid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupUrl("https://www.unixfr.xyz/Game/LuaWebService/HandleSocialRequest.ashx?method=IsInGroup&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupRankUrl("https://www.unixfr.xyz/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRank&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("SocialService"):SetGroupRoleUrl("https://www.unixfr.xyz/Game/LuaWebService/HandleSocialRequest.ashx?method=GetGroupRole&playerid=%d&groupid=%d") end)
pcall(function() game:GetService("GamePassService"):SetPlayerHasPassUrl("https://www.unixfr.xyz/Game/GamePass/GamePassHandler.ashx?Action=HasPass&UserID=%d&PassID=%d") end)
pcall(function() game:GetService("MarketplaceService"):SetProductInfoUrl("https://www.unixfr.xyz/marketplace/productinfo?assetId=%d") end)
--pcall(function() game:GetService("MarketplaceService"):SetDevProductInfoUrl("https://www.unixfr.xyz/marketplace/productDetails?productId=%d") end)
pcall(function() game:GetService("MarketplaceService"):SetPlayerOwnsAssetUrl("https://www.unixfr.xyz/ownership/hasasset?userId=%d&assetId=%d") end)
pcall(function() game:SetCreatorID(1, Enum.CreatorType.User) end)
ifSeleniumThenSetCookie("SeleniumTest3", "Set creator ID")
pcall(function() game:SetVideoInfo("") end)
pcall(function()
registerPlay("rbx_evt_ftp")
delay(60*5, function() registerPlay("rbx_evt_fmp") end)
end)
ifSeleniumThenSetCookie("SeleniumTest4", "Exiting SingleplayerSharedScript")-- SingleplayerSharedScript.lua inserted here --
message.Text = "Loading Place. Please wait..."
coroutine.yield()
game:Load("https://www.unixfr.xyz/asset/?id=<?=$placeId;?>")
message.Parent = nil
game:GetService("ChangeHistoryService"):SetEnabled(true)
<?php
$data = "\r\n" . ob_get_clean();
$key = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/game/0b1BTQn2BgFq.pem");
openssl_sign($data, $sig, $key, OPENSSL_ALGO_SHA1);
echo "--rbxsig%" . base64_encode($sig) . "%" . $data;
?>