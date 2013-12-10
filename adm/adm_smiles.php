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
    if(!$smile_code) $error.="Не указан код смайлика!";
    if(empty($error))
      {
      $query_add="INSERT INTO chat_smiles VALUES ('','$smile_code','$smile_url','$smile_level')";
      if(mysql_query($query_add))
        {
        echo "Смайлик успешно добавлен в базу!";
        }
      else 
        {
        echo "Произошла ошибка при добавлении смайлика в базу! Возможно, такой код уже существует.";
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
    if(empty($smile_code)) $error.="Не указан код смайлика!";
    if(empty($error))
      {
      $query_upd="update chat_smiles set code='$smile_code',url='$smile_url',level='$smile_level' where id='$id'";
      if(mysql_query($query_upd))
        {
        echo "Смайлик успешно отредактирован!";
        }
      else 
        {
        echo "Произошла ошибка при редактировании смайлика в базе!";
        }
      }
    else 
      {
      echo "Произошла ошибка!<br>$error";
      }
    }
  elseif($operat=="del")
    {      
    $query_del="delete from chat_smiles where id='$id'";
    if(mysql_query($query_del))
      {
      echo "Смайлик успешно удалён!";
      }
    else 
      {
      echo "Произошла ошибка при удалении смайлика из базы!";
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
	document.smiley_image.src = "smiles/" + newimage;
        }
      //-->
      </script>
      <form action=adm.php?mode=smiles method=post>
      <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
      <tr>
      <td valign=top align=center class=tcat colspan=2>Добавление смайлика</td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>Код смайлика</td>
      <td valign=top class=alt2 width=50%>
      <input type=text name=smile_code size=25></td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>Уровень</td>
      <td valign=top class=alt2 width=50%><select name=smile_level>
      <?for($i=1;$i<=3;$i++) echo"<option value=$i>$i</option>";?></select></td>
      </tr>
      <tr>
      <td class=alt2 width=50%>Файл с изображением смайлика</td>
      <td class=alt2 width=50%>
      <select name=smile_url onchange="update_smiley(this.options[selectedIndex].value);" class=smallfont><?
      $dir=opendir("smiles");
      while($file=readdir($dir))
        {
        list($name_img,$perm)=explode(".",$file);
        if($file!="." && $file!=".." && $perm=="gif")
          {
          if(empty($sm_n)) $sm_n=$file;
          $opt="<option value=\"$file\" >".$file."</option>\r\n";
          echo $opt;
          }
        }?>
      </select> <img name="smiley_image" src="smiles/<?echo$sm_n;?>" border="0"></td>
      </tr>
      <tr>
      <td colspan=2 class=tcat width=100% align=center>
      <input type=submit name=add value="Добавить"></td>
      </tr>
      </table></form><?         
      }
    elseif($act=="edit")
      {
      $query_smile=mysql_query("select * from chat_smiles where id='$id';");
      list($id_sm,$smile_code,$smile_url,$smile_level)=mysql_fetch_row($query_smile);?>
      <script language="javascript" type="text/javascript">
      <!--
      function update_smiley(newimage)
        {
	document.smiley_image.src = "smiles/" + newimage;
        }
      //-->
      </script>
      <form action=adm.php?mode=smiles method=post>
      <input type=hidden name=id value="<?echo$id_sm;?>">
      <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
      <tr>
      <td valign=top align=center class=tcat colspan=2>Редактирование смайлика</td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>Код смайлика</td>
      <td valign=top class=alt2 width=50%>
      <input type=text name=smile_code value="<?echo$smile_code;?>" size=25></td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>Уровень</td>
      <td valign=top class=alt2 width=50%><select name=smile_level>
      <?
      for($i=1;$i<=3;$i++)
        {
        echo"<option value=$i";
        if($smile_level==$i) echo" selected";
        echo ">$i</option>";
        }?></select></td>
      </tr>
      <tr>
      <td class=alt2 width=50%>Файл с изображением смайлика</td>
      <td class=alt2 width=50%>
      <select name=smile_url onchange="update_smiley(this.options[selectedIndex].value);" class=smallfont><?
      $dir=opendir("smiles");
      while($file=readdir($dir))
        {
        list($name_img,$perm)=explode(".",$file);
        if($file!="." && $file!=".." && $perm=="gif")
          {
          if(empty($sm_n)) $sm_n=$file;
          $opt="\r\n<option value=\"$file\"";
          if($smile_url==$file) $opt.=" selected";
          $opt.=">".$file."</option>";
          echo $opt;
          }
        }?>
      </select> <img name="smiley_image" src="smiles/<?echo$sm_n;?>" border="0"></td>
      </tr>
      <tr>
      <td colspan=2 class=tcat width=100% align=center>
      <input type=submit name=edit value="Изменить"></td>
      </tr>
      </table></form><?         
      }
    else
      {
      $query_smiles=mysql_query("select * from chat_smiles order by level,id;");?>
      <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
      <tr>
      <td align=center colspan=5>
      <a href="adm.php?mode=smiles&act=add">Добавление смайлика</a></td>
      </tr>
      <tr>
      <td valign=top align=center class=tcat colspan=5>Управление смайликами</td>
      </tr>
      <tr>
      <td align=center class=tcat width=20%><b>Код смайлика</b></td>
      <td align=center class=tcat width=20%><b>Изображение смайлика</b></td>
      <td align=center class=tcat width=20%><b>Файл с изображением смайлика</b></td>
      <td align=center class=tcat width=10%><b>Уровень</b></td>
      <td align=center class=tcat width=30%><b>Действие</b></td>
      </tr><?
      while($array_smiles=mysql_fetch_array($query_smiles))
        {
        $id_smile=$array_smiles['id'];
        $smile_code=$array_smiles['code'];
        $smile_url=$array_smiles['url'];
        $smile_level=$array_smiles['level'];?>
        <tr>
        <td align=center class=alt2><b><?echo$smile_code;?></b></td>
        <td align=center class=alt2><img src="smiles/<?echo$smile_url;?>"></td>
        <td align=center class=alt2><?echo$smile_url;?></td>
        <td align=center class=alt2><?echo$smile_level;?></td>
        <td align=center class=alt2><a href="adm.php?mode=smiles&act=edit&id=<?echo$id_smile;?>">Изменить</a>
        <a href="adm.php?mode=smiles&operat=del&&id=<?echo$id_smile;?>"
        onClick="return confirm('Вы действительно хотите удалить этот смайлик?');">Удалить</a></td>
        </tr><?         
        }?>
      </table><?
      }
    }
?>