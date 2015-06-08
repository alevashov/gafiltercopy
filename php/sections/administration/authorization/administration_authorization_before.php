<?php if(!defined('ANTI_DIRECT'))exit;

$boolWrongAuthPair =
    isset ($_GET['try'])
&&
    'again' == $_GET['try']
;

$htmlTitleElement = Authorisation &middot; Administration';

?>