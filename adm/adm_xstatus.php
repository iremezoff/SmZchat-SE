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
    if(!$x_text) $error.="Не указан текст статуса!";
    if(empty($error))
      {
      $query_add="INSERT INTO chat_xstatus VALUES ('','$x_image','$x_text')";
      if(mysql_query($query_add))
        {
        echo "Статус успешно добавлен в базу!";
        }
      else 
        {
        echo "Произошла ошибка при добавлении статуса в базу! Возможно, изображение уже используется для другого статуса";
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
    if(empty($x_text)) $error.="Не указан текст статуса!";
    if(empty($error))
      {
      $query_upd="update chat_xstatus set image='$x_image', text='$x_text' where id='$id'";
      if(mysql_query($query_upd))
        {
        echo "Статус успешно отредактирован!";
        }
      else 
        {
        echo "Произошла ошибка при редактировании статуса в базе!";
        }
      }
    else 
      {
      echo "Произошла ошибка!<br>$error";
      }
    }
  elseif($operat=="del"&&$id!=1&&$id!=2)
    {      
    $query_del="delete from chat_xstatus where id='$id'";
    if(mysql_query($query_del))
      {
      echo "Статус успешно удалён!";
      }
    else 
      {
      echo "Произошла ошибка при удалении статуса из базы!";
      }
    }      
  else
    {
    if($act=="add")
      {?>
      <script language="javascript" type="text/javascript">
      <!--
      function update_smiley(newimage)
        {
	document.x_image.src = "xstatus/" + newimage;
        }
      //-->
      </script>
      <form action=adm.php?mode=xstatus method=post>
      <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
      <tr>
      <td valign=top align=center class=tcat colspan=2>Добавление статуса</td>
      </tr>
      <tr>
      <td class=alt2 width=50%>Файл с изображением статуса</td>
      <td class=alt2 width=50%>
      <select name="x_image" onchange="update_smiley(this.options[selectedIndex].value);" class=smallfont><?
      $dir=opendir("xstatus");
      while($file=readdir($dir))
        {
        list($name_img,$perm)=explode(".",$file);
        if($file!="." && $file!=".." && ($perm=="gif" || $perm=="png"))
          {
          if(empty($sm_n)) $sm_n=$file;
          $opt="<option value=\"$file\" >".$file."</option>\r\n";
          echo $opt;
          }
        }?>
      </select> <img name="x_image" src="xstatus/<?echo$sm_n;?>" border="0"></td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>Текст</td>
      <td valign=top class=alt2 width=50%>
      <input type=text name=x_text size=25></td>
      </tr>
      <tr>
      <td colspan=2 class=tcat width=100% align=center>
      <input type=submit name=add value="Добавить"></td>
      </tr>
      </table></form><?         
      }
    elseif($act=="edit")
      {
      $query_x=mysql_query("select * from chat_xstatus where id='$id';");
      list($id_x,$x_image,$x_text)=mysql_fetch_row($query_x);?>
      <script language="javascript" type="text/javascript">
      <!--
      function update_smiley(newimage)
        {
	document.x_image.src = "xstatus/" + newimage;
        }
      //-->
      </script>
      <form action=adm.php?mode=xstatus method=post>
      <input type=hidden name=id value="<?echo$id_x;?>">
      <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
      <tr>
      <td valign=top align=center class=tcat colspan=2>Редактирование статусов</td>
      </tr>
      <tr>
      <td class=alt2 width=50%>Файл с изображением статуса</td>
      <td class=alt2 width=50%>
      <select name=x_image onchange="update_smiley(this.options[selectedIndex].value);" class=smallfont><?
      $dir=opendir("xstatus");
      while($file=readdir($dir))
        {
        list($name_img,$perm)=explode(".",$file);
        if($file!="." && $file!=".." && ($perm=="gif" || $perm=="png"))
          {
          $opt="\r\n<option value=\"$file\"";
          if($x_image==$file) {$opt.=" selected"; $sm_n=$file; }
          $opt.=">".$file."</option>";
          echo $opt;
          }
        }?>
      </select> <img name="x_image" src="xstatus/<?echo$sm_n;?>" border="0"></td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>Текст статуса</td>
      <td valign=top class=alt2 width=50%>
      <input type=text name=x_text value="<?echo$x_text;?>" size=25></td>
      </tr>
      <tr>
      <td colspan=2 class=tcat width=100% align=center>
      <input type=submit name=edit value="Изменить"></td>
      </tr>
      </table></form><?         
      }
    else
      {
      $query_x=mysql_query("select * from chat_xstatus order by id;");?>
      <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
      <tr>
      <td align=center colspan=4>
      <a href="adm.php?mode=xstatus&act=add">Добавление статутса</a></td>
      </tr>
      <tr>
      <td valign=top align=center class=tcat colspan=4>Управление статусами</td>
      </tr>
      <tr>
      <td align=center class=tcat width=20%><b>Изображение</b></td>
      <td align=center class=tcat width=20%><b>Файл изображения</b></td>
      <td align=center class=tcat width=30%><b>Текст статуса</b></td>
      <td align=center class=tcat width=30%><b>Действие</b></td>
      </tr><?
      while($array_x=mysql_fetch_array($query_x))
        {
        $id_x=$array_x['id'];
        $x_image=$array_x['image'];
        $x_text=$array_x['text'];?>
        <tr>
        <td align=center class=alt2 width=20%><img src="xstatus/<?echo$x_image;?>"></td>
        <td align=center class=alt2 width=30%><?echo$x_image;?></td>
        <td align=center class=alt2 width=30%><?echo$x_text;?></td>
        <td align=center class=alt2 width=30% ><a href="adm.php?mode=xstatus&act=edit&id=<?echo$id_x;?>">Изменить</a>
        <a href="adm.php?mode=xstatus&operat=del&&id=<?echo$id_x;?>"
        onClick="return confirm('Вы действительно хотите удалить этот статус?');">Удалить</a></td>
        </tr><?         
        }?>
      </table><?
      }
    }
?>