<?php if(!defined('ANTI_DIRECT'))exit;

$idAccount = ss($_GET['id']);

require_once FS_ABS_PATH_TO_FUNCSLIBS . 'google-api-php-clientNEW/src/Google/Client.php';
require_once FS_ABS_PATH_TO_FUNCSLIBS . 'google-api-php-clientNEW/src/Google/Service/Analytics.php';
/*
            require_once FS_ABS_PATH_TO_FUNCSLIBS . 'google-api-php-client/src/Google_Client.php';
            require_once FS_ABS_PATH_TO_FUNCSLIBS . 'google-api-php-client/src/contrib/Google_AnalyticsService.php';
*/

$htmlReport = '';
try {

    $objGoogleClient = new Google_Client();
#            $client = new Google_Client();

    $objGoogleClient->setClientId(GANALYTICS_CLIENT_ID);
#            $client->setClientId(GANALYTICS_CLIENT_ID);

    $objGoogleClient->setClientSecret(GANALYTICS_CLIENT_SECRET);
#            $client->setClientSecret(GANALYTICS_CLIENT_SECRET);

    $objGoogleClient->setRedirectUri(GANALYTICS_REDIRECT_URI);
#            $client->setRedirectUri(GANALYTICS_REDIRECT_URI);

    $objGoogleClient->setScopes(array (
        'https://www.googleapis.com/auth/analytics',
        'https://www.googleapis.com/auth/analytics.edit',
        'https://www.googleapis.com/auth/analytics.readonly',
    ));
#            $client->setScopes(array ('https://www.googleapis.com/auth/analytics'));

#            $client->setUseObjects(true);

    if (
        isset ($_SESSION['token_json_goog'])
    ) {
        $objGoogleClient->setAccessToken($_SESSION['token_json_goog']);
#        $client->setAccessToken($_SESSION['token_json_goog']);
    } # if

    $arrTmp = array ();
    if (
        $objGoogleClient->getAccessToken()
#        $client->getAccessToken()
    ) {

        $objService = new Google_Service_Analytics($objGoogleClient);
#        $service = new Google_AnalyticsService($client);

        $mixAnswerGoogle = $objService->management_filters->listManagementFilters(
            $idAccount
        );
#        $accnts = $service->management_accounts->listManagementAccounts();

        if (
            count($mixAnswerGoogle->items)
        ) {
            foreach ( $mixAnswerGoogle->items as $k => $v ) {

                $arrOne = array (
                    'id' => $v->id,
                    'name' => $v->name,
                    'type' => $v->type,
                );

                foreach ( array ('excludeDetails', 'includeDetails', 'lowercaseDetails', 'uppercaseDetails', 'searchAndReplaceDetails', 'advancedDetails') as $v2 ) {
                    if (
                        isset ($v->$v2)
                    ) {

                        $arrOne[$v2] = array ();

                        foreach ( $v->$v2 as $k3 => $v3 ) {
                            if (
                                ! is_object($v3)
                            &&
                                $k3 != 'kind' # !!!
                            ) {
                                $arrOne[$v2][$k3] = $v3;
                            } # if
                        } # foreach

                    } # if
                } # foreach

                $arrTmp[ $v->id ] = $arrOne;

            } # foreach
        } # if

        $_SESSION['arrAnltcsAccFilters'][$idAccount] = $arrTmp;

    } # if

} # try
catch (apiServiceException $e) {
    // Error from the API.
    $htmlReport .= '<font color="red">Error (api, код ' . h($e->getCode()) . '): ' . h($e->getMessage()) . '</font><br>';
} # catch
catch (Exception $e) {
    // General error.
    $htmlReport .= '<font color="red">Error (general): ' . h($e->getMessage()) . '</font><br>';
} # catch

$htmlTitleElement = 'What filter to copy &middot; Administration';

?>