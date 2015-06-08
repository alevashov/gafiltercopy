<?php if(!defined('ANTI_DIRECT'))exit; ?>

<form action="<?php echo h(HRFS) ?>/" method="post">
<input type="hidden" name="s" value="<?php echo h($s) ?>">
<input type="hidden" name="s2" value="authorization_processing">

<table align="center" class="authForm">

<tr>
<td align="right">логин</td>
<td><input type="text" name="login" id="login" value=""></td>
</tr>

<tr>
<td align="right">пароль</td>
<td><input type="password" name="password" value=""></td>
</tr>

<tr>
<td align="right">&nbsp;</td>
<td><input type="submit" value="Войти в систему" class="authInpSbm"></td>
</tr>

<?php if (
    $boolWrongAuthPair
) { ?>
    <tr>
    <td>&nbsp;</td>
    <td class="err">Enter correct<br>login and&nbsp;password.</td>
    </tr>
<?php } # if ?>

</table>

</form>
<script type="text/javascript">
    $('#login').focus();
</script>
