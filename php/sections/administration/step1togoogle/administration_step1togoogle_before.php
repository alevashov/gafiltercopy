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

header('Location: ' . $client->createAuthUrl());
exit (); # !!!

?>