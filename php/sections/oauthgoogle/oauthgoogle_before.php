<?php if(!defined('ANTI_DIRECT'))exit;

require_once FS_ABS_PATH_TO_FUNCSLIBS . 'google-api-php-client/src/Google_Client.php';
require_once FS_ABS_PATH_TO_FUNCSLIBS . 'google-api-php-client/src/contrib/Google_AnalyticsService.php';

$client = new Google_Client();

$client->setClientId(GANALYTICS_CLIENT_ID);
$client->setClientSecret(GANALYTICS_CLIENT_SECRET);
$client->setRedirectUri(GANALYTICS_REDIRECT_URI);
$client->setScopes(array (
    'https://www.googleapis.com/auth/analytics',
    'https://www.googleapis.com/auth/analytics.edit',
    'https://www.googleapis.com/auth/analytics.readonly',
));

$client->setUseObjects(true);

if (
    isset ($_GET['code'])
) {
    # accepted pressed

    $client->authenticate();

    $_SESSION['token_json_goog'] = $client->getAccessToken();

    header('Location: ' . HTTP_ABS_PATH_TO_HOST_ROOT . '/?s=administration&s2=step2accountfrom');
    exit (); # !!!

} # if

if (
    isset ($_SESSION['token_json_goog'])
) {
    $client->setAccessToken($_SESSION['token_json_goog']);
} # if

/*
if (
    $client->getAccessToken()
) {

    $service = new Google_AnalyticsService($client);

    $props = $service->management_webproperties->listManagementWebproperties("~all");

    $_SESSION['arrGoogleWebprops'] = array ();
    if (
        count($props->items)
    ) {
        foreach ( $props->items as $k => $v ) {
            if (
                $v->profileCount > 0
            ) {
                $_SESSION['arrGoogleWebprops'][] = array (
                    'accountId' => $v->accountId,
                    'id' => $v->id, # for example, UA-584028-4
                    'name' => $v->name,
                    'websiteUrl' => $v->websiteUrl,
                );
            } # if
        } # foreach
    } # if

    header('Location: ' . HTTP_ABS_PATH_TO_HOST_ROOT . '/?s=administration&s2=webproperties');
    exit (); # !!!

} # if
*/

header('Location: ' . HTTP_ABS_PATH_TO_HOST_ROOT . '/?s=administration');
exit (); # !!!

?>