<?
if(!defined("F_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }
$_SESSION['upd']=0;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="theme/<?echo$skin;?>/main.css" rel="stylesheet" type="text/css">
<script id="JS"></script>
<script language="JavaScript">
document.getElementById("JS").text = window.parent.document.getElementById("JS_all").text;

function update_smiley(newimage)
 {
 document.x_image.src = "xstatus/" + newimage;
 }
</script>
</head>
<body bgcolor="<?echo$color['bgcolor'];?>" leftMargin="0" topMargin="0" rightMargin="0" marginheight="0" marginwidth="0">
<table  style="width:100%;" width="100%" align="center" border="0" cellspacing="1" bgcolor="<?echo$color['tableh'];?>" cellpadding="1">
<tr>
<td  valign="middle" bgcolor="<?echo$color['tdbgcolor'];?>" style="padding:3px; font-size:10px; font-family:Tahoma; color:<?echo$color['tdcolor'];?>; virtual-align:middle; Text-decoration: none; height:16; Letter-spacing: 2px;" nowrap>
<a href="#" onselectstart="return false;" onclick="menu_right('users');">
<img id="img_users" src="theme/<?echo$skin;?>/img/st_u.gif" border="0" style="padding-right:5px;"><?echo$lang['now'];?>:</a>
</td>
</tr>
<tr>
<td id="users" valign="top" align="left" bgcolor="<?echo$color['bgcolor'];?>" style="font-size:<?echo$font;?>; color:<?echo$color['tdcolor'];?>;">
<span class=txt2>сорт. по: <a href=# onclick="window.parent.frames['hidden'].location.href='hidden.php?sort=nick';">нику</a>, 
<a href=# onclick="window.parent.frames['hidden'].location.href='hidden.php?sort=sex';">полу</a>,
<a href=# onclick="window.parent.frames['hidden'].location.href='hidden.php?sort=default';">без</a></span>
<div name="usr" id="usr"><center>... Loading ...</center></div>
</td>
</tr>
</table>

<table  style="width:100%;" width="100%" align="center" border="0" cellspacing="1" bgcolor="<?echo$color['tableh'];?>" cellpadding="1">
<tr>
<td valign="middle" bgcolor="<?echo$color['tdbgcolor'];?>" style="padding:3px; font-size:10px; font-family:Tahoma; color:<?echo$color['tdcolor'];?>; virtual-align:middle; Text-decoration: none; height:16; Letter-spacing: 2px;" nowrap>
<a href="#" onselectstart="return false;" onclick="menu_right('status');">
<img id="img_status" src="theme/<?echo$skin;?>/img/st_d.gif" border="0" style="padding-right:5px;"><?echo$lang['status'];?>:</a>
</td>
</tr>
<tr id="status" style="display:none;">
<td valign="top" bgcolor="<?echo$color['bgcolor'];?>" style="font-size:<?echo$font;?>; color:<?echo$color['tdcolor'];?>;" align=center>
<form action="hidden.php" method=get target=hidden name=status>
<input type="hidden" name="ch_status" value="1">
<img name="x_image" src="xstatus/<?echo @mysql_result(mysql_query("select image from chat_xstatus where id='$_SESSION[status]'"),0,'image');?>" border="0">
<select name="setstatus" style="width:150px;font-size:8pt;" onchange="update_smiley(this.options[selectedIndex].value);">
<?
$query_x=mysql_query("select * from chat_xstatus order by id");
while($arr_x=mysql_fetch_array($query_x))
  {
  echo "<option value=\"$arr_x[image]\"";
  if($_SESSION['status']==$arr_x['id']) echo" selected";
  echo ">$arr_x[text]</option>";
  }
?>
</select><br>
<?if($_SESSION['slevel'][10]==1){?>Свой текст: <input type="text" name="xtext" id="xtext" style="width:100px"><br><?}?>
<input type="submit" value="Ok"  onclick="document.getElementById('status').style.display='none'">
</td>
</tr></form>
</table>

<table  style="width:100%;" width="100%" align="center" border="0" cellspacing="1" bgcolor="<?echo$color['tableh'];?>" cellpadding="1">
<tr>
<td  valign="middle" bgcolor="<?echo$color['tdbgcolor'];?>" style="padding:3px; font-size:10px; font-family:Tahoma; color:<?echo$color['tdcolor'];?>; virtual-align:middle; Text-decoration: none; height:16; Letter-spacing: 2px;" nowrap>
<a href="#" onselectstart="return false;" onclick="menu_right('rooms');">
<img id="img_rooms" src="theme/<?echo$skin;?>/img/st_d.gif" border="0" style="padding-right:5px;"><?echo$lang['rooms'];?>:</a>
</td>
</tr>
<tr id="rooms" style="display:none;">
<td valign="top" bgcolor="<?echo$color['bgcolor'];?>" style="font-size:<?echo$font;?>; color:<?echo$color['tdcolor'];?>;" align=center>
<form action="hidden.php" method=get target=hidden>
<input type="hidden" name="ch_room" value="1">
<select name="setroom" style="width:150px;font-size:8pt;">
<?
$query_rooms=mysql_query("select * from chat_rooms order by id");
while($array_rooms=mysql_fetch_array($query_rooms))
  {
  $total=mysql_num_rows(mysql_query("select * from chat_onliners where room='$array_rooms[id]'"));
  echo "<option value=\"$array_rooms[id]\"";
  if($_SESSION['room']==$array_rooms['id']) echo " selected";
  echo ">$array_rooms[name] ($total)</option>";
  }
?>
</select><br>
<button onclick="submit(); return false;"><?echo$lang['goto'];?></button>
</form></td>
</tr>
</td>
</tr>
</table>
<?
if($_SESSION['balls']>=500)
  {?>
<table  style="width:100%;" width="100%" align="center" border="0" cellspacing="1" bgcolor="<?echo$color['tableh'];?>" cellpadding="1">
<tr>
<td  valign="middle" bgcolor="<?echo$color['tdbgcolor'];?>" style="padding:3px; font-size:10px; font-family:Tahoma; color:<?echo$color['tdcolor'];?>; virtual-align:middle; Text-decoration: none; height:16; Letter-spacing: 2px;" nowrap>
<a href="#" onselectstart="return false;" onclick="menu_right('moder');">
<img id="img_moder" src="theme/<?echo$skin;?>/img/st_d.gif" border="0" style="padding-right:5px;"><?echo$lang['moder'];?>:</a>
</td>
</tr>
<tr id="moder" style="display:none;">
<td valign="top" bgcolor="<?echo$color['bgcolor'];?>" style="font-size:<?echo$font;?>; color:<?echo$color['tdcolor'];?>;" align=center>
<?if($_SESSION['balls']>=700){?>[ <a href="#" onclick="adm_ban('','');"><b><?echo$lang['addban2'];?></b></a> ]<br><?}?>
[ <a href="#" onclick="ban_list();"><b><?echo$lang['banlist'];?></b></a> ]<br>
[ <a href="#" onclick="ban_logs();"><b><?echo$lang['banlogs'];?></b></a> ]
</td>
</tr>
</table>
<?}?>

<table  style="width:100%;" width="100%" align="center" border="0" cellspacing="1" bgcolor="<?echo$color['tableh'];?>" cellpadding="1">
<tr>
<td  valign="middle" bgcolor="<?echo$color['tdbgcolor'];?>" style="padding:3px; font-size:10px; font-family:Tahoma; color:<?echo$color['tdcolor'];?>; virtual-align:middle; Text-decoration: none; height:16; Letter-spacing: 2px;" nowrap>
<a href="#" onselectstart="return false;" onclick="menu_right('other');">
<img id="img_other" src="theme/<?echo$skin;?>/img/st_d.gif" border="0" style="padding-right:5px;">Дополнительно:</a>
</td>
</tr>
<tr id="other" style="display:none;">
<td valign="top" bgcolor="<?echo$color['bgcolor'];?>" style="font-size:<?echo$font;?>; color:<?echo$color['tdcolor'];?>;" align=center>
[ <a href="#" onclick="window.open('rules.php','rules','width=700,height=450,top=0,left=0,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no');"><b>Правила</b></a> ]<br>
[ <a href="help.html" target="_blank"><b>Справка</b></a> ]<br>
[ <a href="#" onclick="window.parent.frames['hidden'].document.location.href='./blank.html'; window.parent.frames['messages'].document.getElementById('msgs').innerHTML='';"><b>Очистить сообщения</b></a> ]<br>
[ <a href="#" onclick="window.parent.frames['hidden'].document.location.href='./blank.html'; window.parent.frames['messages'].document.location.href='index.php?file=messages';"><b>Обновить сообщения</b></a> ]<br>
[ <a href="#" onclick="window.open('ign.php?mode=myign','myignn','width=800,height=700,top=0,left=0,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no');"><b>Игнорируемые</b></a> ]<br>
[ <a href="#" onclick="window.open('ign.php?mode=meign','meigns','width=530,height=700,top=0,left=0,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no');"><b>Игнорирующие</b></a> ]<br>
</td>
</tr>
</table>

<?
for($s=1;$s<=3;$s++)
{
if($_SESSION['slevel'][$s-1]==1)
{?>
<table  style="width:100%;" width="100%" align="center" border="0" cellspacing="1" bgcolor="<?echo$color['tableh'];?>" cellpadding="1">
<tr>
<td  valign="middle" bgcolor="<?echo$color['tdbgcolor'];?>" style="padding:3px; font-size:10px; font-family:Tahoma; color:<?echo$color['tdcolor'];?>; virtual-align:middle; Text-decoration: none; height:16; Letter-spacing: 2px;" nowrap>
<a href="#" onselectstart="return false;" onclick="menu_right('smiles<?echo$s;?>');">
<img id="img_smiles<?echo$s;?>" src="theme/<?echo$skin;?>/img/st_d.gif" border="0" style="padding-right:5px;"><?echo$lang['smiles'];?><?echo$s;?>:</a>
</td>
</tr>
<tr id="smiles<?echo$s;?>" style="display:none;">
<td valign="top" bgcolor="<?echo$color['bgcolor'];?>" style="font-size:<?echo$font;?>; color:<?echo$color['tdcolor'];?>;" align=center>
<table border=0 align=center width=100%><tr>
<?
$query_sm=mysql_query("select * from chat_smiles where level='$s' group by url");
$q=0;
while($array_sm=mysql_fetch_array($query_sm))
  {
  if($q%3==0 && $q!=0)
    {
    echo '</tr><tr>';
    }
  echo "<td align=\"center\"><a href=\"javascript:insSmile('$array_sm[code]');\"><img src=\"smiles/$array_sm[url]\" border=0></a></td>";
  $q++;
  }
?>
</tr>
</table></td>
</tr>
</table>
<?}
}?>

<?
if($_SESSION['slevel'][3]==1 || $_SESSION['slevel'][5]==1 || $_SESSION['slevel'][6]==1 || $_SESSION['slevel'][7]==1 || $_SESSION['slevel'][9]==1)
{?>
<table style="width:100%;" width="100%" align="center" border="0" cellspacing="1" bgcolor="<?echo$color['tableh'];?>" cellpadding="1">
<tr>
<td  valign="middle" bgcolor="<?echo$color['tdbgcolor'];?>" style="padding:3px; font-size:10px; font-family:Tahoma; color:<?echo$color['tdcolor'];?>; virtual-align:middle; Text-decoration: none; height:16; Letter-spacing: 2px;" nowrap>
<a href="#" onselectstart="return false;" onclick="menu_right('styles');">
<img id="img_styles" src="theme/<?echo$skin;?>/img/st_d.gif" border="0" style="padding-right:5px;">Стили:</a>
</td>
</tr>
<tr id="styles" style="display:none;">
<td valign="top" bgcolor="<?echo$color['bgcolor'];?>" style="font-size:<?echo$font;?>; color:<?echo$color['tdcolor'];?>;" align="center">
<div align="left">
<form action="hidden.php" method=get target=hidden>
<input type="hidden" name="ch_style" value="1">
<?if($_SESSION['slevel'][7]==1){?><a href="#" onclick="window.parent.frames['send'].document.send_form.message.value = '/beg ' + window.parent.frames['send'].document.send_form.message.value"><b>Бегущий текст</b></a><br><?}?>
<?if($_SESSION['slevel'][5]==1){?><a href="#" onclick="window.parent.frames['send'].document.send_form.message.value = '/b ' + window.parent.frames['send'].document.send_form.message.value"><b>Жирный</b></a><br><?}?>
<?if($_SESSION['slevel'][6]==1){?><a href="#" onclick="window.parent.frames['send'].document.send_form.message.value = '/i ' + window.parent.frames['send'].document.send_form.message.value"><b>Курсив</b></a><br><?}?>
<?if($_SESSION['slevel'][7]==1){?><a href="#" onclick="window.parent.frames['send'].document.send_form.message.value = '/big ' + window.parent.frames['send'].document.send_form.message.value"><b>Большой</b></a><br><?}?>
<?if($_SESSION['slevel'][9]==1){?><b>Жирность ника:</b> <input type="checkbox" name="setbnick" value="1"<?if(isset($_SESSION['bnick'])&&$_SESSION['bnick']==1)echo" checked";?>><br><?}?>
<?if($_SESSION['slevel'][3]==1){?><b>Цвет ника: </b><select name="setcnick" style="width: 130;"><?echo$opt_cs2;?></select><?}?>
</div>
<?if($_SESSION['slevel'][9]==1 || $_SESSION['slevel'][3]==1){?><button onclick="submit(); return false;"><?echo$lang['apply'];?></button><?}?>
</form>
</td>
</tr>
</table>
<?}?>

<?if($_SESSION['sid']>-1){?>
<table  style="width:100%;" width="100%" align="center" border="0" cellspacing="1" bgcolor="<?echo$color['tableh'];?>" cellpadding="1">
<tr>
<td  valign="middle" bgcolor="<?echo$color['tdbgcolor'];?>" style="padding:3px; font-size:10px; font-family:Tahoma; color:<?echo$color['tdcolor'];?>; virtual-align:middle; Text-decoration: none; height:16; Letter-spacing: 2px;" nowrap>
<a href="#" onselectstart="return false;" onclick="menu_right('opts');">
<img id="img_opts" src="theme/<?echo$skin;?>/img/st_d.gif" border="0" style="padding-right:5px;"><?echo$lang['option'];?>:</a>
</td>
</tr>
<tr id="opts" style="display:none;">
<td valign="top" bgcolor="<?echo$color['bgcolor'];?>" style="font-size:<?echo$font;?>; color:<?echo$color['tdcolor'];?>;" align=center>
[ <a href="#" onclick="window.open('userinfo.php?mode=edit','myinfo','width=800,height=750,top=0,left=0,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no');"><b><?echo$lang['myinfo'];?></b></a> ]<br>
<?
if($_SESSION['balls']>=700)
  {
  echo "[ <a href=\"adm.php\" target=_blank><b>$lang[conpanel]</b></a> ]";
  }
?>
<form action="hidden.php" method=get target=hidden>
<input type="hidden" name="ch_opts" value="1">
<div align="left">
<b><?echo$lang['smiles'];?>:</b> 
<input name="setsmiles" type="checkbox" value=1 <?if($_SESSION['smiles']==1)echo" checked";?>><br>
<b><?echo$lang['newmess'];?>:</b><br> 
<input name="setnm" value="up" type="radio"<?if($_SESSION['nm']=="up")echo" checked";?>><?echo$lang['ups'];?>&nbsp;&nbsp;
<input name="setnm" value="down" type="radio"<?if($_SESSION['nm']=="down")echo" checked";?>><?echo$lang['downs'];?><br>
<b><?echo$lang['sendform'];?>:</b><br> <input name="setsend" value="up" type="radio"<?if($_SESSION['send']=="up")echo" checked";?>><?echo$lang['ups'];?>&nbsp;&nbsp;
<input name="setsend" value="down" type="radio"<?if($_SESSION['send']=="down")echo" checked";?>><?echo$lang['downs'];?><br>
<b><?echo$lang['autofocus'];?>:</b><br> <input name="setfocus" value="1" type="radio"<?if($_SESSION['focus']=="1") echo" checked";?>><?echo$lang['on'];?>&nbsp;&nbsp;
<input name="setfocus" value="0" type="radio"<?if($_SESSION['focus']=="0")echo" checked";?>><?echo$lang['off'];?><br>
<b><?echo$lang['theme'];?>:</b>
<select name=setskin>
<?
define("SK_MOD",1);
include("theme/skins.php");
foreach($skins as $val)
  {
  echo "<option";
  if($_SESSION['skin']==$val) echo" selected";
  echo ">$val</option>";
  }
?>
</select><br>
<b><?echo$lang['time'];?>:</b> 
<select name="settime">
<option value="hm"<?if($_SESSION['vtime']=="hm")echo" selected";?>><?echo$lang['ttime']['hm'];?>
<option value="hms"<?if($_SESSION['vtime']=="hms")echo" selected";?>><?echo$lang['ttime']['hms'];?>
<option value="ms"<?if($_SESSION['vtime']=="ms")echo" selected";?>><?echo$lang['ttime']['ms'];?>
</select><br></div>
<button onclick="submit(); return false;"><?echo$lang['apply'];?></button>
</form>
</td>
</tr>
</table>
<?}?>

<?if($_SESSION['sid']>-1){?>
<table  style="width:100%;" width="100%" align="center" border="0" cellspacing="1" bgcolor="<?echo$color['tableh'];?>" cellpadding="1">
<tr>
<td  valign="middle" bgcolor="<?echo$color['tdbgcolor'];?>" style="padding:3px; font-size:10px; font-family:Tahoma; color:<?echo$color['tdcolor'];?>; virtual-align:middle; Text-decoration: none; height:16; Letter-spacing: 2px;" nowrap>
<a href="#" onselectstart="return false;" onclick="menu_right('mail');">
<img id="img_mail" src="theme/<?echo$skin;?>/img/st_d.gif" border="0" style="padding-right:5px;"><?echo$lang['mail'];?>:</a>
</td>
</tr>
<tr id="mail" style="display:none;">
<td valign="top" bgcolor="<?echo$color['bgcolor'];?>" style="font-size:<?echo$font;?>; color:<?echo$color['tdcolor'];?>;" align=center>
<?
$nums_in=mysql_result(mysql_query("select count(id) from chat_mail where userto='$_SESSION[suser]' and viewin='1'"),0,'count(id)');
$nums_innew=mysql_result(mysql_query("select count(id) from chat_mail where userto='$_SESSION[suser]' and rd='0' and viewin='1'"),0,'count(id)');
$nums_out=mysql_result(mysql_query("select count(id) from chat_mail where user='$_SESSION[suser]' and viewout='1'"),0,'count(id)');
$procs=round(($nums_in+$nums_out)/$max_msgs*100,1);
?><b>
[ <a href="#" onclick="window.open('mail.php?mode=in','mail_in','width=800,height=700,top=0,left=0,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no');"><?echo$lang['inbox'];?></a> <?echo$nums_in;if($nums_innew>0)echo" ($nums_innew)";?> ]<br>
[ <a href="#" onclick="window.open('mail.php?mode=out','mail_out','width=800,height=700,top=0,left=0,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no');"><?echo$lang['outbox'];?></a> <?echo$nums_out;?> ]<br>
[ <?echo$lang['usemail'];?>: <?echo$procs;?>% ]<br>
[ <a href="#" onclick="window.open('mail.php?mode=write','mail_new','width=800,height=750,top=0,left=0,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no');"><?echo$lang['newletter'];?></a> ]</b>
</td>
</tr>
</table>
<?}?>
</body>
</html>