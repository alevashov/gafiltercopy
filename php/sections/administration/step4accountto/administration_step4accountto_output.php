<?php if(!defined('ANTI_DIRECT'))exit;

require FS_ABS_PATH_TO_SECTIONS . 'administration' . DIRECTORY_SEPARATOR .
    '_inc_administration_output_start.php';

?>

<p><b>Select destination account, where to copy a filter &laquo;<?php echo h(
    $_SESSION['arrAnltcsAccFilters'][$idAccount][$idFilterAcc]['name']
) ?>&raquo;</b>&nbsp; <a href="<?php echo h(HRFS) ?>/?s=<?php echo h(urlencode($s)) ?>&amp;s2=step3whatfilter&amp;id=<?php echo h(urlencode($idAccount)) ?>"><small>&larr;&nbsp;return</small></a></p>

<?php if (
    ! count($_SESSION['arrAnltcsAccounts'])
) { ?>
    <p>No single account available. Can not make selection.</p>
<?php } # if
else { ?>
    <?php foreach ( $_SESSION['arrAnltcsAccounts'] as $k => $v ) { ?>
        <p><a href="<?php echo h(HRFS) ?>/?s=<?php echo h(urlencode($s)) ?>&amp;s2=step5filtname&amp;id=<?php echo h(urlencode($idAccount)) ?>&amp;fid=<?php echo h(urlencode($idFilterAcc)) ?>&amp;idto=<?php echo h(urlencode($v['id'])) ?>">id&nbsp;<?php echo h($v['id']) ?>.&nbsp;<?php echo h($v['name']) ?></a></p>
    <?php } # foreach ?>
<?php } # else ?>

<?php

require FS_ABS_PATH_TO_SECTIONS . 'administration' . DIRECTORY_SEPARATOR .
    '_inc_administration_output_stop.php';

?>