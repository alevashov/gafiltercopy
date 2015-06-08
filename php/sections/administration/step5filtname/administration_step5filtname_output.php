<?php if(!defined('ANTI_DIRECT'))exit;

require FS_ABS_PATH_TO_SECTIONS . 'administration' . DIRECTORY_SEPARATOR .
    '_inc_administration_output_start.php';

?>

<p><b>Name of new filter in Destination Account &laquo;<?php echo h($_SESSION['arrAnltcsAccounts'][$idAccountTo]['name']) ?>&raquo;</b>&nbsp; <a href="<?php echo h(HRFS) ?>/?s=<?php echo h(urlencode($s)) ?>&amp;s2=step4accountto&amp;id=<?php echo h(urlencode($idAccount)) ?>&amp;fid=<?php echo h(urlencode($idFilterAcc)) ?>"><small>&larr;&nbsp;return</small></a></p>

<form action="<?php echo h(HRFS) ?>/" method="POST">
<input type="hidden" name="s" value="<?php echo h($s) ?>">
<input type="hidden" name="s2" value="<?php echo h($s2) ?>">
<input type="hidden" name="id" value="<?php echo h($idAccount) ?>">
<input type="hidden" name="fid" value="<?php echo h($idFilterAcc) ?>">
<input type="hidden" name="idto" value="<?php echo h($idAccountTo) ?>">

<p><input type="text" name="new_name" id="new_name" value="<?php echo h($strNewFilterName) ?>" size="<?php echo h(4+max(24, mb_strlen($strNewFilterName))) ?>"></p>

<?php if (
    count($_SESSION['arrAllViews'])
) { ?>
    <p>(Not mandatory) Views:<br>
    <?php foreach ( $_SESSION['arrAllViews'] as $arrView ) { ?>
        <label for="views_<?php echo h($arrView['idView']) ?>"><input type="checkbox" name="views[]" id="views_<?php echo h($arrView['idView']) ?>" value="<?php echo h($arrView['idView']) ?>">&nbsp;<?php echo h($arrView['name']) ?></label><br>
    <?php } # foreach ?>
    </p>
<?php } # if ?>

<p><input type="submit" value="Copy filter"></p>

<p style="color: #FF0000;"><?php echo nh($txtMessage) ?></p>

</form>

<?php

require FS_ABS_PATH_TO_SECTIONS . 'administration' . DIRECTORY_SEPARATOR .
    '_inc_administration_output_stop.php';

?>