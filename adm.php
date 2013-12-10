<?
define("C_MOD",1);
include("inc/functions.php");
if(empty($mode)) $mode="index";
if(!isset($_SESSION['suser']) || !file_exists("adm/adm_$mode.php")) exit;
if(empty($act)) $act="";
if(empty($operat)) $operat="";
if(empty($page)) $page=1;
$arr_mods=array('index'=>'Опции',
		'reg'=>'Строка регистрации',
		'dump'=>'Дамп БД',
		'rooms'=>'Комнаты',
		'priv'=>'Уровни',
		'balls'=>'Уровни пользователей',
		'users'=>'Управление пользователями',
		'smiles'=>'Смайлики',
		'xstatus'=>'X-статусы',
		'colors'=>'Цвета',
		'botsay'=>'Реплики входа/выхода',
		'rules'=>'Правила',
		'compl'=>'Смотреть жалобы'
);

define("A_MOD",1);

$title="Панель администрирования";
include("design/header.tpl");
?>
<table cellpadding=5 cellspacing=0 width=100% align=center>
<tr>
<td valign=top colspan=2 bgColor=#a9bfd4 align=left style="color: #ffffff;">
<b>Powered by <a href="mailto:remezov2004@mail.ru">SmZv<span style="color:#FA2B2B"><b>i</b></span>oo s-mod</a>&nbsp;&nbsp</b></td>
</tr>
</table>
<table cellpadding=0 cellspacing=0 width=100% border=0>
<tr>
<td valign=top width=25%><b>Настройки чата:</b><br>
[ <a href="adm.php"><?echo$arr_mods['index'];?></a> ]<br>
[ <a href="adm.php?mode=reg"><?echo$arr_mods['reg'];?></a> ]<br>
[ <a href="adm.php?mode=dump"><?echo$arr_mods['dump'];?></a> ]<br>
[ <a href="adm.php?mode=rooms"><?echo$arr_mods['rooms'];?></a> ]
</td>
<td valign=top width=25%><b>Управление пользователями:</b><br>
[ <a href="adm.php?mode=priv"><?echo$arr_mods['priv'];?></a> ]<br>
[ <a href="adm.php?mode=balls"><?echo$arr_mods['balls'];?></a> ]<br>
[ <a href="adm.php?mode=users"><?echo$arr_mods['users'];?></a> ]
</td>
<td valign=top width=25%><b>Визуальные настройки:</b><br>
[ <a href="adm.php?mode=smiles"><?echo$arr_mods['smiles'];?></a> ]<br>
[ <a href="adm.php?mode=xstatus"><?echo$arr_mods['xstatus'];?></a> ]<br>
[ <a href="adm.php?mode=colors"><?echo$arr_mods['colors'];?></a> ]<br>
[ <a href="adm.php?mode=botsay"><?echo$arr_mods['botsay'];?></a> ]
</td>
<td valign=top width=25%><b>Прочее:</b><br>
[ <a href="adm.php?mode=rules"><?echo$arr_mods['rules'];?></a> ]<br>
[ <a href="adm.php?mode=compl"><?echo$arr_mods['compl'];?></a> ]<br>
</td>
</tr>
</table><br>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td style="height:2px;background:#000000" colspan=2></td></tr>
<tr>
<td><a href="javascript:history.go(-1)"><b>&laquo; Назад</b></a></td>
<td align="right" nowrap="nowrap">
<span class=big><i>&raquo <?echo$arr_mods[$mode];?></i></span></td>
</tr>
<tr><td style="height:2px;background:#000000" colspan=2></td></tr>
</table>
<br>
<?

if(file_exists("adm/adm_$mode.php"))
  {
  include("adm/adm_$mode.php");
  }
else
  {
  echo "<script language=javascript>window.close();</script>";
  }
?>
<br><br><table cellpadding=5 cellspacing=0 width=100% align=center>
<tr>
<td valign=top colspan=2 bgColor=#a9bfd4 align=right style="color: #ffffff;">
<b>Powered by <a href="mailto:remezov2004@mail.ru">SmZv<span style="color:#FA2B2B"><b>i</b></span>oo s-mod</a>&nbsp;&nbsp</b></td>
</tr>
</table>
<?include("design/footer.tpl");?>