<?
if(!defined("A_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

  if(empty($operat)) $operat="";
  if(empty($act)) $act="";
  if(isset($add))
    {
    $error="";
    if(!$color_code) $error.="Не указан код цвета!";
    if(!$color_title) $error.="Не указано название цвета!";
    if(empty($error))
      {
      $query_add="INSERT INTO chat_colors VALUES ('','$color_code','$color_title','$color_level')";
      if(mysql_query($query_add))
        {
        echo "Цвет успешно добавлен в базу!";
        }
      else 
        {
        echo "Произошла ошибка при добавлении цвета в базу! Возможно, такой код уже существует.";
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
    if(!$color_code) $error.="Не указан код цвета!";
    if(!$color_title) $error.="Не указано название цвета!";
    if(empty($error))
      {
      $query_upd="update chat_colors set code='$color_code',title='$color_title',level='$color_level' where id='$id'";
      if(mysql_query($query_upd))
        {
        echo "Цвет успешно отредактирован!";
        }
      else 
        {
        echo "Произошла ошибка при редактировании цвета в базе!";
        }
      }
    else 
      {
      echo "Произошла ошибка!<br>$error";
      }
    }
  elseif($operat=="del"&&$id!=1)
    {      
    $query_del="delete from chat_colors where id='$id'";
    if(mysql_query($query_del))
      {
      echo "Цвет успешно удалён!";
      }
    else 
      {
      echo "Произошла ошибка при удалении цвета из базы!";
      }
    }      
  else
    {
    if($act=="add")
      {?>
      <form action=adm.php?mode=colors method=post name="form">
      <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
      <tr>
      <td valign=top align=center class=tcat colspan=2>Добавление цвета</td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>Название цвета</td>
      <td valign=top class=alt2 width=50%>
      <input type=text name=color_title size=25></td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>Код цвета</td>
      <td valign=top class=alt2 width=50%>
      <b>#</b><input type=text name="color_code" size=25 maxlength=6 onchange="document.getElementById('color').style.background='#'+document.form.color_code.value"> <span id="color" style="background:none;width: 100px"></span></td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>Уровень</td>
      <td valign=top class=alt2 width=50%><select name=color_level>
      <?for($i=1;$i<=3;$i++) echo"<option value=$i>$i</option>";?></select></td>
      </tr>
      <tr>
      <td colspan=2 class=tcat width=100% align=center>
      <input type=submit name=add value="Добавить"></td>
      </tr>
      </table></form><?         
      }
    elseif($act=="edit")
      {
      $query_color=mysql_query("select * from chat_colors where level>'0' and id='$id';");
      list($id_cl,$color_code,$color_title,$color_level)=mysql_fetch_row($query_color);?>
      <form action=adm.php?mode=colors method=post name="form">
      <input type=hidden name=id value="<?echo$id_cl;?>">
      <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
      <tr>
      <td valign=top align=center class=tcat colspan=2>Редактирование цвета</td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>Название цвета</td>
      <td valign=top class=alt2 width=50%>
      <input type=text name="color_title" size=25 value="<?echo$color_title;?>"></td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>Код цвета</td>
      <td valign=top class=alt2 width=50%>
      #<input type=text name="color_code" size=25 value="<?echo$color_code;?>" maxlength=6 onchange="document.getElementById('color').style.background='#'+document.form.color_code.value"> <span id="color" style="background-color:#<?echo$color_code;?>;width:100px"></span></td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>Уровень</td>
      <td valign=top class=alt2 width=50%><select name="color_level">
      <?
      for($i=1;$i<=3;$i++)
        {
        echo"<option value=$i";
        if($color_level==$i) echo" selected";
        echo ">$i</option>";
        }?></select></td>
      </tr>
      <tr>
      <td colspan=2 class=tcat width=100% align=center>
      <input type=submit name=edit value="Изменить"></td>
      </tr>
      </table></form><?         
      }
    else
      {
      $query_colors=mysql_query("select * from chat_colors where level>'0' order by level,id;");?>
      <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
      <tr>
      <td align=center colspan=5>
      <a href="adm.php?mode=colors&act=add">Добавление цвета</a></td>
      </tr>
      <tr>
      <td valign=top align=center class=tcat colspan=5>Управление цветами</td>
      </tr>
      <tr>
      <td align=center class=tcat width=20%><b>Название цвета</b></td>
      <td align=center class=tcat width=20%><b>Код цвета</b></td>
      <td align=center class=tcat width=20%><b>Цвет</b></td>
      <td align=center class=tcat width=10%><b>Уровень</b></td>
      <td align=center class=tcat width=30%><b>Действие</b></td>
      </tr><?
      while($array_colors=mysql_fetch_array($query_colors))
        {?>
        <tr>
        <td align=center class=alt2><b><?echo$array_colors['title'];?></b></td>
        <td align=center class=alt2>#<?echo$array_colors['code'];?></td>
        <td align=center class=alt2 style="background:#<?echo$array_colors['code'];?>;width:150px"></td>
        <td align=center class=alt2><?echo$array_colors['level'];?></td>
        <td align=center class=alt2><a href="adm.php?mode=colors&act=edit&id=<?echo$array_colors['id'];?>">Изменить</a>
        <a href="adm.php?mode=colors&operat=del&&id=<?echo$array_colors['id'];?>"
        onClick="return confirm('Вы действительно хотите удалить этот цвет?');">Удалить</a></td>
        </tr><?         
        }?>
      </table><?
      }
    }
?>