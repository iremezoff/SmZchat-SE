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
    if(!$color_code) $error.="�� ������ ��� �����!";
    if(!$color_title) $error.="�� ������� �������� �����!";
    if(empty($error))
      {
      $query_add="INSERT INTO chat_colors VALUES ('','$color_code','$color_title','$color_level')";
      if(mysql_query($query_add))
        {
        echo "���� ������� �������� � ����!";
        }
      else 
        {
        echo "��������� ������ ��� ���������� ����� � ����! ��������, ����� ��� ��� ����������.";
        }
      }
    else 
      {
      echo "��������� ������!<br>$error";
      }
    }
  elseif(isset($edit))
    {
    $error="";
    if(!$color_code) $error.="�� ������ ��� �����!";
    if(!$color_title) $error.="�� ������� �������� �����!";
    if(empty($error))
      {
      $query_upd="update chat_colors set code='$color_code',title='$color_title',level='$color_level' where id='$id'";
      if(mysql_query($query_upd))
        {
        echo "���� ������� ��������������!";
        }
      else 
        {
        echo "��������� ������ ��� �������������� ����� � ����!";
        }
      }
    else 
      {
      echo "��������� ������!<br>$error";
      }
    }
  elseif($operat=="del"&&$id!=1)
    {      
    $query_del="delete from chat_colors where id='$id'";
    if(mysql_query($query_del))
      {
      echo "���� ������� �����!";
      }
    else 
      {
      echo "��������� ������ ��� �������� ����� �� ����!";
      }
    }      
  else
    {
    if($act=="add")
      {?>
      <form action=adm.php?mode=colors method=post name="form">
      <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
      <tr>
      <td valign=top align=center class=tcat colspan=2>���������� �����</td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>�������� �����</td>
      <td valign=top class=alt2 width=50%>
      <input type=text name=color_title size=25></td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>��� �����</td>
      <td valign=top class=alt2 width=50%>
      <b>#</b><input type=text name="color_code" size=25 maxlength=6 onchange="document.getElementById('color').style.background='#'+document.form.color_code.value"> <span id="color" style="background:none;width: 100px"></span></td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>�������</td>
      <td valign=top class=alt2 width=50%><select name=color_level>
      <?for($i=1;$i<=3;$i++) echo"<option value=$i>$i</option>";?></select></td>
      </tr>
      <tr>
      <td colspan=2 class=tcat width=100% align=center>
      <input type=submit name=add value="��������"></td>
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
      <td valign=top align=center class=tcat colspan=2>�������������� �����</td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>�������� �����</td>
      <td valign=top class=alt2 width=50%>
      <input type=text name="color_title" size=25 value="<?echo$color_title;?>"></td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>��� �����</td>
      <td valign=top class=alt2 width=50%>
      #<input type=text name="color_code" size=25 value="<?echo$color_code;?>" maxlength=6 onchange="document.getElementById('color').style.background='#'+document.form.color_code.value"> <span id="color" style="background-color:#<?echo$color_code;?>;width:100px"></span></td>
      </tr>
      <tr>
      <td valign=top class=alt2 width=50%>�������</td>
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
      <input type=submit name=edit value="��������"></td>
      </tr>
      </table></form><?         
      }
    else
      {
      $query_colors=mysql_query("select * from chat_colors where level>'0' order by level,id;");?>
      <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
      <tr>
      <td align=center colspan=5>
      <a href="adm.php?mode=colors&act=add">���������� �����</a></td>
      </tr>
      <tr>
      <td valign=top align=center class=tcat colspan=5>���������� �������</td>
      </tr>
      <tr>
      <td align=center class=tcat width=20%><b>�������� �����</b></td>
      <td align=center class=tcat width=20%><b>��� �����</b></td>
      <td align=center class=tcat width=20%><b>����</b></td>
      <td align=center class=tcat width=10%><b>�������</b></td>
      <td align=center class=tcat width=30%><b>��������</b></td>
      </tr><?
      while($array_colors=mysql_fetch_array($query_colors))
        {?>
        <tr>
        <td align=center class=alt2><b><?echo$array_colors['title'];?></b></td>
        <td align=center class=alt2>#<?echo$array_colors['code'];?></td>
        <td align=center class=alt2 style="background:#<?echo$array_colors['code'];?>;width:150px"></td>
        <td align=center class=alt2><?echo$array_colors['level'];?></td>
        <td align=center class=alt2><a href="adm.php?mode=colors&act=edit&id=<?echo$array_colors['id'];?>">��������</a>
        <a href="adm.php?mode=colors&operat=del&&id=<?echo$array_colors['id'];?>"
        onClick="return confirm('�� ������������� ������ ������� ���� ����?');">�������</a></td>
        </tr><?         
        }?>
      </table><?
      }
    }
?>