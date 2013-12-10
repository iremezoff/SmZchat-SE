<?
if(!defined("C_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=../index.php'>";
  exit;
  }
if(file_exists("install")) die("<span style=\"color:#ff0000\">Удалите директорию <b>install</b></span>");
session_start();

require_once("db.php");
require_once("class.uploadimg.php");

$dbcnx=@mysql_connect($dblocation,$dbuser,$dbpasswd) or die("<p>В настоящий момент сервер базы данных не доступен, поэтому корректное отображение страницы  невозможною</p>");
@mysql_select_db($dbname,$dbcnx) or die("<p>В настоящий момент база данных недоступна, поэтому корректное отображение страницы невозможно.<p>");

if(isset($_SESSION['skin'])) $skin=$_SESSION['skin'];
else $skin="default";
$excs=array("chat_url");
$array_rand=array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","0","1","2","4","3","5","6","7","8","9");

// Конвертирует значения массива $_GET. Двумерные массивы не поддерживаются
foreach($_GET as $key_get=>$val_get)
  {
  if(is_array($_GET[$key_get]))
    foreach($_GET[$key_get] as $key2_get=>$val2_get) 
      {
      $temp_arr[$key2_get]=nl2br(htmlspecialchars(trim(stripslashes(str_replace("'","`",$val2_get)))));
      $$key_get=$temp_arr;
      }
  else $$key_get=nl2br(htmlspecialchars(trim(stripslashes(str_replace("'","`",$val_get)))));
  }

// Конвертирует значения массива $_POST с глубиной 3 (нет рекурсии, однако :))
foreach($_POST as $key_post=>$val_post)
  {
  if(!in_array($key_post,$excs))
    {
    if(is_array($_POST[$key_post]))
      {
      foreach($_POST[$key_post] as $key2_post=>$val2_post) 
        {
        if(!in_array($key2_post,$excs))
          {
          if(is_array($_POST[$key_post][$key2_post]))
            {
            $temp_arr[$key2_post]=$val2_post;
            foreach($temp_arr[$key2_post] as $key3_post=>$val3_post)
              {
              $temp_arr[$key2_post][$key3_post]=htmlspecialchars(trim(stripslashes(str_replace("'","`",$val3_post))));
              }
            }
          else
            $temp_arr[$key2_post]=htmlspecialchars(trim(stripslashes(str_replace("'","`",$val2_post))));
          $$key_post=$temp_arr;
          }
        else
          {
          $temp_arr[$key2_post]=trim(str_replace("'","`",$val2_post));
          $$key_post=$temp_arr;
          }
        }
      }
    else
      {
      $$key_post=htmlspecialchars(trim(stripslashes(str_replace("'","`",$val_post))));
      }
    }
  else $$key_post=trim(str_replace("'","`",$val_post));
  }

$query="select name,value from chat_config;";
if($result_info=mysql_query($query))
  {
  while($line=mysql_fetch_row($result_info))
    {
    $$line[0]=$line[1];
    $optval[$line[0]]=$line[1];
    }
  } 
else 
  { 
  print "Ошибка!!!";
  exit;
  }

if(!isset($_SESSION['slang']) || $_SESSION['slang']=="") $slang="russian";
else 
  {
  $check=mysql_result(mysql_query("select count(*) from chat_language where lang='$_SESSION[slang]'"),0,'count(*)');
  if($check<1) $slang="russian";
  else $slang=$_SESSION['slang'];
  }

$query_lang=mysql_query("select descr,value from chat_language where lang='$slang'") or die(mysql_error());
while($arr_lang=mysql_fetch_array($query_lang))
  {
  $expl=explode("|",$arr_lang['descr']);
  if(count($expl)>1)
    $lang[$expl[0]][$expl[1]]=$arr_lang['value'];
  else
    $lang[$arr_lang['descr']]=$arr_lang['value'];
  }

if(isset($_SESSION['room']))
  {
  $query_room=mysql_query("select id,botname,topic from chat_rooms where id='$_SESSION[room]'");
  $check_room=mysql_num_rows($query_room);
  if($check_room<1)
    {
    $min_room=mysql_result(mysql_query("select min(id) from chat_rooms"),0,'min(id)');
    mysql_query("update chat_onliners set room='$min_room'");
    $_SESSION['room']=$min_room;
    }
  list($id_room,$bot_name,$topic)=mysql_fetch_row($query_room);
  if(empty($topic)) $topic="&nbsp;";
  }

if(getenv('HTTP_X_FORWARDED_FOR')) $ip=getenv('HTTP_X_FORWARDED_FOR');
else $ip=getenv('REMOTE_ADDR');

$array_logout=array("Нас покидает +u+.");
$array_login=array("В чат влетает +u+.");
$query_says=mysql_query("select * from chat_botsay");
while($arr_says=mysql_fetch_array($query_says))
  {
  if($arr_says['type']==2) $array_logout[]=$arr_says['value'];
  if($arr_says['type']==1) $array_login[]=$arr_says['value'];
  }

$opt_cs=$opt_cs2="";
$query_clr="select * from chat_colors where ";
if(isset($_SESSION['slevel'][11])&&$_SESSION['slevel'][11]==1) $query_clr.="level='1' or ";
if(isset($_SESSION['slevel'][12])&&$_SESSION['slevel'][12]==1) $query_clr.="level='2' or ";
if(isset($_SESSION['slevel'][13])&&$_SESSION['slevel'][13]==1) $query_clr.="level='3' or ";
$query_clr.="level='0' order by id";
$query_colors=mysql_query($query_clr);

while($array_colors=mysql_fetch_array($query_colors))
  {
  $opt_cs.="<option value=\"$array_colors[code]\" style=\"background-color:#$array_colors[code];color:#ffffff\"";
  if(isset($_SESSION['ctxt'])&&$_SESSION['ctxt']==$array_colors['code']) $opt_cs.=" selected";
  $opt_cs.=">$array_colors[title]";
  $opt_cs2.="<option value=\"$array_colors[code]\" style=\"background-color:#$array_colors[code];color:#ffffff\"";
  if(isset($_SESSION['cnick'])&&$_SESSION['cnick']==$array_colors['code']) $opt_cs2.=" selected";
  $opt_cs2.=">$array_colors[title]";
  }

$query="select * from chat_smiles where ";
if(isset($_SESSION['slevel'][0])&&$_SESSION['slevel'][0]==1) $query.="level='1' or ";
if(isset($_SESSION['slevel'][1])&&$_SESSION['slevel'][1]==1) $query.="level='2' or ";
if(isset($_SESSION['slevel'][2])&&$_SESSION['slevel'][2]==1) $query.="level='3' or ";
$query.="level='0' order by length(code) desc";
$query_smiles=mysql_query($query);
while($array_smiles=mysql_fetch_array($query_smiles))
  {
  $codes_sm[]=$array_smiles['code'];
  $urls_sm[]="<img src=\"smiles/$array_smiles[url]\" border=0>";
  }

$upd_str=explode(" ",date("Y m W z",$last));
$now_str=explode(" ",date("Y m W z"));
$time=time();
if($upd_str[0]!=$now_str[0])
  {
  mysql_query("update chat_stat set count=0 where type='mess_year' or type='size_year'");
  }
if($upd_str[1]!=$now_str[1])
  {
  mysql_query("update chat_stat set count=0 where type='mess_month' or type='size_month'");
  }
if($upd_str[2]!=$now_str[2])
  {
  mysql_query("update chat_stat set count=0 where type='mess_week' or type='size_week'");
  }
if($upd_str[3]!=$now_str[3])
  {
  $lastday=mysql_result(mysql_query("select `count` from chat_stat where type='auth'"),0,'count');
  $recauth=mysql_result(mysql_query("select `count` from chat_stat where type='rec_auth'"),0,'count');
  if($lastday>$recauth)
    {
    mysql_query("update chat_config set value='".date("Y-m-d",$last)."' where name='rec_auth'");
    mysql_query("update chat_stat set count='$lastday' where type='rec_auth'");
    $rec_auth=date("Y-m-d",$last);
    }
  mysql_query("update chat_stat set count=0 where type='mess_day' or type='auth' or type='size_day'");
  save_logs();
  }
mysql_query("update chat_config set value='$time' where name='last'");

function encode($string,$sm)
  {
  global $codes_sm,$urls_sm;
  $string=preg_replace("#\[b\](.+?)\[/b\]#is"     , "<b>\\1</b>",$string ); 
  $string=preg_replace("#\[i\](.+?)\[/i\]#is"     , "<i>\\1</i>",$string );
  $string=preg_replace("#\[u\](.+?)\[/u\]#is"     , "<u>\\1</u>",$string );
  $string=preg_replace("#\[sup\](.+?)\[/sup\]#is" , "<sup>\\1</sup>",$string );
  $string=preg_replace("#\[sub\](.+?)\[/sub\]#is" , "<sub>\\1</sub>",$string );

  $string=preg_replace("#\[center\](.+?)\[/center\]#is" , "<div align='center'>\\1</div>" , $string ); 
  $string=preg_replace("#\[right\](.+?)\[/right\]#is"   , "<div align='right'>\\1</div>"  , $string ); 
  $string=preg_replace("#\[left\](.+?)\[/left\]#is"     , "<div align='left'>\\1</div>"   , $string ); 

  $string=preg_replace("#\(c\)#i"  , "&copy;" , $string ); 
  $string=preg_replace("#\(tm\)#i" , "<sup>TM</sup>" , $string ); 
  $string=preg_replace("#\(r\)#i"  , "&reg;"  , $string ); 

  $string=preg_replace("#\[color=([^\]]+)\](.+?)\[/color\]#is" , "<span style='color:\\1'>\\2</span>"       , $string );  

  $string=str_replace("\n","<br />",$string);
  $string=eregi_replace('(http://.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\\0" target="_blank">\\0</a>', $string);
  $string=eregi_replace("[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*","<a href=\"mailto:\\0\">\\0</a>",$string);
  $query_smiles=mysql_query("select * from expg_smiles order by length(code) desc");

  if($sm==1) $string=str_replace($codes_sm,$urls_sm,$string);
  return $string;
  }


function table_smiles($form,$area)
  {?>
  <script language=JavaScript><!--
  var ico;
  function smile(ico) {document.<?echo$form;?>.<?echo$area;?>.value=document.<?echo$form;?>.<?echo$area;?>.value+ico;}
  //--></script>
  <a href="#" oncontextmenu="toggle_group(1); save_group_prefs(1); return false" onclick="toggle_group(1); return false;">Смайлики</a>
  <div id=group_1 style="display: none" onmouseover="this.className='navlink-hover';">
  <table cellpadding=0 cellspacing=0 border=0 ondblclick="toggle_group(1); return false;">
  <tr>
  <td valign=top width=60%>
  <table cellpadding=1 cellspacing=1 width=100% border=0>
  <tr>
  <td valign=top colspan=10><b>Смайлики</b></td>
  </tr><?
  $i=1;
  $query_smiles=mysql_query("select * from chat_smiles group by url desc");
  while($array_smiles=mysql_fetch_array($query_smiles))
    {
    if($i==1) echo "<tr>";?>
    <td align=center>
    <a href="javascript: smile(' <?echo$array_smiles['code'];?> ');">
    <img src="smiles/<?echo$array_smiles['url'];?>" border=0></a></td><?
    if($i==5) {$i=1; echo "</tr>";}     
    else $i++;
    }?>
  </table></td>
  <td valign=top width=40%>
  <table cellpadding=1 cellspacing=1 width=100% border=0>
  <tr>
  <td valign=top colspan=10><b>BBcode</b></td>
  </tr>
  <tr>
  <td valign=top width=85>
  <button onclick="javascript: smile('[b] [/b]');"><b>ж</b></button>&nbsp;&nbsp;&nbsp;
  <button onclick="javascript: smile('[i] [/i]');"><i>к</i></button>&nbsp;&nbsp;&nbsp;
  <button onclick="javascript: smile('[u] [/u]');"><u>п</u></button></td>
  </tr>
  </table></td>
  </tr>
  </table></div><?
  }


function cmonth($string) 
  { 
  $month=array(1=>"января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"); 
  return $month[$string]; 
  } 

function dbdate($string)
  {
  global $reg, $persearch1;
  if(preg_match("#^(([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}))+([\s]+([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}))*$#",$string,$reg))
    {
    $string=(int)$reg[4]." ".cmonth((int)$reg[3])." ".(int)$reg[2];
    if(isset($reg[5]) && isset($reg[6])) $string.=" в $reg[6]:$reg[7]";
    }
  else
    {
    echo "Неверный формат даты: $string";
    }
  return $string;
  }


function resizeimg($filename, $smallimage, $w, $h) 
  { 
  $ratio=$w/$h; 
  $size_img=getimagesize($filename); 
  if(($size_img[0]<$w) && ($size_img[1]<$h)) return true; 
  $src_ratio=$size_img[0]/$size_img[1]; 
  if($ratio<$src_ratio) $h=$w/$src_ratio; 
  else $w=$h*$src_ratio; 
  $dest_img=imagecreatetruecolor($w,$h);   
  $white=imagecolorallocate($dest_img, 255, 255, 255);        
  if($size_img[2]==2) $src_img=imagecreatefromjpeg($filename);                       
  elseif($size_img[2]==1) $src_img=imagecreatefromgif($filename);                       
  elseif($size_img[2]==3) $src_img=imagecreatefrompng($filename); 
  imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $w, $h, $size_img[0], $size_img[1]);                 
  if($size_img[2]==2) imagejpeg($dest_img, $smallimage);                       
  elseif($size_img[2]==1) imagegif($dest_img, $smallimage);                       
  elseif($size_img[2]==3) imagepng($dest_img, $smallimage); 
  imagedestroy($dest_img); 
  imagedestroy($src_img); 
  return true;          
  }

########## Удаление трупов #################

function delusers()
  {
  global $user_prunetime,$array_logout;
  $time=time();
  $remtime=time()-$user_prunetime;
  $query_to=mysql_query("select user,room,lastactivity,start FROM chat_onliners WHERE lastactivity<$remtime");
  while($array_to=mysql_fetch_array($query_to))
    {
    $message=str_replace("+u+","<b>$array_to[user]</b>",$array_logout[array_rand($array_logout,1)])." (timeout)";
    if($array_to['lastactivity']<$remtime)
      {
      $tot_time=ceil(($array_to['lastactivity']-$array_to['start'])/60);
      mysql_query("DELETE FROM chat_onliners WHERE user='$array_to[user]' and lastactivity<$remtime") ;
      mysql_query("update chat_users set inchat=inchat+'$tot_time',date_last='".date("Y-m-d H:i:s",$array_to['lastactivity'])."' where user='$array_to[user]'");  
      mysql_query("INSERT INTO chat_messages (room, user, message, time) VALUES ('$array_to[room]', 'Хранитель', '$message', '$time')");
      }
    }
  return;
  }

delusers();
########## /Удаление трупов ################

########## Удаление банов #################
  $query_bans=mysql_query("select * from chat_banlist order by id asc");
  while($array_bans=mysql_fetch_array($query_bans))
    {
    $time=time();
    if(($time-(int)$array_bans['mute_settime'])>=(int)$array_bans['mute_time'])
      {
      mysql_query("insert into chat_logs values ('','$array_bans[user]','','<b>истёк бан</b>','$time')");
      mysql_query("delete from chat_banlist where user='$array_bans[user]'");
      }
    }
########## /Удаление банов ################

########## Удаление логов #################
  $time=time();
  $secs=$time-60*60*24*$clear_logs;
  $query=mysql_query("delete from chat_logs where time<'$secs'");
########## /Удаление логов ################

########## Удаление жалоб #################
  $time=time();
  $secs2=$time-60*60*24*$clear_compl;
  $query=mysql_query("delete from chat_compl where time<'$secs2'");
########## /Удаление жалоб ################

function pages($string)
  {
  global $maxinp,$page,$pages,$mode;
  if($string>$maxinp)
    {
    if(!$page) $page="1";
    for($i=1;$i<=$pages;$i++)
      {
      if($i!=$page)
        {
        $st="<a href=\"$_SERVER[PHP_SELF]?";
        if($mode) $st.="mode=$mode&";
        $st.="page=$i\">$i</a> ";
        echo $st;
        }
      else echo "$i ";
      }
    }
  else echo "1";
  }

function my_bcmod($x,$y)
  {
  $z=(int)($x/$y);
  return $x-$y*$z;
  }

function my_bcdiv($x,$y)
  {
  $z=(int)($x/$y);
  return $z;
  }

function h_m($time)
  {
  $mins=my_bcmod($time,60);
  $hours=my_bcdiv($time,60);
  if(my_bcmod($mins,10)>=5 || my_bcmod($mins,10)==0 || ($mins>=10 && $mins<=20)) $strmins="минут";
  elseif(my_bcmod($mins,10)==1) $strmins="минута";
  else $strmins="минуты";
  if(my_bcmod($hours,10)>=5 || my_bcmod($hours,10)==0|| (my_bcmod($hours,100)>=10 && my_bcmod($hours,100)<=20)) $strhours="часов";
  elseif(my_bcmod($hours,10)==1) $strhours="час";
  else $strhours="часа";
  return "$hours $strhours $mins $strmins";
  }

function save_logs()
  {
  global $last;
  $room=0;
  $log="";
  $query_messlogs=mysql_query("select * from chat_messlogs order by room");
  while($arr_messlogs=mysql_fetch_array($query_messlogs))
    {
    if($arr_messlogs['room']!=$room)
      {
      $title_room=mysql_result(mysql_query("select name from chat_rooms where id='$arr_messlogs[room]'"),0,'name');
      $log.="Комната: $title_room\r\n";
      $room=$arr_messlogs['room'];
      }
    $log.=$arr_messlogs['message']."\r\n";
    }
  file_put_contents("./logs/log.txt",$log);
  include("phpzip.inc.php");
  $zip=new PHPZip();
  $zip->Zip(array("./logs/log.txt"),"./logs/log_".date("Y-m-d",$last).".zip");
  mysql_query("TRUNCATE TABLE chat_messlogs");
  unlink("./logs/log.txt");
  return true;
  }
?>