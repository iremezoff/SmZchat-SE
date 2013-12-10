<?
if(!defined("A_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

  if(isset($edit))
    {
    $error=0;
    if($per['type_str']!=3) $per['font']="";
    if(empty($per['noise'])) $per['noise']=1000;
    if($per['type_str']>3 && $per['type_str']<1) $error=1;
    if($per['type_str']==3 && !file_exists("./fonts/$per[font].ttf")) $error=1;
    if($error!=1)
      {
      mysql_query("update chat_config set value='$per[type_str]' where name='type_str'");
      mysql_query("update chat_config set value='$per[font]' where name='font'");
      mysql_query("update chat_config set value='$per[noise]' where name='noise'");
      }
    echo "<br><meta http-equiv='refresh' content='0; url=javascript:history.go(-1)'>\n";
    }
  else
    {?>
    <table cellpadding=1 cellspacing=3 width=100% border=0>
    </table>
    <form action=adm.php?mode=reg method=post name=form>
    <table cellpadding=1 cellspacing=3 width=100% border=0>
    <tr><td class=tcat width=60%><span class=smallfont><b>Изображение при регистрации</b></span></td>
    <td class=alt2 width=40%><select id="type_str" name="per[type_str]" class=smallfont onChange="if(form.type_str.value!=3) form.font.disabled=true; else form.font.disabled=false; if(form.type_str.value==1) form.noise.disabled=true; else form.noise.disabled=false;">
    <option value=1<?if($type_str==1) echo" selected";?>>Без изображения
    <option value=2<?if($type_str==2) echo" selected";?>>Средствами GD2
    <option value=3<?if($type_str==3) echo" selected";?>>Средствами GD2 с использованием TTF
    </select>
    </td>
    </tr>
    <tr><td class=tcat width=60%><span class=smallfont><b>Шрифт (директория fonts)</b></span></td>
    <td class=alt2 width=40%><select id="font" name="per[font]" class=smallfont <?if($type_str!=3)echo"disabled";?>>
    <?
    $scanttf=opendir("./fonts");
    while($readttf=readdir($scanttf))
      {
      if(preg_match("#^([a-z0-9_]+)\.ttf$#is",$readttf,$parse))
        {
        echo "<option value=\"$parse[1]\"";
        if($parse[1]==$font) echo" selected";
        echo ">$parse[1]\r\n";
        }
      }
    ?>
    </select>
    </td>
    </tr>
    <tr><td class=tcat width=60%><span class=smallfont><b>Уровень шума на изображении</b></span></td>
    <td class=alt2 width=40%><input type=text name="per[noise]" id="noise" value="<?echo$noise;?>" maxlength="4" class="smallfont" size=25 <?if($type_str==1)echo"disabled";?>></td>
    </tr>
    <tr><td class=tcat width=60%><span class=smallfont><b>Текущее изображение:</b></span></td>
    <td class=alt2 width=40%><img src="../img.php"></td>
    </tr>
    <tr>
    <td colspan=2 class=tcat align=center><input type=submit name=edit value="Редактировать"> <input type=reset value="Сбросить"></td>
    </tr>
    </table></form><?
    }
?>