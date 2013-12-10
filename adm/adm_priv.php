<?
if(!defined("A_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

  if(isset($add))
    {
    $query_add="insert into chat_level (title,mess,priv) values ('$nlev', '$nmess','0|0|0|0|0|0|0|0|0|0|0|0|0|0')";
    if(mysql_query($query_add))
      {
      echo "Уровень успешно добавлен!";
      }
    else
      {
      echo "Ошибка!<br>".mysql_error();
      }
    }
  elseif(isset($edit))
    {
    foreach($priv as $key=>$val)
      {
      if(empty($titles[$key])) mysql_query("delete from chat_level where id='$key'");
      else
        {
        if(!is_array($priv[$key])) $priv[$key]=array();
        for($i=0;$i<14;$i++)
          {
          if(!isset($priv[$key][$i])) $priv[$key][$i]=0;
          }
        ksort($priv[$key]);
        $str=implode("|",$priv[$key]);
        mysql_query("update chat_level set priv='$str',title='$titles[$key]',mess='$mess[$key]',image='$user_icon[$key]' where id='$key'");
        }
      }
    echo "Уровни успешно отредактированы!";
    }
  else
    {
    $query_priv=mysql_query("select * from chat_level order by mess,id");
    $skinsstr="";
    define("SK_MOD",1);
    include("theme/skins.php");?>
    <script language="javascript" type="text/javascript">
    <!--
    function update_icon(newimage,sel)
      {
      <?foreach($skins as $key=>$val)
        {
        echo "document.getElementById('icon_".$key."_'+sel).src = \"theme/$val/icons/\" + newimage;";
        $skinsstr.=", $val";
        }?>
      }
    //-->
    </script>
    <span class=smallfont><b>Оставьте строку названия уровня пустой для удаления уровня.<br>
    Установленные скины (порядок следования иконок):<?echo substr($skinsstr,1);?>.</span>
    <table cellpadding=1 cellspacing=3 border=0>
    <tr>
    <td class=tcat align=center>Уровень</td>
    <td class=tcat align=center>Сообщения</td>
    <td class=tcat align=center>Изображение</td>
    <td class=tcat align=center>смайлики</td>
    <td class=tcat align=center>смайлики 2</td>
    <td class=tcat align=center>смайлики 3</td>
    <td class=tcat align=center>цвет ника</td>
    <td class=tcat align=center>жирный</td>
    <td class=tcat align=center>курсив</td>
    <td class=tcat align=center>большой размер</td>
    <td class=tcat align=center>бегущий</td>
    <td class=tcat align=center>жирность ника</td>
    <td class=tcat align=center>менять текст статуса</td>
    <td class=tcat align=center>цвета</td>
    <td class=tcat align=center>цвета 2</td>
    <td class=tcat align=center>цвета 3</td>
    </tr>
    <form action="adm.php?mode=priv" method="post" name="form"><?
    while($arr_priv=mysql_fetch_array($query_priv))
      {
      $privel=explode("|",$arr_priv['priv']);
      echo "<input type=\"hidden\" name=\"priv[$arr_priv[id]][4]\" value=\"1\">\r\n";
      echo "<tr>";
      echo "<td class=alt2><input type=\"text\" name=\"titles[$arr_priv[id]]\" value=\"$arr_priv[title]\" class=smallfont></td>\r\n";
      echo "<td class=alt2><input type=\"text\" name=\"mess[$arr_priv[id]]\" value=\"$arr_priv[mess]\" size=7 class=smallfont></td>\r\n";
      echo "<td class=alt2>";
      echo "<select name=user_icon[$arr_priv[id]] onchange=\"update_icon(this.options[selectedIndex].value,$arr_priv[id]);\" class=smallfont>";
      $dir=opendir("theme/default/icons");
      while($file=readdir($dir))
        {
        list($name_img,$perm)=explode(".",$file);
        if($file!="." && $file!=".." && $perm=="gif")
          {
          $opt="\r\n<option value=\"$file\"";
          if($arr_priv['image']==$file) {$opt.=" selected"; $ic_n=$file; }
          $opt.=">".$file."</option>";
          echo $opt;
          }
        }
      echo "</select>";
      foreach($skins as $key=>$val)
        echo "<img id=\"icon_".$key."_$arr_priv[id]\" src=\"theme/$val/icons/$ic_n\" border=\"0\">";
      echo "</td>";
      $t=0;
      foreach($privel as $val)
        {
        if($t!=4)
          {
          echo "<td align=center class=alt2><input type=\"checkbox\" name=\"priv[$arr_priv[id]][$t]\" value=\"1\"";
          if($val==1)echo" checked";
          echo "></td>\r\n";
          }
        $t++;
        }
      echo "</tr>\r\n";
      }?>
    <tr><td colspan=17 align=center class=tcat><input type="submit" name="edit" value="Редактировать"></td></tr>
    <tr><td colspan=17 class=tcat>Новый уровень<br> Название: <input type="text" name="nlev"> Сообщения: <input type="text" name="nmess"><br>
    <input type="submit" name="add" value="Добавить"></td></tr>
    </table></form><?
    }
?>