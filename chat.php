<?
define("C_MOD",1);
include("inc/functions.php");

header("Content-type: text/javascript; charset=windows-1251");

if($mode=="priv")
  {?>
  document.write('<table cellpadding=1 cellspacing=0 border=1 align=center>');
  document.write('<tr>');
  document.write('<td width=70 align=center valign=top>�������</td>');
  document.write('<td width=70 align=center valign=top>���������</td>');
  document.write('<td width=70 align=center valign=top>��������</td>');
  document.write('<td width=70 align=center valign=top>�������� 2</td>');
  document.write('<td width=70 align=center valign=top>�������� 3</td>');
  document.write('<td width=70 align=center valign=top>���� ����</td>');
  document.write('<td width=70 align=center valign=top>������</td>');
  document.write('<td width=70 align=center valign=top>������</td>');
  document.write('<td width=70 align=center valign=top>������� ������</td>');
  document.write('<td width=70 align=center valign=top>�������</td>');
  document.write('<td width=70 align=center valign=top>�������� ����</td>');
  document.write('<td width=70 align=center valign=top>������ ����� �������</td>');
  document.write('<td width=70 align=center valign=top>�����</td>');
  document.write('<td width=70 align=center valign=top>����� 2</td>');
  document.write('<td width=70 align=center valign=top>����� 3</td>');
  document.write('</tr>');
<?
  $query_priv=mysql_query("select * from chat_level order by mess,id");
  while($arr_priv=mysql_fetch_array($query_priv))
    {
    $privel=explode("|",$arr_priv['priv']);
    echo "document.write('<tr>');";
    echo "document.write('<td>$arr_priv[title]</td>');\r\n";
    echo "document.write('<td>$arr_priv[mess]</td>');\r\n";
    $t=0;
    foreach($privel as $val)
      {
      if($t!=4)
        {
        if($val==1) echo "document.write('<td align=center>x</td>');\r\n";
        else echo "document.write('<td align=center>&nbsp;</td>');\r\n";
        }
      $t++;
      }
    echo "document.write('</tr>');\r\n";
    }
  echo "document.write('</table>');";
  }
else
  {
  $count=mysql_num_rows(mysql_query("select id from chat_onliners"));
  echo "document.write(' <a href=\"http://nnchat.ru\">��� ($count)</a>')";
  }
?>