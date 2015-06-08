<?php if(!defined('ANTI_DIRECT'))exit;

# Output preparation (html content).

if (
    FALSE
) {
    # "www. not required"
    if (
        'www.' == mb_substr($_SERVER['HTTP_HOST'], 0, 4)
    ) {
        header('HTTP/1.0 301 Moved Permanently');
        header('Location: ' . SITE_HTTP_HOST . $_SERVER['REQUEST_URI']);
        exit (); # !!!
    } # if
} # if

# functions and libs (((

require FS_ABS_PATH_TO_FUNCSLIBS . 'frequent_functions.php';

require FS_ABS_PATH_TO_FUNCSLIBS . 'DbSimple_Koterov' . DIRECTORY_SEPARATOR . 'Generic.php';

require FS_ABS_PATH_TO_FUNCSLIBS . 'PHPMailer_v5.0.2' . DIRECTORY_SEPARATOR . 'class.phpmailer.php';

# ))) functions and libs

# define website section and subsection to display (((

$s  = 'administration';
$s2 = 'relations';
if (
    isset ($_REQUEST['s'])
&&
    isset ($arrAvailableS[ $_REQUEST['s'] ])
) {
    $s = $_REQUEST['s'];
    if (
        $s != 'administration'
    ) {
        $s2 = NULL;
    } # if
} # if
if (
    isset ($_REQUEST['s2'])
&&
    in_array($_REQUEST['s2'], $arrAvailableS[$s])
) {
    $s2 = $_REQUEST['s2'];
} # if

# ))) define website section and subsection to display

if (
    $s != 'administration'
||
    ! in_array($s2, array ('authorization', 'logout'))
) {
    # DB connection  (((

    $boolConnectionPrepared = TRUE; # default

    $objDb =
        @ # !!!
        DbSimple_Generic::connect('mysql://' . MYSQL_USER . ':' . MYSQL_PWD . '@' . MYSQL_HOST . '/' . MYSQL_DB . '?charset=' . str_replace('-', '', CHARSET))
    ;

    $objDb->setErrorHandler('databaseErrorHandler'); # трогает $boolConnectionPrepared

    if (
        ! $boolConnectionPrepared
    &&
        $s != 'connect_error'
    ) {
        header('HTTP/1.0 307 Temporary redirect');
        header('Location: ' . HTTP_ABS_PATH_TO_HOST_ROOT . '/?s=connect_error');
        exit (); # !!!
    } # if
    elseif (
        $boolConnectionPrepared
    &&
        'connect_error' == $s
    ) {
        header('Location: ' . HTTP_ABS_PATH_TO_HOST_ROOT . '/');
        exit (); # !!!
    } # elseif

    # ))) DB connection
} # if

if (
    TRUE
) {
    header('Content-Type: text/html; charset=' . CHARSET);
} # if

$htmlTitleElement     = ''; # default
$htmlMetaDescrContent = ''; # default
$htmlMetaKeywrContent = ''; # default

@session_start();

if (
    'administration' == $s
&&
    ! in_array($s2, array ('authorization', 'authorization_processing', 'logout'))
) {

    # only admin with correct credentials allows (((
    if (
        ! (
            isset (
                $_SESSION['administration']['blnAuthOk'],
                $_SESSION['administration']['strBrowserFingerprint']
            )
        &&
            $_SESSION['administration']['blnAuthOk']
        &&
            $_SESSION['administration']['strBrowserFingerprint'] == fncGetBrowserFingerprint()
        )
    ) {

        # store return point (((

        @ session_start();

        if (
            isset ($_SESSION['administration']['strBackToRequestUri'])
        ) {
            unset ($_SESSION['administration']['strBackToRequestUri']);
        } # if

        if (
            isset ($_SERVER['REQUEST_URI'])
        &&
            $_SERVER['REQUEST_URI'] != ''
        ) {
            $_SESSION['administration']['strBackToRequestUri'] = $_SERVER['REQUEST_URI'];
        } # if

        # ))) store return point

        header('Location: ' . HTTP_ABS_PATH_TO_HOST_ROOT . '/?s=administration&s2=authorization');
        exit (); # !!!

    } # if
    # ))) only admin users allowed

    # plus 'regular' user is allowed in some sections (((
    if (
        (
            ! isset ($_SESSION['administration']['blnIsSuperadmin'])
        ||
            ! $_SESSION['administration']['blnIsSuperadmin']
        )
    &&
        isset ($s2)
    &&
        ! in_array($s2, array ())
    ) {
        header('Location: ' . HTTP_ABS_PATH_TO_HOST_ROOT . '/?s=administration');
        exit (); # !!!
    } # if
    # ))) plus 'regular' user is allowed in some sections

} # if

if (
    'administration' == $s
&&
    in_array($s2, array ('relations'))
) {
    # for paging
    require FS_ABS_PATH_TO_FUNCSLIBS . 'clsPagination.php';
} # if

# preparing output (HTML) of required section/subsection of the website 

include FS_ABS_PATH_TO_SECTIONS .
    $s . DIRECTORY_SEPARATOR . ( isset ($s2) ? $s2.DIRECTORY_SEPARATOR : '' ) .
    $s . '_' . ( isset ($s2) ? $s2.'_' : '' ) . 'before.php'
;

?>