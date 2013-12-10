<?
if(!defined("A_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

  if(isset($edit))
    {
    $error="";
    if(empty($setuser)) $error.="Не указан пользователь.<br>";
    if(empty($error))
      {
      $query_edit="update chat_users set balls='$setballs' where user='$setuser'";
      if(mysql_query($query_edit))
        {
        mysql_query("update chat_onliners set balls='$setballs' where user='$setuser'");
        echo "Статус успешно отредактирован.";
        }
      else
        {
        echo "Произошла ошибка при редактировании статуса.";
        }
      }
    else 
      {
      echo "Произошла ошибка!<br>$error";
      }
    }
  elseif($operat=="del"&&$id)
    {
    $query=mysql_query("select id,user from chat_users where id='$id'");
    list($check,$nick)=mysql_fetch_row($query);
    if(strtolower($nick)!=strtolower($_SESSION['suser']))
      $query_del="update chat_users set balls='0' where id='$id'";
    else $query_del="";
    if(mysql_query($query_del)&&mysql_num_rows($query)>0)
      {
      mysql_query("update chat_onliners set balls='0' where user='$nick'");
      echo "Статус успешно удалён!";
      }
    else 
      {
      echo "Произошла ошибка при удалении статуса из базы!";
      }
    }
  else
    {
    $count=mysql_result(mysql_query("select count(*) from chat_users where balls>0"),0,'count(*)');
    $pages=ceil($count/20);
    if($page=="" or $page=="0") $lim="0";
    else $lim=($page-1)*20;
    $query_balls=mysql_query("select * from chat_users where balls>0 order by balls desc, id limit $lim,20");?>
    <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
    <tr>
    <td valign=top align=center class=tcat colspan=4>Управление баллами</td>
    </tr>
    <tr>
    <td align=center class=tcat width=30%><b>Пользователь</b></td>
    <td align=center class=tcat width=30%><b>Статус</b></td>
    <td align=center class=tcat width=40%><b>Действие</b></td>
    </tr>
    <tr>
    <td class=alt2 colspan=3><span class=smallfont>Всего: <b><?echo$pages;?></b>. Страницы: <b><?pages($count);?></b></span></td>
    </tr><?
    while($array_balls=mysql_fetch_array($query_balls))
      {
      echo "<tr>";
      echo "<td align=center class=alt2 width=20%><b>$array_balls[user]</b></td>";
      echo "<td align=center class=alt2 width=20%>";
      if($array_balls['balls']==999) echo "Администратор";
      elseif($array_balls['balls']>=700) echo "Старший модератор";
      elseif($array_balls['balls']>=500) echo "Младший модератор";
      elseif($array_balls['balls']>=100) echo "Форумчанин";
      echo "</td>";
      echo "<td align=center class=alt2 width=30%>
      <a href=\"adm.php?mode=balls&operat=del&&id=$array_balls[id]\"
      onClick=\"return confirm('Вы действительно хотите удалить статус у этого пользователя?');\">Удалить статус</a></td>";
      echo "</tr>";
      }
    echo "<tr>
    <td colspan=3 class=tcat><form action=adm.php?mode=balls method=post>
    Задать статус:<br>
    Ник: <input type=text name=setuser> Статус: <select name=setballs class=smallfont>
    <option value=0>Пользователь</option>
    <option value=100>Форумчанин</option>
    <option value=500>Младший модератор</option>
    <option value=700>Старший модератор</option>
    <option value=999>Администратор</option>
    </select>
    <input type=submit name=edit value=\"Изменить\"></form></td></tr>";
    echo "</table>";
    }
?>