<?
if(!defined("F_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }
?><html>
<head>
<META HTTP-EQUIV="Content-Type" Content="text/html;Charset=Windows-1251">
<link href="theme/<?echo$skin;?>/main.css" rel="stylesheet" type="text/css">
<script id="JS"></script>
<script language="JavaScript">
<!--
document.getElementById("JS").text = window.parent.document.getElementById("JS_all").text;
function tester()
{
 var new_send = "new";
 var last_send = "last";
 //document.cookie="color="+document.send_form.color.value+";"
 var nwmess = document.send_form.message.value;
 if(nwmess != "")
 {
  new_send = document.send_form.to.value+nwmess;
  if(new_send == last_send)
  {
   alert("<?echo$lang['double'];?>");
   return false;
  }
  else
  {
   last_send = new_send;
   return true;
  }
 }
 else
 {
  alert("<?echo$lang['notmess'];?>");
  return false;
 }
}

function submited(type)
{
 var test= tester();
 if (test == true)
 {
  window.parent.frames['hidden'].document.location.href='./blank.html';
  document.getElementById('s').disabled=true;
  document.getElementById('send_form').submit();
  document.send_form.new_mess.value='all';
  mess_focus();
 }
 return false;
}
-->
</script>
</head>
<body bgcolor="<?echo$color['bgcolor2'];?>" onload="mess_focus();" leftMargin="0" topMargin="1" rightMargin="0" marginheight="0" marginwidth="0" <?echo$body;?>>
<span id="muteremtime" style="color:<?echo$color['bancolor'];?>;font-family:Verdana, Geneva, Arial, Helvetica, sans-serif; font-size:12px;"></span>
<span id="muteremtime2" style="color:<?echo$color['bancolor'];?>;font-family:Verdana, Geneva, Arial, Helvetica, sans-serif; font-size:12px;"></span>
<table border="0" bgcolor="<?echo$color['bgcolor2'];?>" cellpadding="1" cellspacing="0" align="center" style="width:100%;" width="100%">
<form name="send_form" id="send_form" method="post" target="hidden" action="hidden.php" onSubmit="return tester();"><input name="new_mess" value="all" type="hidden">
<tr valign="bottom"><td style="width: 135;" nowrap><input name="to" style="width:130px; cursor:hand;" title="<?echo$lang['clear'];?>" value="" onclick="JavaScript: document.send_form.to.value='';" readonly></td>
<td style="width: 130;" nowrap><select name="color" onchange="mess_focus();" style="width: 130;"><?echo$opt_cs;?></select>&nbsp;</td>
<td><input type="text" id="message" name="message" width="100%" style="width: 100%" maxlength="400" onkeypress="if(event.keyCode==13){submited();}"></td>
<td style="width:86;" nowrap><button name="s" id="s" onclick="submited(); return false;"><?echo$lang['send'];?></button></td>
<td style="width:96;" nowrap><input type="checkbox" name="private" value="1"> <span class="txt"><?echo$lang['private'];?></span></td></form>
<td style="width:86;" nowrap><button name="l" id="l" onclick="logout();"><?echo$lang['exit'];?></button></td>
</tr></table>
</body>
</html>