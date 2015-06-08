<?php if(!defined('ANTI_DIRECT'))exit;

# Developer settings.

ini_set('html_errors', 0);

ini_set('display_errors', intval( time() < mktime(4,0,0,1,15+1,2015) )); # temporary ON

error_reporting(E_ALL);

@ini_set('magic_quotes_runtime', 0);

# http paths (((

# http absolute path to the host root (without trailing slash)
define('HTTP_ABS_PATH_TO_HOST_ROOT', SITE_HTTP_HOST . HRFS);

# ))) http paths

# filesystem paths (((

# filesystem absolute path to the site root (with trailing slash)
# !!! place this line only in some file from the site root !!!
define('FS_ABS_PATH_TO_SITE_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);

define('FS_ABS_PATH_TO_FUNCSLIBS', FS_ABS_PATH_TO_SITE_ROOT . 'php' . DIRECTORY_SEPARATOR . 'funcs_libs' . DIRECTORY_SEPARATOR);

define('FS_ABS_PATH_TO_SECTIONS', FS_ABS_PATH_TO_SITE_ROOT . 'php' . DIRECTORY_SEPARATOR . 'sections' . DIRECTORY_SEPARATOR);

# ))) filesystem paths

set_include_path(FS_ABS_PATH_TO_FUNCSLIBS . 'google-api-php-clientNEW/src/' . PATH_SEPARATOR . get_include_path());

ini_set('session.save_path',        '');  # default ""
ini_set('session.gc_maxlifetime',   1440);  # default 1440
ini_set('session.cookie_lifetime',  0);  # default 0
ini_set('session.cookie_httponly',  '');  # default ""
ini_set('session.use_cookies',      1);        # default 1
ini_set('session.use_only_cookies', 1);        # default 1
ini_set('session.cookie_path',      HRFS.'/'); # default "/"

# sections and sub-sections nick names 
$arrAvailableS = array (

    # Special section of the website, keep nick name! 
    'administration' => array (

        # special subsections, keep nick names! (((
        'authorization',
        'authorization_processing',
        'logout',
        # ))) special subsections, keep nick names!

        'relations',

        'step1togoogle',
        'step2accountfrom',
        'step3whatfilter',
        'step4accountto',
        'step5filtname',

    ),

    # special subsections, keep nick names! (level2 in home excluded)!  (((

    'connect_error' => array (
    ),

    'oauthgoogle' => array (
    ),

    # ))) special sections, keep nick names!

/*
    Example:
    'level1' => array (
        'level11',
        'level12',
    ),
*/

);

define('CHARSET', 'utf-8');

mb_internal_encoding(CHARSET);
mb_regex_encoding(CHARSET);

define('SALT_BROWSER_FINGERPRINT', '78C5M38T593XJ');

define('SALT_PASSWORD_HASH', '`'.'GCM7RHK94869CYME');

date_default_timezone_set('Europe/Moscow'); # например, 'Europe/Moscow' или 'Europe/Kiev'

define('GANALYTICS_REDIRECT_URI', SITE_HTTP_HOST . HRFS . '/?s=oauthgoogle');

?>