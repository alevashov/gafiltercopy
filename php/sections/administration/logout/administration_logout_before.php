<?php if(!defined('ANTI_DIRECT'))exit;

# removed 31.07.2013 17:06
#@session_start();

$_SESSION['administration']['blnAuthOk'] = FALSE;
unset ($_SESSION['administration']['blnAuthOk']);

unset ($_SESSION['administration']['blnIsSuperadmin']);

if (
    isset ($_SESSION['administration']['idAuthUser'])
) {
    unset ($_SESSION['administration']['idAuthUser']);
} # if

unset ($_SESSION['administration']['strBrowserFingerprint']);

if (
    isset ($_SESSION['arrAnltcsAccounts'])
) {
    unset ($_SESSION['arrAnltcsAccounts']);
} # if

if (
    isset ($_SESSION['arrAnltcsAccFilters'])
) {
    unset ($_SESSION['arrAnltcsAccFilters']);
} # if

header('Location: ' . HTTP_ABS_PATH_TO_HOST_ROOT . '/?s=administration');
exit (); # !!!

?>