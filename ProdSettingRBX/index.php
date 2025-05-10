<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

header("Content-Type: application/json");

// moved pc player seperately as it didnt allow us to format it properly


$MobileClientiOS = [
  "FFlagRenderNoDMLock" => "True",
  "FFlagFRMCullDebris" => "True",
  "FFlagFRMAdornSupport" => "True",
  "FFlagDMFeatherweightEnabled" => "True",
  "FFlagRenderFeatherweightEnabled" => "True",
  "iPad2_MaximumIdealParts" => 0,
  "FFlagLoadingGuiEnabled" => "False",
  "iPadMinimumVersion" => 1,
  "iPhoneMinimumVersion" => 1,
  "iPodMinimumVersion" => 1,
  "TimeIntervalBetweenRobuxPurchaseInMinutes" => "10",
  "FFlagRenderFeatherweightUseGeometryGenerator" => "True",
  "FFlagInGamePurchases" => "True",
  "FFlagOpenBrowserWindowFromLua" => "False",
  "FFlagRenderTextureCompositorUse32Bit" => "False",
  "FFlagFRMUsePerformanceLockstepTable" => "True",
  "FLogViewRbxInit" => "7",
  "DisablePlayButtonForAll" => "False",
  "TestFlightLoggingLevel" => "1",
  "FFlagVoxelGridInsideMegaCluster" => "True",
  "SearchEndpointIPad" => "Browse.aspx?name=",
  "SearchEndpointIPhone" => "people?search=",
  "FFlagRenderOptimizedTextureUpload" => "False",
  "FFlagAutoJumpForTouchDevices" => "True",
  "FFlagUseVirtualMethodForCell" => "True",
  "FFlagSuppressSIGPIPEOnShutdown" => "True",
  "iOSGoogleAnalyticsAccount" => "UA-486632-14",
  "FFlagRenderLoopExplicit" => "True",
  "FFlagMarkRakNetSocketOnDelete" => "True",
  "FFlagRenderNewMegaCluster" => "True",
  "TestFlightPercentage" => "0",
  "BugSensePercentage" => "90",
  "MemoryBouncerActive" => "False",
  "MemoryBouncerEnforceRateMilliSeconds" => "100",
  "MemoryBouncerThresholdKiloBytes" => 5120,
  "MemoryBouncerLimitMegaBytes" => 250,
  "MemoryBouncerLimitMegaBytesLow" => 0,
  "MemoryBouncerDelayCount" => 10,
  "iOSGoogleAnalyticsAccount2" => "UA-42322750-1",
  "BugSenseLogLines" => "200",
  "FFlagStopProcessingPacketsIfDataModelIsShuttingDown" => "True",
  "FFlagDisableCookiesServiceForiOS" => "True",
  "FreeMemoryCheckerActive" => "False",
  "FreeMemoryCheckerRateMilliSeconds" => 100,
  "FreeMemoryCheckerThresholdKiloBytes" => 4096,
  "FFlagPhysics60HZ" => "True",
  "FFlagNewWaterMaterialEnable" => "False",
  "FFlagRenderNewMaterials" => "False",
  "FFlagRenderAnisotropy" => "False",
  "MaxMemoryReporterRateMilliSeconds" => "100",
  "iOSGoogleAnalyticsSampleRate" => "20",
  "DisplayMemoryWarning" => "False",
  "FFlagNoCacheForLocalContent" => "True",
  "ClearCacheOnMemoryWarning" => "True",
  "EnableMobileAppTracking" => "True",
  "FIntTimeIntervalBetweenRobuxPurchaseInMinutes" => 1,
  "FIntTimeIntervalBetweenCatalogPurchaseInMinutes" => 1,
  "UseKeychain" => "True",
  "FFlagRenderMaterialsOnMobile" => "True",
  "CrashlyticsPercentage" => 100,
  "FFlagNewBackpackScript" => "True",
  "FFlagNewPlayerListScript" => "True",
  "EnableFriendsAndFollowers" => "True",
  "EnableFriendsOnProfile" => "False",
  "ReadInAppPurchaseSettingsBeforeEveryPurchase" => "False",
  "AllowAppleInAppPurchase" => "True",
  "EnableWebPageGameDetail" => "True",
  "HttpUseCurlPercentageMacClient" => "100",
  "FFlagUseInGameTopBar" => "True",
  "FFlagTaskSchedulerCyclicExecutive" => "True",
  "DFFlagGuiBase3dReplicateColor3WithBrickColor" => "True",
  "DFFlagUseNewAnalyticsApi" => "False"
];
$AndroidClientiOS = [
  "GoogleVideoAdUrl" => "",
  "AdColonyZoneId" => "",
  "AdColonyAppId" => "",
  "EnableRbxAnalytics" => "False",
  "UseNewWebGamesPage" => "False",
  "GigyaPrefix" => "",
  "EnabledSponsoredZoom" => "False",
  "EnableUtilsAlertFix" => "True",
  "EnableFacebookAuth" => "False",
  "EnableGameStartFix" => "False",
  "EnableGoogleAnalyticsChange" => "False",
  "FIntAvatarEditorAndroidRollout" => "1",
  "FIntEnableAvatarEditorAndroid" => "1",
  "FIntEnableAvatarEditoriOS" => "100",
  "EnableCookieConsistencyChecks" => "False",
  "EnableRotationGestureFix" => "True",
  "EnableRbxReportingManager" => "False",
  "EnableInfluxV2" => "False",
  "InfluxUrl" => "",
  "InfluxDatabase" => "",
  "InfluxUser" => "",
  "InfluxPassword" => "",
  "InfluxThrottleRate" => "0",
  "EnableNeonBlocker" => "True",
  "EnableLoginFailureExactReason" => "True",
  "EnableLoginWriteOnSuccessOnly" => "False",
  "EnableXBOXSignupRules" => "False",
  "EnableInputListenerActivePointerNullFix" => "False",
  "EnableWelcomeAnimation" => "False",
  "EnableShellLogoutOnWebViewLogout" => "False",
  "EnableSetWebViewBlankOnLogout" => "False",
  "EnableLoginLogoutSignupWithApi" => "False",
  "EnableSessionLogin" => "True",
  "AndroidInferredCrashReporting" => "False",
  "EnableAuthCookieAnalytics" => "False"
];


$Bootstrapper = [
  "NeedInstallBgTask" => false,
  "NeedRunBgTask" => false,
  "IsPreDeployOn" => false,
  "PreVersion" => "",
  "ForceSilentMode" => false,
  "CountersLoad" => 100,
  "EnableWebRedirect" => true,
  "LaunchWithLegacyFlagEnabled" => 100,
  "PerModeLoggingEnabled" => true,
  "BetaPlayerLoad" => 100,
  "CountersFireImmediately" => true,
  "ExeVersion" => "0.300.0.7899",
  "ValidateInstalledExeVersion" => false,
  "UseNewCdn" => true,
  "UseFastStartup" => true,
  "CheckIsStudioOutofDate" => true,
  "ShowInstallSuccessPrompt" => true
];

// Encode the array in JSON
$encodedPC = file_get_contents("PCPlayer.json");
$encodediOS = json_encode($MobileClientiOS, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
$encodedBootstrapper = json_encode($Bootstrapper, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
$encodedAndroid = json_encode($AndroidClientiOS, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

$switchMap = [
  '/Setting/QuietGet/ClientSharedSettings' => $encodedPC,
  '/Setting/QuietGet/ClientAppSettings' => $encodedPC,
  '/Setting/QuietGet/RCCService' => $encodedPC,
  '/Setting/QuietGet/iOSAppSettings' => $encodediOS,
  '/Setting/QuietGet/WindowsBootstrapperSettings' => $encodedBootstrapper,
  '/Setting/QuietGet/AndroidAppSettings' => $encodedAndroid,
  '/Setting/Get/AndroidAppSettings' => $encodedAndroid
];

foreach ($switchMap as $search => $result) {
  if (strpos($CurrPage, $search) !== false) {
    die($result);
  }
}
