<?
if(!defined("A_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

  if($act=="edit")
    {
    $error=0;
    foreach($per as $key=>$val)
      {
      if(empty($per[$key])&&$per[$key]!="0") $error=1;
      }
    if(empty($error))
      {
      $query_upd=mysql_query("select * from chat_config where other order by other");
      while($arr_upd=mysql_fetch_array($query_upd))
        {
        if($arr_upd['value']!=stripslashes($per[$arr_upd['name']]))
          {
          $query="update chat_config set value='".$per[$arr_upd['name']]."' where name='$arr_upd[name]'";
          if(mysql_query($query)) {}
          else echo'<b>Ошибка SQL:</b> невозможно записать опцию <br>'.mysql_error().'<br>('.__FILE__.'::строка '.__LINE__.')';
          }
        }
      echo "<br><meta http-equiv='refresh' content='0; url=javascript:history.go(-1)'>\n";
      }
    else
      {
      echo "Произошла ошибка!<br>Не указан один или более параметров";
      }
    }
  else
    {?>
    <table cellpadding=1 cellspacing=3 width=100% border=0>
    </table>  
    <form action=adm.php method=post name=form>
    <input type=hidden name=act value=edit>
    <table cellpadding=1 cellspacing=3 width=100% border=0><?
    $query_opts=mysql_query("select * from chat_config where other order by other") or die(mysql_error());
    while($arr=mysql_fetch_array($query_opts))
      {
      $tegs=explode("|||",$arr['other']);
      $zn=($tegs[3])?explode('|',$tegs[3]):array('','','','');
      $vl=($tegs[4])?explode('|',$tegs[4]):array('','','','');
      echo"<tr><td class=tcat width=60%><span class=smallfont><b>$tegs[2]</b></span></td>\r\n
      <td class=alt2 width=40%>";
      if($tegs[1]==1)
        {
        echo "<input type=text name=\"per[$arr[name]]\" id=\"$arr[name]\" value=\"$arr[value]\" maxlength=\"255\" class=\"smallfont\" size=25>\n";
        if($arr['name']=='chat_url')
          {
          if(str_replace("www.","",$optval['chat_url'])!=str_replace("www.","","http://".getenv("HTTP_HOST")))
            {
            echo"\n<span class=smallfont>
            Ошибочный URL <a href=\"#\" style=\"color: red;\" onclick=\"form.chat_url.value='http://".getenv("HTTP_HOST")."'\"
            onMouseOver=\"window.status='У вас ошибочный URL, кликните сюда для замены';return true\"
            onMouseOut=\"window.status='';return true\">http://".getenv("HTTP_HOST")."</a></span>";
            }
          }
        }
      elseif($tegs[1]==2)
        {
        echo "<textarea name=\"per[$arr[name]]\" style=\"height: 100px;width: 250px;\" class=\"smallfont\">$arr[value]</textarea>\n";
        }
      elseif ($tegs[1] == 3)
        {
        while(list($key,$val) = each($zn))
          {
          echo "<input type=radio name=\"per[$arr[name]]\" value=\"$val\"";
          if ($arr['value']==$val) echo " checked";
          echo "> <span class=smallfont>$vl[$key]</span><br>\n";
          }
        }
      elseif($tegs[1] == 4)
        {
        echo "<select id=\"tipteg\" name=\"per[$arr[name]]\" class=smallfont>";
        while(list($key,$val)=each($zn))
          {
          echo "<OPTION value=\"$val\"";
          if ($arr['value']==$val) echo " selected";
          echo "> $vl[$key]</OPTION>\n";
          }
        echo "</select>";
        }
      echo"</td></tr>\r\n";
      }?>
    <tr>
    <td colspan=2 class=tcat align=center><input type=submit value="Редактировать"> <input type=reset value="Сбросить"></td>
    </tr>
    </table><?
    }
?>