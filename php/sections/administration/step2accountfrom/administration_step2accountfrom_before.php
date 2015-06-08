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
    isset ($_SESSION['token_json_goog'])
) {
    $client->setAccessToken($_SESSION['token_json_goog']);
} # if

$arrTmp = array ();
if (
    $client->getAccessToken()
) {

    $service = new Google_AnalyticsService($client);

    $accnts = $service->management_accounts->listManagementAccounts();

    if (
        count($accnts->items)
    ) {
        foreach ( $accnts->items as $k => $v ) {
            $arrTmp[ $v->id ] = array (
                'id' => $v->id,
                'name' => $v->name,
            );
        } # foreach
    } # if

    $_SESSION['arrAnltcsAccounts'] = $arrTmp;

} # if

$htmlTitleElement = 'Origin, from what account\'to copy &middot; Administration';

?>