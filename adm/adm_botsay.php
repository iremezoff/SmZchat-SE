<?
if(!defined("A_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

  if(isset($add))
    {
    $error="";
    if(!$say) $error.="�� �� ����� ������!<br>";
    if($type!=1&&$type!=2) $error.="�� �� ������� ��� �������!<br>";
    if(empty($error))
      {
      $query_say="insert into chat_botsay values ('','$say','$type')";
      if(mysql_query($query_say))
        {
        echo "������� ������� �������";
        }
      }
    else
      {
      echo "��������� ������!<br>$error";
      }
    }
  elseif(isset($edit))
    {
    foreach($says as $key=>$val)
      {
      if(!$val) mysql_query("delete from chat_botsay where id='$key'");
      else mysql_query("update chat_botsay set value='$val',type='".$type[$key]."' where id='$key'");
      }
    echo "������� ������� ���������������";
    }
  else
    {
    $query_says=mysql_query("select * from chat_botsay order by id");?>
    <span class=smallfont>*<b>�������� ���� ������, ���� ������ ������� ���� ��� ��������� ���������������</b><br>
    � ������� ����������� ������� '+u+' (��� �������) ��� ������� ����. � ��������� ������ ��� �� ����� ������������ ��� �����/������</span>
    <form action=adm.php?mode=botsay method=post>
    <table cellpadding=1 cellspacing=3 width=100% border=0>
    <tr>
    <td valign=top align=center class=tcat colspan=2>�������</td>
    </tr><?
    while($array_says=mysql_fetch_array($query_says))
      {?>
      <tr>
      <td valign=top class=alt2 align=center>
      <input type=text name=says[<?echo$array_says['id'];?>] value="<?echo$array_says['value'];?>" class=smallfont size=30></td>
      <td valign=top class=alt2 align=center><input type=radio name=type[<?echo$array_says['id'];?>] value=1<?if($array_says['type']==1)echo" checked";?>> ����
      <input type=radio name=type[<?echo$array_says['id'];?>] value=2<?if($array_says['type']==2)echo" checked";?>> �����</td>
      </tr><?
      }?>
    <tr>
    <td valign=top class=tcat align=center colspan=2>
    <input type=text name=say class=smallfont size=25> <input type=radio name=type value=1> ����
    <input type=radio name=type value=2> ����� <input type=submit name=add value="��������" class=smallfont></td>
    </tr>
    <tr>
    <td valign=top align=center class=tcat colspan=2>
    <input type=submit name=edit value="��������"></td>
    </tr>
    </table>
    </form><?
    }
?>