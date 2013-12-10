<?
if(!defined("F_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

if(empty($room)) $room=mysql_result(mysql_query("select min(id) from chat_rooms"),0,'min(id)');
$opt_rs=$nowchat="";
$query_rooms=mysql_query("select id,name from chat_rooms order by id");
while($arr_rooms=mysql_fetch_array($query_rooms))
  {
  $query_users=mysql_query("select user,sex from chat_onliners where room='$arr_rooms[id]' order by user");
  $total=mysql_num_rows($query_users);
  $total_m=mysql_num_rows(mysql_query("select user from chat_onliners where sex=1 and room='$arr_rooms[id]' order by user"));
  $total_w=mysql_num_rows(mysql_query("select user from chat_onliners where sex=2 and room='$arr_rooms[id]' order by user"));
  $total_g=mysql_num_rows(mysql_query("select user from chat_onliners where sex=0 and room='$arr_rooms[id]' order by user"));
  $opt_rs.="<option value=\"$arr_rooms[id]\"";
  if($room==$arr_rooms['id']) $opt_rs.=" selected";
  $opt_rs.=">$arr_rooms[name]</option>";
  if($room==$arr_rooms['id']) $display="yes";
  else $display="none";
  $nowchat.="<tr>";
  $nowchat.="<td><a href=# onclick=\"show_hide('$arr_rooms[id]')\" title=\"Нажми на меня\">$arr_rooms[name]</a> Всего: <b>$total</b> (мужчины/парни: <b>$total_m</b>, женщины/девушки: <b>$total_w</b>, гости: <b>$total_g</b>)</td></tr>";
  $nowchat.="<tr id=$arr_rooms[id] style=\"display:$display\"><td> <b>|</b> ";
  if($total==0) $nowchat.="пусто <b>|</b>";
  else
    while($arr_users=mysql_fetch_array($query_users))
      if($arr_users['sex']==0)
        $nowchat.="$arr_users[user] <b>|</b> ";
      else
        $nowchat.="<a href=\"userinfo.php?user=$arr_users[user]\" target=\"_blank\">$arr_users[user]</a> <b>|</b> ";
  $nowchat.="</td></tr>";
  }

$reg_now=mysql_num_rows(mysql_query("select id from chat_users where date_register='".date("Y-m-d")."'"));
$reg_total=mysql_num_rows(mysql_query("select id from chat_users"));
include("design/header.tpl");
?>
<script language=javascript>
<!--
function show_hide (ID)
{
 var ID;
 if(document.getElementById(ID).style.display == 'none') { document.getElementById(ID).style.display = ''; }
 else { document.getElementById(ID).style.display = 'none'; }
}
//-->
</script>
<table border="0" cellpadding="0" cellspacing="6" height="100%" width="100%">
<tr>
<td width="200" valign="top" rowspan=3>
<form action="auth.php" method="post" name="form">
<table cellpadding=0 cellsapcing=0 width=100% border=0>
<tr>
<td width=50>Логин:</td>
<td width=150><input type="text" maxlength="32" name="login" class=smallfont></td>
</tr>
<tr>
<td width=50>Пароль:</td>
<td width=150><input type="password" maxlength="32" name="pass" class=smallfont></td>
</tr>
<tr>
<td width=50>Комната:</td>
<td width=150><select name="room" class="smallfont" style="width:100%"><?echo$opt_rs;?></select></td>
</tr>
<tr>
<td colspan=2 align=center><input type="submit" value="Авторизироваться" name="submit" class=smallfont></td>
</tr>
<tr>
<td colspan=2 align=center>
[ <a href="index.php?reg" target=_blank>Регистрация</a> ]<br>
[ <a href=# onclick='window.open("index.php?forg","_blank","Width=600,Height=200,toolbar=0,status=0,border=0,scrollbars=0");'>Восстановить пароль</a> ]
<br /><br />
<div align=left><?include("banners/news.tpl");?></div>
</td>
</tr>
</table>
</form>
</td>
<td valign=top>
<table cellpadding=0 cellspacing=0 width=100% border=0>
<tr>
<td height=80 align=center><div><?include("banners/468x60_top.tpl");?></div></td>
</tr>
<tr>
<td>
<table cellpadding=0 cellspacing=0 width=100% border=0>
<tr>
<td colspan=2><b>Комнаты:</b></td>
</tr>
<?echo$nowchat;?>
<tr><td>&nbsp;</td></tr>
<tr>
<td colspan=2><b>Статистика:</b></td>
</tr>
<td>
<?
$query_stat=mysql_query("select * from chat_stat order by id");
while($arr_stat=mysql_fetch_array($query_stat))
  {
  switch($arr_stat['type'])
    {
    case "auth":
    echo "Сегодня были: <b>$arr_stat[count]</b>";
    break;
    case "mess_day":
    echo "Сообщений за сегодня: <b>$arr_stat[count]</b>";
    break;
    case "mess_week":
    echo "Сообщений за неделю: <b>$arr_stat[count]</b>";
    break;
    case "mess_month":
    echo "Сообщений за месяц: <b>$arr_stat[count]</b>";
    break;
    case "mess_year":
    echo "Сообщений за год: <b>$arr_stat[count]</b>";
    break;
    case "mess_total":
    echo "Сообщений всего: <b>$arr_stat[count]</b>";
    break;
    case "size_day":
    echo "Размер за сегодня: <b>";printf("%0.3f Mb", $arr_stat['count']/1024/1024);echo "</b>";
    break;
    case "size_week":
    echo "Размер за неделю: <b>";printf("%0.3f Mb", $arr_stat['count']/1024/1024);echo "</b>";
    break;
    case "size_month":
    echo "Размер за месяц: <b>";printf("%0.3f Mb", $arr_stat['count']/1024/1024);echo "</b>";
    break;
    case "size_year":
    echo "Размер за год: <b>";printf("%0.3f Mb", $arr_stat['count']/1024/1024);echo "</b>";
    break;
    case "size_total":
    echo "Размер всего: <b>";printf("%0.3f Mb", $arr_stat['count']/1024/1024);echo "</b>";
    break;
    case "rec_auth":
    $total_rec_auth=$arr_stat['count'];
    break;
    }
  echo "<br>";
  }
if(my_bcmod($total_rec_auth,10)>4 || my_bcmod($total_rec_auth,10)<2) $hum="человек";
else $hum="человека";
?>
</td>
<tr><td><a href=# onclick="show_hide('time')"><b>Наибольшее количество времени, проведённого в чате</b></a></td></tr>
<tr id="time" style="display:none"><td>
<?
$i=1;
$query_ust=mysql_query("select user,inchat from chat_users order by inchat desc limit 10");
while($arr_ust=mysql_fetch_array($query_ust))
  {echo "$i. <a href=\"userinfo.php?user=$arr_ust[user]\" target=\"_blank\">$arr_ust[user]</a> (".h_m($arr_ust['inchat']).")<br>"; $i++; }
?>
</td></tr>
<tr><td><a href=# onclick="show_hide('tmess')"><b>Наибольшее общее количество сообщений</b></a></td></tr>
<tr id="tmess" style="display:none"><td>
<?
$i=1;
$query_ustm=mysql_query("select user,mess+pmess from chat_users order by mess+pmess desc limit 10");
while($arr_ustm=mysql_fetch_array($query_ustm))
  {echo "$i. <a href=\"userinfo.php?user=$arr_ustm[user]\" target=\"_blank\">$arr_ustm[user]</a> (".$arr_ustm['mess+pmess'].")<br>"; $i++; }
?>
</td></tr>
<tr><td><a href=# onclick="show_hide('mess')"><b>Наибольшее количество неприватных сообщений</b></a></td></tr>
<tr id="mess" style="display:none"><td>
<?
$i=1;
$query_usm=mysql_query("select user,mess from chat_users order by mess desc limit 10");
while($arr_usm=mysql_fetch_array($query_usm))
  {echo "$i. <a href=\"userinfo.php?user=$arr_usm[user]\" target=\"_blank\">$arr_usm[user]</a> (".$arr_usm['mess'].")<br>"; $i++; }
?>
</td></tr>
<tr><td><a href=# onclick="show_hide('pmess')"><b>Наибольшее количество приватных сообщений</b></a></td></tr>
<tr id="pmess" style="display:none"><td>
<?
$i=1;
$query_uspm=mysql_query("select user,pmess from chat_users order by pmess desc limit 10");
while($arr_uspm=mysql_fetch_array($query_uspm))
  {echo "$i. <a href=\"userinfo.php?user=$arr_uspm[user]\" target=\"_blank\">$arr_uspm[user]</a> (".$arr_uspm['pmess'].")<br>"; $i++; }
?>
</td></tr>
<tr><td><a href=# onclick="show_hide('auth')"><b>Наибольшее количество входов в чат</b></a></td></tr>
<tr id="auth" style="display:none"><td>
<?
$i=1;
$query_usa=mysql_query("select user,auth from chat_users order by auth desc limit 10");
while($arr_usa=mysql_fetch_array($query_usa))
  {echo "$i. <a href=\"userinfo.php?user=$arr_usa[user]\" target=\"_blank\">$arr_usa[user]</a> (".$arr_usa['auth'].")<br>"; $i++; }
?>
</td></tr>
<tr><td><a href=# onclick="show_hide('top')"><b>Самые популярные пользователи (наиболее просматриваемые)</b></a></td></tr>
<tr id="top" style="display:none"><td>
<table cellpadding=0 cellcpacing=0 border=0>
<tr>
<td>Мужчины/парни:</td>
<td width=50>&nbsp;</td>
<td>Женщины/девушки:</td>
</tr>
<td valign=top>
<?
$i=1;
$query_ustm=mysql_query("select user,clicks from chat_users where sex='1' order by clicks desc limit 10");
while($arr_ustm=mysql_fetch_array($query_ustm))
  {echo "$i. <a href=\"userinfo.php?user=$arr_ustm[user]\" target=\"_blank\">$arr_ustm[user]</a> (".$arr_ustm['clicks'].")<br>"; $i++; }
?>
</td>
<td></td>
<td valign=top>
<?
$i=1;
$query_ustw=mysql_query("select user,clicks from chat_users where sex='2' order by clicks desc limit 10");
while($arr_ustw=mysql_fetch_array($query_ustw))
  {echo "$i. <a href=\"userinfo.php?user=$arr_ustw[user]\" target=\"_blank\">$arr_ustw[user]</a> (".$arr_ustw['clicks'].")<br>"; $i++; }
?>
</td>
</tr>
</table>
</td></tr>
<tr><td><a href=# onclick="show_hide('skins')"><b>Рейтинг скинов</b></a></td></tr>
<tr id="skins" style="display:none"><td>
<?
define("SK_MOD",1);
include("theme/skins.php");
foreach($skins as $val)
  $count[$val]=mysql_result(mysql_query("select count(id) from chat_users where skin='$val'"),0,'count(id)');
arsort($count);
$i=1;
foreach($count as $key=>$val)
  {echo "$i. <b>$key</b> ($val)<br>"; $i++; }
?>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>Рекорд посещаемости: <b><?echo dbdate($rec_auth);?></b> - <b><?echo$total_rec_auth;?></b> <?echo$hum;?></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>Всего зарегистрировано: <b><?echo$reg_total;?></b></td></tr>
<tr><td>Зарегистрировано сегодня: <b><?echo$reg_now;?></b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><b>Именинники:</b></td></tr>
<?
$burn1=explode("-",date("m-d"));
$burn2=explode("-",date("m-d",time()+60*60*24));
$burn3=explode("-",date("m-d",time()+60*60*24*2));
$query_1bm=mysql_query("select user from chat_users where sex='1' and month(date_burn)='$burn1[0]' and day(date_burn)='$burn1[1]'");
$query_1bw=mysql_query("select user from chat_users where sex='2' and month(date_burn)='$burn1[0]' and day(date_burn)='$burn1[1]'");
$query_2bm=mysql_query("select user from chat_users where sex='1' and month(date_burn)='$burn2[0]' and day(date_burn)='$burn2[1]'");
$query_2bw=mysql_query("select user from chat_users where sex='2' and month(date_burn)='$burn2[0]' and day(date_burn)='$burn2[1]'");
$query_3bm=mysql_query("select user from chat_users where sex='1' and month(date_burn)='$burn3[0]' and day(date_burn)='$burn3[1]'");
$query_3bw=mysql_query("select user from chat_users where sex='2' and month(date_burn)='$burn3[0]' and day(date_burn)='$burn3[1]'");
$total_1bm=mysql_num_rows($query_1bm);
$total_1bw=mysql_num_rows($query_1bw);
$total_2bm=mysql_num_rows($query_2bm);
$total_2bw=mysql_num_rows($query_2bw);
$total_3bm=mysql_num_rows($query_3bm);
$total_3bw=mysql_num_rows($query_3bw);
?>
<tr><td><a href=# onclick="show_hide('im1')" title="Нажми на меня">Сегодня</a> <b><?echo dbdate(date("Y-m-d"));?></b> Всего: <b><?echo($total_1bm+$total_1bw);?></b> (мужчины/парни: <b><?echo$total_1bm;?></b>, женщины/девушки: <b><?echo$total_1bw;?></b>)</td></tr>
<tr id="im1"><td><b>|</b> 
<?
while($arr_1bm=mysql_fetch_array($query_1bm))
  echo "<a href=\"userinfo.php?user=$arr_1bm[user]\" target=\"_blank\">$arr_1bm[user]</a> <b>|</b> ";
while($arr_1bw=mysql_fetch_array($query_1bw))
  echo "<a href=\"userinfo.php?user=$arr_1bw[user]\" target=\"_blank\">$arr_1bw[user]</a> <b>|</b> ";
if(($total_1bm+$total_1bw)==0) echo "пусто <b>|</b>";
?></td></tr>
<tr><td><a href=# onclick="show_hide('im2')" title="Нажми на меня">Завтра</a> <b><?echo dbdate(date("Y-m-d",time()+60*60*24));?></b> Всего: <b><?echo($total_2bm+$total_2bw);?></b> (мужчины/парни: <b><?echo$total_2bm;?></b>, женщины/девушки: <b><?echo$total_2bw;?></b>)</td></tr>
<tr id="im2"><td><b>|</b> 
<?
while($arr_2bm=mysql_fetch_array($query_2bm))
  echo "<a href=\"userinfo.php?user=$arr_2bm[user]\" target=\"_blank\">$arr_2bm[user]</a> <b>|</b> ";
while($arr_2bw=mysql_fetch_array($query_2bw))
  echo "<a href=\"userinfo.php?user=$arr_2bw[user]\" target=\"_blank\">$arr_2bw[user]</a> <b>|</b> ";
if(($total_2bm+$total_2bw)==0) echo "пусто <b>|</b>";
?></td></tr>
<tr><td><a href=# onclick="show_hide('im3')" title="Нажми на меня">Послезавтра</a> <b><?echo dbdate(date("Y-m-d",time()+60*60*24*2));?></b> Всего: <b><?echo($total_3bm+$total_3bw);?></b> (мужчины/парни: <b><?echo$total_3bm;?></b>, женщины/девушки: <b><?echo$total_3bw;?></b>)</td></tr>
<tr id="im3"><td><b>|</b> 
<?
while($arr_3bm=mysql_fetch_array($query_3bm))
  echo "<a href=\"userinfo.php?user=$arr_3bm[user]\" target=\"_blank\">$arr_3bm[user]</a> <b>|</b> ";
while($arr_3bw=mysql_fetch_array($query_3bw))
  echo "<a href=\"userinfo.php?user=$arr_3bw[user]\" target=\"_blank\">$arr_3bw[user]</a> <b>|</b> ";
if(($total_3bm+$total_3bw)==0) echo "пусто <b>|</b>";
?></td></tr>
</table>
</td>
</tr>
<tr>
<td height=80 align=center><?include("banners/counter.tpl");?></td>
</tr>
<tr>
<td height=80 align=center><?include("banners/468x60_bottom.tpl");?></td>
</tr>
</table>
</td>
<td width="200" valign="top">
<div><?include("banners/friends.tpl");?></div>
<div><?include("banners/links.tpl");?></div></td>
</tr>
</table>
</body>
</html>