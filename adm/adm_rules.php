<?
if(!defined("A_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

  if(isset($editrs))
    {
    if(count($rules)>0)
      {
      foreach($rules as $key=>$val)
        {
        foreach($val as $key2=>$val2)
          {
          if(!empty($val2)) mysql_query("update chat_rules set content='$val2' where id='$key2'");
          else mysql_query("delete from chat_rules where id='$key2'");
          }
        }
      foreach($rules_cats as $key2=>$val2)
        {
        if($val2) mysql_query("update chat_rules_cats set categ='$val2' where id='$key2'");
        elseif(!$val2)
          {
          mysql_query("delete from chat_rules where id_cat='$key2'");
          mysql_query("delete from chat_rules_cats where id='$key2'");
          }
        }
      echo "������� ������� ���������������";
      }
    else
      {
      echo "�� ����������� �� ������ �������";
      }
    }
  elseif(isset($addrule))
    {
    $count=0;
    foreach($newrule as $key=>$val)
      {
      if($val) {$count++;$val2=$val;$key2=$key;}
      }
    if($count<1) $error.="�� �� ����� ���������� ������ �������";
    if(empty($error))
      {
      $query_newrule="insert into chat_rules values ('','$key2','$val2')";
      if(mysql_query($query_newrule))
        {
        echo "������� ������� ��������� � ����!";
        }
      }
    else
      {
      echo "��������� ������!<br>$error";
      }
    }
  elseif(isset($addcat))
    {
    if(!$newcat) $error.="�� �� ����� �������� ����� ���������";
    if(empty($error))
      {
      $query_newcat="insert into chat_rules_cats values ('','$newcat')";
      if(mysql_query($query_newcat))
        {
        echo "����� ��������� ������� ��������� � ����!";
        }
      }
    else
      {
      echo "��������� ������!<br>$error";
      }
    }
  else
    {
    $query_cats=mysql_query("select * from chat_rules_cats order by id");?>
    <span class=smallfont>* <b>�������� ���� ������, ���� ������ ������� ������� ��� ��������� ������</b></span>
    <form action=adm.php?mode=rules method=post>
    <table cellpadding=1 cellspacing=3 width=100% border=0>
    <tr>
    <td valign=top class=tcat align=center>�������������� ������</td>
    </tr><?
    while($array_cats=mysql_fetch_array($query_cats))
      {
      echo "<tr>
      <td valign=top class=tcat align=center>
      <b><input type=text name=rules_cats[$array_cats[id]] value='$array_cats[categ]' size=50></b></td>
      </tr>
      <tr>
      <td valign=top class=alt2><ol>";
      $query_rules=mysql_query("select * from chat_rules where id_cat='$array_cats[id]' order by id");
      while($array_rules=mysql_fetch_array($query_rules))
        {
        echo "<li><input type=text name=rules[$array_cats[id]][$array_rules[id]] class=smallfont value='$array_rules[content]' size=100></li>";
        }
      echo "<br>������ �������: <input type=text name=newrule[$array_cats[id]] class=smallfont soze=100></textarea>
      <input type=submit name=addrule value=\"��������!\" class=smallfont>
      </td>
      </tr>
      <tr><td class=tcat><hr width=80%></td></tr>";
      }?>
    <tr><td>&nbsp;</td></tr>
    <tr>
    <td valign=top class=tcat align=center><br>
    <input type=submit name=editrs value="�������� �������"><br><br></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
    <td valign=top class=tcat>
    ����� ���������: <input type=text name=newcat size=25> <input type=submit name=addcat value="��������">
    </table></form><?
    }
?>