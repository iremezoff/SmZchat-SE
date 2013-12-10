<?
if(!defined("A_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

  if(isset($dumping))
    {
    $dump=$test="";
    $z=$i=$u=0;
    $q1=mysql_query("show tables");
    $z=1;
    while($a1=mysql_fetch_array($q1))
      {
      list($pref,$table)=explode("_",$a1[0]);
      if($pref=="chat")
        {
        $dump.="DROP TABLE IF EXISTS `$a1[0]`;\r\n";
        $dump.="CREATE TABLE `$a1[0]` (\r\n";
        $q2=mysql_query("describe $a1[0]");
        while($a2=mysql_fetch_row($q2))
          {
          $dump.="&nbsp;&nbsp;`$a2[0]` $a2[1] ";
          if($a2[2]=="YES") $dump.="default NULL,\r\n";
          elseif($a2[5]=="auto_increment") $dump.="NOT NULL auto_increment,\r\n";
          elseif($a2[4]!="NULL") $dump.="NOT NULL default '$a2[4]',\r\n";
          if($a2[3]=="PRI")
            {
            $s[$z]="&nbsp;&nbsp;PRIMARY KEY (`$a2[0]`)";
            $z++;
            }
          elseif($a2[3]=="UNI")
            {
            $s[$z]="&nbsp;&nbsp;UNIQUE KEY (`$a2[0]`)";
            $z++;
            }
          $i++;
          }
        $z=1;
        $count=count($s);
        foreach($s as $key=>$val)
          {
          if($key!=$count) $dump.=$s[$key].",\r\n";
          else $dump.=$s[$key]."\r\n";
          }
        $dump.=");\r\n\r\n";
        if($test!=$a1[0]) $i=$i-$u;
        $test=$a1[0];
        $u=$i;
        $q3=mysql_query("select * from $a1[0]");
        while($a3=mysql_fetch_row($q3))
          {
          $dump.="INSERT INTO $a1[0] VALUES ($a3[0]";
          $t=$i-1;
          for($w=1;$w<=$t;$w++) $dump.=",'".htmlspecialchars(trim(mysql_escape_string($a3[$w])))."'";
          $dump.=");\r\n";
          $dump.="";
          }
        $dump.="\r\n\r\n";
        }
      }?>
    <table cellpadding=1 cellspacing=3 border=0 width=100% height=700>
    <tr>
    <td valign=top height=20 align=center class=tcat>Дамп БД</td>
    </tr>
    <tr>
    <td valign=top class=alt2>
    <textarea style="width: 1000; height: 100%" wrap="OFF"><?echo$dump;?></textarea>
    </td>
    </tr>
    </table><?
    }
  else
    {?>
    <table cellpadding=1 cellspacing=3 border=0 width=100%>
    <tr>
    <td valign=top align=center class=tcat>Дамп БД</td>
    </tr>
    <tr>
    <td valign=top align=center class=alt2>
    <form action=adm.php?mode=dump method=post>
    <input type=submit name=dumping value="Начать резервирование">
    </form></td>
    </tr>
    </table><?
    }
?>