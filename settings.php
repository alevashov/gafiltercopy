<?php if(!defined('ANTI_DIRECT'))exit;

# Installation settings (for business user).

define('SITE_HTTP_HOST', 'http://hostname.com'); # for example , 'http://example.com' (no slash at the end)

define('HRFS', ''); # for example, '' (if in root), or '/folder', or '/folder1/folder2' (no slash at the end)

define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'username');
define('MYSQL_PWD',  'password');
define('MYSQL_DB',   'db-name');

define('SUPERADMIN_LOGIN',    'login');
define('SUPERADMIN_PASSWORD', 'password');

define('EMAIL_FOR_REPORT', 'my@email.com');


define('GANALYTICS_CLIENT_ID', 'yourdata');
define('GANALYTICS_CLIENT_SECRET', 'yourdata');

define('GOOGLE_SERVICE_ACCOUNT_CLIENT_ID', 'yourdata');
define('GOOGLE_SERVICE_ACCOUNT_EMAIL_ADDRESS', 'yourdata');
define('GOOGLE_SERVICE_ACCOUNT_PKEY_FNAME', 'yourdata');

?>