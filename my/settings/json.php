<?php


include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/getuser.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/func.php');


$is_admin = ($admin === 1);

$isBC = ($membership === 1 || $membership === 2 || $membership === 3);

if ($RBXTICKET === null) {
    $jsonData = json_encode(array("message" => "Not logged in."));
	http_response_code(400);
} else {
$jsonData = '{
    "ChangeUsernameEnabled": true,
    "IsAdmin": ' . ($is_admin ? 'true' : 'false') . ',
    "UserId": '.$id.',
    "Name": "'.$name.'",
    "DisplayName": "'.$name.'",
    "IsEmailOnFile": true,
    "IsEmailVerified": true,
    "IsPhoneFeatureEnabled": true,
    "RobuxRemainingForUsernameChange": 0,
    "PreviousUserNames": "",
    "UseSuperSafePrivacyMode": false,
    "IsSuperSafeModeEnabledForPrivacySetting": false,
    "UseSuperSafeChat": false,
    "IsAppChatSettingEnabled": true,
    "IsGameChatSettingEnabled": true,
    "IsAccountPrivacySettingsV2Enabled": true,
    "IsSetPasswordNotificationEnabled": false,
    "ChangePasswordRequiresTwoStepVerification": false,
    "ChangeEmailRequiresTwoStepVerification": false,
    "UserEmail": "d****@dummy.com",
    "UserEmailMasked": true,
    "UserEmailVerified": true,
    "CanHideInventory": true,
    "CanTrade": false,
    "MissingParentEmail": false,
    "IsUpdateEmailSectionShown": true,
    "IsUnder13UpdateEmailMessageSectionShown": false,
    "IsUserConnectedToFacebook": false,
    "IsTwoStepToggleEnabled": false,
    "AgeBracket": 0,
    "UserAbove13": true,
    "ClientIpAddress": "123.123.123.123",
    "AccountAgeInDays": 0,
    "IsOBC": ' . ($membership === 3 ? 'true' : 'false') . ',
    "IsTBC": ' . ($membership === 2 ? 'true' : 'false') . ',
    "IsAnyBC": ' . ($isBC ? 'true' : 'false') . ',
    "IsPremium": false,
    "IsBcRenewalMembership": ' . ($isBC ? 'true' : 'false') . ',
    "BcExpireDate": "\/Date(-0)\/",
    "BcRenewalPeriod": null,
    "BcLevel": null,
    "HasCurrencyOperationError": false,
    "CurrencyOperationErrorMessage": null,
    "BlockedUsersModel": {
        "BlockedUserIds": [156],
        "BlockedUsers": [{
            "uid": 156,
            "Name": "builderman"
        }],
        "MaxBlockedUsers": 50,
        "Total": 1,
        "Page": 1
    },
    "Tab": null,
    "ChangePassword": false,
    "IsAccountPinEnabled": true,
    "IsAccountRestrictionsFeatureEnabled": true,
    "IsAccountRestrictionsSettingEnabled": false,
    "IsAccountSettingsSocialNetworksV2Enabled": false,
    "IsUiBootstrapModalV2Enabled": true,
    "IsI18nBirthdayPickerInAccountSettingsEnabled": true,
    "InApp": false,
    "MyAccountSecurityModel": {
        "IsEmailSet": true,
        "IsEmailVerified": true,
        "IsTwoStepEnabled": true,
        "ShowSignOutFromAllSessions": true,
        "TwoStepVerificationViewModel": {
            "UserId": 261,
            "IsEnabled": true,
            "CodeLength": 0,
            "ValidCodeCharacters": null
        }
    },
    "ApiProxyDomain": "https://api.unixfr.xyz",
    "AccountSettingsApiDomain": "https://accountsettings.unixfr.xyz",
    "AuthDomain": "https://auth.unixfr.xyz",
    "IsDisconnectFbSocialSignOnEnabled": true,
    "IsDisconnectXboxEnabled": true,
    "NotificationSettingsDomain": "https://notifications.unixfr.xyz",
    "AllowedNotificationSourceTypes": ["Test", "FriendRequestReceived", "FriendRequestAccepted", "PartyInviteReceived", "PartyMemberJoined", "ChatNewMessage", "PrivateMessageReceived", "UserAddedToPrivateServerWhiteList", "ConversationUniverseChanged", "TeamCreateInvite", "GameUpdate", "DeveloperMetricsAvailable"],
    "AllowedReceiverDestinationTypes": ["DesktopPush", "NotificationStream"],
    "BlacklistedNotificationSourceTypesForMobilePush": [],
    "MinimumChromeVersionForPushNotifications": 50,
    "PushNotificationsEnabledOnFirefox": true,
    "LocaleApiDomain": "https://locale.unixfr.xyz",
    "HasValidPasswordSet": true,
    "IsUpdateEmailApiEndpointEnabled": true,
    "FastTrackMember": null,
    "IsFastTrackAccessible": false,
    "HasFreeNameChange": false,
    "IsAgeDownEnabled": ' . ($isBC ? 'true' : 'false') . ',
    "IsSendVerifyEmailApiEndpointEnabled": true,
    "IsPromotionChannelsEndpointEnabled": true,
    "ReceiveNewsletter": false,
    "SocialNetworksVisibilityPrivacy": 6,
    "SocialNetworksVisibilityPrivacyValue": "AllUsers",
    "Facebook": null,
    "Twitter": "@Shedletsky",
    "YouTube": null,
    "Twitch": null
}';
}

// Display the updated JSON data
echo $jsonData;
?>
