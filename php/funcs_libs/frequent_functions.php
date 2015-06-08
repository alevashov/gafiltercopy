<?php if(!defined('ANTI_DIRECT'))exit;

#-----------
function h($string, $boolDoubleEncode = TRUE) {
# Сокращённая запись для правильного htmlspecialchars.

    static $boolFourthParamAvailable = NULL;
    if (
        ! isset ($boolFourthParamAvailable)
    ) {
        $boolFourthParamAvailable = version_compare(PHP_VERSION, '5.2.3', '>=');
    } # if

    if (
        $boolFourthParamAvailable
    ) {
        return htmlspecialchars($string, ENT_QUOTES, CHARSET, $boolDoubleEncode);
    } # if
    else {
        return htmlspecialchars($string, ENT_QUOTES, CHARSET);
    } # else

} # function
#-----------

#-----------
function nh($s) {
    return nl2br(h($s));
} # function
#-----------

#-----------
function ss($str) {
    if ( get_magic_quotes_gpc() || ini_get('magic_quotes_sybase') ) {
        return stripslashes($str);
    } # if
    return $str;
} # function
#-----------

#-----------
function j($s) {
    return str_replace(
        array("\\",   "'",  '"',   "\r\n",    "\n"),
        array("\\\\", "\'", '\"',  "\\n\\\r", "\\n\\\n"),
        $s
    );
} # function
#-----------

#-----------
function databaseErrorHandler($htmlMessage, $arrInfo) {

    global $s, $boolConnectionPrepared;

    $boolConnectionPrepared = FALSE;

    # если использовалась @, — ничего не делать
    if (
        ! error_reporting()
    ) {
        return;
    } # if

    if (
        $s != 'connect_error'
    ) {
        header('HTTP/1.0 307 Temporary redirect');
        header('Location: ' . HTTP_ABS_PATH_TO_HOST_ROOT . '/?s=connect_error');
        exit (); # !!!
    } # if

} # function
#-----------

#-----------
function fncMailHtml($to, $subject, $body, $from_email, $from_name) {

    $charset = CHARSET;

    # anti injection (
    foreach ( array ('to', 'subject', 'from_email', 'from_name') as $varName ) {
        if (
            FALSE !== strpos(${$varName}, "\n")
        ||
            FALSE !== strpos(${$varName}, "\r")
        ) {
            return FALSE;
        } # if
    } # foreach
    # ) anti injection
/*
	return mail(
        $to,
        '=?' . $charset . '?B?' . base64_encode($subject) . '?=',
        $body,
        'From: ' . $from . "\r\n" .
        'Reply-To: ' . $from . "\r\n" .
        'X-Priority:' . '3' . "\r\n" .
        'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/' . 'html' . '; charset="' . $charset . '"'
    );
*/
    $objMail = new PHPMailer();

    $objMail->CharSet = $charset;

    $objMail->SetFrom   ($from_email, $from_name);
    $objMail->AddReplyTo($from_email, $from_name);

    $objMail->AltBody = 'To view the message, please use an HTML compatible email viewer.';

    $objMail->AddAddress($to);

    $objMail->Subject = $subject;

#    $body = eregi_replace("[\]", '', $body); # ???
    
    $objMail->MsgHTML($body);

    if (
        $objMail->Send()
    ) {
        return TRUE;
    } # if
    else {
        return $objMail->ErrorInfo;
    } # else

} # function
#-----------

#-----------
function fncGetBrowserFingerprint() {

    return
        md5(
            @$_SERVER['HTTP_USER_AGENT'] .
            SALT_BROWSER_FINGERPRINT
        )
    ;

} # function
#-----------

?>