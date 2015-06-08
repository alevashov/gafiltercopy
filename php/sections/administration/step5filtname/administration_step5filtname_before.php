<?php if(!defined('ANTI_DIRECT'))exit;

$idAccount = ss($_REQUEST['id']);
$idFilterAcc = ss($_REQUEST['fid']);
$idAccountTo = ss($_REQUEST['idto']);

require_once FS_ABS_PATH_TO_FUNCSLIBS . 'google-api-php-clientNEW/src/Google/Client.php';
require_once FS_ABS_PATH_TO_FUNCSLIBS . 'google-api-php-clientNEW/src/Google/Service/Analytics.php';

if (
    isset ($_POST['new_name'])
) {

    $strNewName = trim(ss($_POST['new_name']));
    if (
        $strNewName != ''
    ) {

        $_SESSION['newFilterName'] = $strNewName;

        try {

            $objGoogleClient = new Google_Client();

            $objGoogleClient->setClientId(GANALYTICS_CLIENT_ID);

            $objGoogleClient->setClientSecret(GANALYTICS_CLIENT_SECRET);

            $objGoogleClient->setRedirectUri(GANALYTICS_REDIRECT_URI);

            $objGoogleClient->setScopes(array (
                'https://www.googleapis.com/auth/analytics',
                'https://www.googleapis.com/auth/analytics.edit',
                'https://www.googleapis.com/auth/analytics.readonly',
            ));

            if (
                isset ($_SESSION['token_json_goog'])
            ) {
                $objGoogleClient->setAccessToken($_SESSION['token_json_goog']);
            } # if

            if (
                $objGoogleClient->getAccessToken()
            ) {

                # !!!!!!!
                $fltr = & $_SESSION['arrAnltcsAccFilters'][$idAccount][$idFilterAcc];

                // Construct the filter expression object.
                if (
                    isset ($fltr['excludeDetails'])
                ) {
                    $dtls = & $fltr['excludeDetails'];
                } # if
                elseif (
                    isset ($fltr['includeDetails'])
                ) {
                    $dtls = & $fltr['includeDetails'];
                } # elseif
                elseif (
                    isset ($fltr['lowercaseDetails'])
                ) {
                    $dtls = & $fltr['lowercaseDetails'];
                } # elseif
                elseif (
                    isset ($fltr['uppercaseDetails'])
                ) {
                    $dtls = & $fltr['uppercaseDetails'];
                } # elseif
                elseif (
                    isset ($fltr['searchAndReplaceDetails'])
                ) {
                    $dtls = & $fltr['searchAndReplaceDetails'];
                } # elseif
                elseif (
                    isset ($fltr['advancedDetails'])
                ) {
                    $dtls = & $fltr['advancedDetails'];
                } # elseif
                $details = new Google_Service_Analytics_FilterExpression();
                $details->setField($dtls['field']);
                $details->setMatchType($dtls['matchType']);
                $details->setExpressionValue($dtls['expressionValue']);
                $details->setCaseSensitive( (bool)$dtls['caseSensitive'] );

                // Construct the filter and set the details.
                $filter = new Google_Service_Analytics_Filter();
                $filter->setName($strNewName);
                $filter->setType($fltr['type']);
                if (
                    isset ($fltr['excludeDetails'])
                ) {
                    $filter->setExcludeDetails($details);
                } # if
                elseif (
                    isset ($fltr['includeDetails'])
                ) {
                    $filter->setIncludeDetails($details);
                } # elseif
                elseif (
                    isset ($fltr['lowercaseDetails'])
                ) {
                    $filter->setLowercaseDetails($details);
                } # elseif
                elseif (
                    isset ($fltr['uppercaseDetails'])
                ) {
                    $filter->setUppercaseDetails($details);
                } # elseif
                elseif (
                    isset ($fltr['searchAndReplaceDetails'])
                ) {
                    $filter->setSearchAndReplaceDetails($details);
                } # elseif
                elseif (
                    isset ($fltr['advancedDetails'])
                ) {
                    $filter->setAdvancedDetails($details);
                } # elseif
                
                $objService = new Google_Service_Analytics($objGoogleClient);

                $mixAnswerGoogle = $objService->management_filters->insert($idAccountTo, $filter);

                if (
                    isset ($_SESSION['newFilterName'])
                ) {
                    unset ($_SESSION['newFilterName']);
                } # if

                $_SESSION['textMessage'] = 'The filter is copied, check it should appear in Google Analytics interface of destination account';

                # ко View привязываем (((
                if (
                    isset ($_POST['views'])
                &&
                    count($_POST['views'])
                ) {
                    foreach ( $_POST['views'] as $v ) {

                        $objPFL = new Google_Service_Analytics_ProfileFilterLink();

                        $objFR = new Google_Service_Analytics_FilterRef();
                        $objFR->setId($mixAnswerGoogle->id); # filterRef.id

                        $objPFL->setFilterRef($objFR);

                        $objService->management_profileFilterLinks->insert(
                            $idAccountTo,
                            $_SESSION['arrAllViews'][$v]['idProp'],
                            $v,
                            $objPFL
                        );

                    } # foreach
                } # if
                # ))) ко View привязываем

            } # if

        } # try
        catch (apiServiceException $e) {
            // Error from the API.
            $_SESSION['textMessage'] = 'Error (api, code ' . $e->getCode() . '): ' . $e->getMessage();
        } # catch
        catch (Exception $e) {
            // General error.
            $_SESSION['textMessage'] = 'Error (general): ' . $e->getMessage();
        } # catch

    } # if

    header('Location: ' . HTTP_ABS_PATH_TO_HOST_ROOT . '/?s=' . urlencode($s) . '&s2=' . urlencode($s2) . '&id=' . urlencode($idAccount) . '&fid=' . urlencode($idFilterAcc) . '&idto=' . urlencode($idAccountTo));
    exit (); # !!!

} # if

# list of all views (((

$_SESSION['arrAllViews'] = array ();

try {

    $objGoogleClient = new Google_Client();

    $objGoogleClient->setClientId(GANALYTICS_CLIENT_ID);

    $objGoogleClient->setClientSecret(GANALYTICS_CLIENT_SECRET);

    $objGoogleClient->setRedirectUri(GANALYTICS_REDIRECT_URI);

    $objGoogleClient->setScopes(array (
        'https://www.googleapis.com/auth/analytics',
        'https://www.googleapis.com/auth/analytics.edit',
        'https://www.googleapis.com/auth/analytics.readonly',
    ));

    if (
        isset ($_SESSION['token_json_goog'])
    ) {
        $objGoogleClient->setAccessToken($_SESSION['token_json_goog']);
    } # if

    if (
        $objGoogleClient->getAccessToken()
    ) {

        $objService = new Google_Service_Analytics($objGoogleClient);

        $profiles = $objService->management_profiles->listManagementProfiles($idAccountTo, '~all');

        if (
            count($profiles)
        ) {
            foreach ($profiles->getItems() as $profile) {

                $idView = $profile->getId();

                $arrOne = array (
                    'idAcc' => $profile->getAccountId(),
                    'idProp' => $profile->getWebPropertyId(),
                    'idView' => $idView,
                    'name' => $profile->getName(),
                    'type' => $profile->getType(),
                );

              $_SESSION['arrAllViews'][$idView] = $arrOne;

            }
        } # if

    } # if

} catch (apiServiceException $e) {
    #
} catch (apiException $e) {
    #
}

# ))) list of all views

$strNewFilterName = 'Копия ' . $_SESSION['arrAnltcsAccFilters'][$idAccount] [$idFilterAcc]['name'];
if (
    isset ($_SESSION['newFilterName'])
) {
    $strNewFilterName = $_SESSION['newFilterName'];
    unset ($_SESSION['newFilterName']);
} # if

$txtMessage = '';
if (
    isset ($_SESSION['textMessage'])
) {
    $txtMessage = $_SESSION['textMessage'];
    unset ($_SESSION['textMessage']);
} # if

$htmlTitleElement = 'Name of new filter &middot; Administration';

?>