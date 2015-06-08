<?php if(!defined('ANTI_DIRECT'))exit;

# HTML output.

?><html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo h(CHARSET) ?>">
<meta http-equiv="Content-Script-Type" content="text/javascript">

<title><?php echo $htmlTitleElement ?></title>

<?php if (
    $htmlMetaDescrContent != ''
) { ?>
    <meta name="description" content="<?php echo $htmlMetaDescrContent ?>">
<?php } # if ?>
<?php if (
    $htmlMetaKeywrContent != ''
) { ?>
    <meta name="keywords" content="<?php echo $htmlMetaKeywrContent ?>">
<?php } # if ?>

<?php if (
    'administration' == $s
) { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo h(HRFS) ?>/css/administration.css">
<?php } # if
else { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo h(HRFS) ?>/css/index.css">
<?php } # else ?>

<!-- <link rel="shortcut icon" href="<?php echo h(HRFS) ?>/favicon.ico" type="image/x-icon"> -->

<script type="text/javascript" src="<?php echo h(HRFS) ?>/jvscript/jquery/jquery-1.7.2.min.js"></script>

</head>

<body><?php

# html output of required website section/subsection  

include FS_ABS_PATH_TO_SECTIONS .
    $s . DIRECTORY_SEPARATOR . ( isset ($s2) ? $s2.DIRECTORY_SEPARATOR : '' ) .
    $s . '_' . ( isset ($s2) ? $s2.'_' : '' ) . 'output.php'
;

?></body>

</html>
