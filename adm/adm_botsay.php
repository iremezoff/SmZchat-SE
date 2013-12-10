<?
if(!defined("A_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

  if(isset($add))
    {
    $error="";
    if(!$say) $error.="Вы не ввели релику!<br>";
    if($type!=1&&$type!=2) $error.="Вы не выбрали тип реплики!<br>";
    if(empty($error))
      {
      $query_say="insert into chat_botsay values ('','$say','$type')";
      if(mysql_query($query_say))
        {
        echo "Реплика успешно удалена";
        }
      }
    else
      {
      echo "Произошла ошибка!<br>$error";
      }
    }
  elseif(isset($edit))
    {
    foreach($says as $key=>$val)
      {
      if(!$val) mysql_query("delete from chat_botsay where id='$key'");
      else mysql_query("update chat_botsay set value='$val',type='".$type[$key]."' where id='$key'");
      }
    echo "Реплики успешно отредактированы";
    }
  else
    {
    $query_says=mysql_query("select * from chat_botsay order by id");?>
    <span class=smallfont>*<b>Оставьте поле пустым, если хотите удалить одно или несколько вероисповеданий</b><br>
    В реплике обязательно введите '+u+' (без кавычек) для вставки ника. В противном случае ник не будет отображаться при входе/выходе</span>
    <form action=adm.php?mode=botsay method=post>
    <table cellpadding=1 cellspacing=3 width=100% border=0>
    <tr>
    <td valign=top align=center class=tcat colspan=2>Реплики</td>
    </tr><?
    while($array_says=mysql_fetch_array($query_says))
      {?>
      <tr>
      <td valign=top class=alt2 align=center>
      <input type=text name=says[<?echo$array_says['id'];?>] value="<?echo$array_says['value'];?>" class=smallfont size=30></td>
      <td valign=top class=alt2 align=center><input type=radio name=type[<?echo$array_says['id'];?>] value=1<?if($array_says['type']==1)echo" checked";?>> Вход
      <input type=radio name=type[<?echo$array_says['id'];?>] value=2<?if($array_says['type']==2)echo" checked";?>> Выход</td>
      </tr><?
      }?>
    <tr>
    <td valign=top class=tcat align=center colspan=2>
    <input type=text name=say class=smallfont size=25> <input type=radio name=type value=1> Вход
    <input type=radio name=type value=2> Выход <input type=submit name=add value="Добавить" class=smallfont></td>
    </tr>
    <tr>
    <td valign=top align=center class=tcat colspan=2>
    <input type=submit name=edit value="Обновить"></td>
    </tr>
    </table>
    </form><?
    }
?>