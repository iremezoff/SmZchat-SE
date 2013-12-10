<?
if(!defined("A_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

  if(isset($add))
    {
    $error="";
    if(empty($newroom)) $error.="Не указано название комнаты!<br>";
    if(empty($newbot)) $error.="Не указано имя бота!<br>";
    if(empty($error))
      {
      $query_add="insert into chat_rooms values('','$newroom','$newbot','')";
      if(mysql_query($query_add))
        {
        echo "Комната успешно добавлена!";
        }
      else
        {
        echo "Произошла ошибка при добавлении комнаты!";
        }
      }
    else
      {
      echo "Произошла ошибка!<br>$error";
      }
    }
  elseif(isset($edit))
    {
    $error="";
    if(empty($setroom)) $error.="Не введено название комнаты!<br>";
    if(empty($setbot)) $error.="Не введено имя бота!<br>";
    if(empty($error))
      {
      $query_edit="update chat_rooms set name='$setroom',botname='$setbot',topic='$settopic',moders='$moders_list' where id='$id_room'";
      if(mysql_query($query_edit))
        {
        echo "Комната успешно отредактирована!";
        }
      else
        {
        echo "Произошла ошибка при редактировании комнаты!";
        }
      }
    else
      {
      echo "Произошла ошибка!<br>$error";
      }
    }
  elseif($operat=="del")
    {
    $query_del="delete from chat_rooms where id='$id'";
    if(mysql_query($query_del))
      {
      mysql_query("delete from chat_messages where room='$id'");
      $min_room=mysql_result(mysql_query("select min(id) from chat_rooms"),0,'min(id)');
      mysql_query("update chat_onliners set room='$min_room'");
      echo "Комната успешно удалена";
      }
    else
      {
      echo "Произошла ошибка при удалении комнаты";
      }
    }
  else
    {
    if($act=="edit")
      {
      $query_room=mysql_query("select * from chat_rooms where id='$id'");
      list($id_room,$name_room,$name_bot,$topic_room,$moders_room)=mysql_fetch_row($query_room);
      $moders=explode("|",$moders_room);
      $opt_mods="";
      foreach($moders as $val)
        {
        if(!empty($val))
          {
          @$nick=mysql_result(mysql_query("select user from chat_users where id='$val'"),'0','user');
          if(!empty($nick)) $opt_mods.="<option value=$val>$nick\n";
          }
        }?>
      <script language=JavaScript type=text/javascript src="design/js.php"></script>
      <form action=adm.php?mode=rooms method=post name=form>
      <input type=hidden name=id_room value=<?echo$id_room;?>>
      <table cellpadding=1 cellspacing=3 width=100% border=0>
      <tr>
      <td align=center class=tcat colspan=2>Редактирование комнаты</td>
      </tr>
      <tr>
      <td class=alt2 width=20%><b>Название комнаты:</b></td>
      <td class=alt2 width=80%><input type=text name=setroom value="<?echo$name_room;?>"></td>
      </tr>
      <tr>
      <td class=alt2 width=20%><b>Имя бота:</b></td>
      <td class=alt2 width=80%><input type=text name=setbot value="<?echo$name_bot;?>"></td>
      </tr>
      <tr>
      <td class=alt2 width=20%><b>Топик комнаты:</b></td>
      <td class=alt2 width=80%><input type=text name=settopic value="<?echo$topic_room;?>"></td>
      </tr>
      <tr>
      <td align=center class=tcat colspan=2><input type=submit name=edit value="Редактировать"></td>
      </tr>
      </table></form><?
      }
    else
      {
      $query_rooms=mysql_query("select * from chat_rooms order by id");?>
      <table cellpadding=1 cellspacing=3 width=100% border=0>
      <tr>
      <td valign=top class=tcat align=center>#</td>
      <td valign=top class=tcat align=center>Комнаты</td>
      <td valign=top class=tcat align=center>Боты</td>
      <td valign=top class=tcat align=center>Топик</td>
      <td valign=top class=tcat align=center>Действия</td>
      </tr><?
      $r=1;
      while($array_rooms=mysql_fetch_array($query_rooms))
        {?>
        <tr>
        <td align=center class=alt2><?echo$r;?></td>
        <td align=center class=alt2><b><?echo$array_rooms['name'];?></b></td>
        <td align=center class=alt2><b><?echo$array_rooms['botname'];?></b></td>
        <td align=center class=alt2><b><?echo$array_rooms['topic'];?></b></td>
        <td align=center class=alt2><a href="adm.php?mode=rooms&act=edit&id=<?echo$array_rooms['id'];?>">Редактировать</a> |
        <a href="adm.php?mode=rooms&operat=del&id=<?echo$array_rooms['id'];?>"
        onClick="return confirm('Вы действительно хотите удалить эту комнату?');">Удалить</a></td>
        </tr><?
        $r++;
        }?>
      <tr>
      <td valign=top colspan=5 class=tcat>
      <form action=adm.php?mode=rooms method=post>
      Добавить новую комнату: Название: <input type=text name=newroom> Бот: <input type=text name=newbot> <input type=submit name=add value="Добавить!">
      </from></td>
      </tr>
      </table><?
      }
    }
?>