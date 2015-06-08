<?php if(!defined('ANTI_DIRECT'))exit;

if (
    isset ($_POST['login'], $_POST['password'])
) {

    $login_entered = trim(ss($_POST['login']));
    $passw_entered = ss($_POST['password']);

    # checking via database
    $arrAuth = @$objDb->selectRow('
        SELECT
            id
        FROM
            administration_users
        WHERE
            login = ?
        AND
            password_hash = ?
    ',
        $login_entered,
        md5($passw_entered . SALT_PASSWORD_HASH)
    );
    $boolAuthByDb = 1 == count($arrAuth);

    if (
        $boolAuthByDb
    ||
        (
            SUPERADMIN_LOGIN == $login_entered
        &&
            SUPERADMIN_PASSWORD == $passw_entered
        )
    ) {

# removed 31.07.2013 17:06
#        @session_start();

        $_SESSION['administration']['blnAuthOk'] = TRUE;
        $_SESSION['administration']['strBrowserFingerprint'] = fncGetBrowserFingerprint();

        $_SESSION['administration']['blnIsSuperadmin'] =
            ! $boolAuthByDb
        ;

        if (
            ! $_SESSION['administration']['blnIsSuperadmin']
        ) {
            $_SESSION['administration']['idAuthUser'] = $arrAuth['id'];
        } # if

        if (
            isset ($_SESSION['administration']['strBackToRequestUri'])
        ) {
            # go where remembered

            $tmp = $_SESSION['administration']['strBackToRequestUri'];

            unset ($_SESSION['administration']['strBackToRequestUri']);

            header('Location: ' . SITE_HTTP_HOST . $tmp);
            exit (); # !!!
        } # if

        header('Location: ' . HTTP_ABS_PATH_TO_HOST_ROOT . '/?s=administration');
        exit (); # !!!

    } # if

} # if

header('Location: ' . HTTP_ABS_PATH_TO_HOST_ROOT . '/?s=administration&s2=authorization&try=again');
exit (); # !!!

?>