<?php if(!defined('ANTI_DIRECT'))exit;

require FS_ABS_PATH_TO_SECTIONS . 'administration' . DIRECTORY_SEPARATOR .
    '_inc_administration_output_start.php';

?>

<p><b>Select filter to copy from the Account &laquo;<?php echo h($_SESSION['arrAnltcsAccounts'][$idAccount]['name']) ?>&raquo;</b>&nbsp; <a href="<?php echo h(HRFS) ?>/?s=<?php echo h(urlencode($s)) ?>&amp;s2=step2accountfrom"><small>&larr;&nbsp;return</small></a></p>

<?php if (
    $htmlReport != ''
) { ?>
    <p><?php echo $htmlReport; ?></p>
<?php } # if ?>

<?php if (
    ! count($_SESSION['arrAnltcsAccFilters'][$idAccount])
) { ?>
    <p>No filters available. Can't do selection.</p>
<?php } # if
else { ?>
    <?php foreach ( $_SESSION['arrAnltcsAccFilters'][$idAccount] as $k => $v ) { ?>
        <p><a href="<?php echo h(HRFS) ?>/?s=<?php echo h(urlencode($s)) ?>&amp;s2=step4accountto&amp;id=<?php echo h(urlencode($idAccount)) ?>&amp;fid=<?php echo h(urlencode($v['id'])) ?>">id&nbsp;<?php echo h($v['id']) ?>.&nbsp;<?php echo h($v['name']) ?> (тип <?php echo h($v['type']) ?>)</a></p>
    <?php } # foreach ?>
<?php } # else ?>

<?php

require FS_ABS_PATH_TO_SECTIONS . 'administration' . DIRECTORY_SEPARATOR .
    '_inc_administration_output_stop.php';

?>