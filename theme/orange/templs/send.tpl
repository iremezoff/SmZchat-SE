<table border="0" bgcolor="<?echo$color['bgcolor2'];?>" cellpadding="1" cellspacing="0" align="center" style="width:100%;" width="100%">
<form name="send_form" id="send_form" method="post" target="hidden" action="hidden.php" onSubmit="return tester();"><input name="new_mess" value="all" type="hidden">
<tr valign="bottom"><td style="width: 135;" nowrap><input name="to" style="width:130px; cursor:hand;" title="<?echo$lang['clear'];?>" value="" onclick="JavaScript: document.send_form.to.value='';" readonly></td>
<td style="width: 130;" nowrap><select name="color" style="width: 130;"><?echo$opt_cs;?></select>&nbsp;</td>
<td><input type="text" id="message" name="message" width="100%" style="width: 100%" maxlength="400" onkeypress="if(event.keyCode==13){submited();}"></td>
<td style="width:86;" nowrap><button name="s" id="s" onclick="submited(); return false;"><?echo$lang['send'];?></button></td>
<td style="width:96;" nowrap><input type="checkbox" name="private" value="1"> <span class="txt"><?echo$lang['private'];?></span></td></form>
<td style="width:86;" nowrap><button name="l" id="l" onclick="logout();"><?echo$lang['exit'];?></button></td>
</tr></table>