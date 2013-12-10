<?
if(!defined("A_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

  $count=mysql_result(mysql_query("select count(*) from chat_compl"),0,'count(*)');
  $pages=ceil($count/20);
  if($page=="" or $page=="0") $lim="0";
  else $lim=($page-1)*20;
  $query_compl=mysql_query("select * from chat_compl order by time desc limit $lim,20");?>
  <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
  <tr>
  <td valign=top align=center class=tcat colspan=4>Жалобы на модеров</td>
  </tr>
  <tr>
  <td align=center class=tcat width=20%><b>Пользователь</b></td>
  <td align=center class=tcat width=20%><b>Модератор</b></td>
  <td align=center class=tcat width=20%><b>Дата</b></td>
  <td align=center class=tcat width=40%><b>Текст</b></td>
  </tr>
  <tr>
  <td class=alt2 colspan=4><span class=smallfont>Всего: <b><?echo$count;?></b>. Страницы: <b><?pages($count);?></b></span></td>
  </tr><?
  while($array_compl=mysql_fetch_array($query_compl))
    {
    $date=date("d.m.Y H:i:s",$array_compl['time']);
    echo "<tr>
    <td align=center class=alt2><b>$array_compl[user]</b></td>
    <td align=center class=alt2>$array_compl[moder]</td>
    <td align=center class=alt2>$date</td>
    <td align=center class=alt2><span class=smallfont>$array_compl[text]</span></td>
    </tr>";
    }
  echo "</table>";
?>