<?
define("C_MOD",1);
include("inc/functions.php");
if(empty($mode)) $mode="";
if(empty($act)) $act="";
if($mode=="edit"&&isset($_SESSION['suser']))
  {?>
  <html>
  <head>
  <title><?echo$lang['im'];?> <?echo$_SESSION['suser'];?></title>
  <meta http-equiv="content-type" Content="text/html;charset=windows-1251">
  <link href="design/main.css" type=text/css rel=stylesheet>
  </head>
  <body><?
  if(isset($act) && $act=="up_photo")
    {
    $count_photos=mysql_result(mysql_query("select count(id) from chat_photos where user='$_SESSION[suser]'"),0,'count(id)');
    if(($count_photos+1)>$max_photos) echo "<center>Вы исчерпали лимит максимального числа загружаемых фотографий!</center>";
    elseif(!$title) echo "<center>Вы не указали название фотографии.</center>";
    else
      {
      if($_FILES['newphoto']['name'])
        {
        $action=new uploadimg;
        $action->lang=array("complete"=>array("load"=>$lang['complete']['load']),"error"=>array("ext"=>$lang['error']['ext'],"width"=>$lang['error']['width'],"height"=>$lang['error']['height'],"size"=>$lang['error']['size'],"small"=>$lang['error']['small'],"copy"=>$lang['error']['copy']));
        $action->check_empty($_FILES['newphoto']['name'],$lang['error']['path']);
        $action->check_empty($_FILES['newphoto']['tmp_name'],$lang['error']['image']);
        $action->check_empty($_FILES['newphoto']['size'],$lang['error']['notfile']);
        if(empty($action->errors))
          {
          $arr=array("photos", "photos/small", "1", $widthmax, $heightmax, $sizemax, "150", "113", $_FILES['newphoto']['tmp_name'],$_FILES['newphoto']['name'],
          date("YmdHis"), round($_FILES['newphoto']['size']/1024));
          list($action->path1, $action->path2, $action->load_small, $action->width_max, $action->height_max,
          $action->size_max, $action->width_to, $action->height_to, $action->image, $action->image_name_or, $action->image_name, $action->size)=$arr;
          $action->setPars();
          $action->check_pars();
          }
        if(empty($action->errors))
          {
          $action->upload();
          mysql_query("insert into chat_photos values ('','$_SESSION[suser]','$title','$action->image_name','".$action->width."x".$action->height."','$action->size','0')");
          echo "<center>$action->total</center>";
          }
        else
          {
          echo "<center>$action->errors</center>";
          }
        }
      else
        {
        echo "<center>Произошла ошибка! Невозможно загрузить фотографию на сервер.</center>";
        }
      }
    }
  elseif(isset($act) && $act=="del_ph")
    {
    if(count($photos)>0)
      {
      foreach($photos as $key=>$val)
        {
        if($val==1)
          {
          $path_img=mysql_result(mysql_query("select filename from chat_photos where id='$key' and user='$_SESSION[suser]'"),0,'filename');
          mysql_query("delete from chat_photos where id='$key' and user='$_SESSION[suser]'");
          @unlink("./photos/$path_img");
          @unlink("./photos/small/$path_img");
          }
        }
      echo "<center>Выбранные фотографии успешно удалены!</center>";
      }
    else
      {
      echo "<center>Не удалено ни одной фотографии!</center>";
      }
    }
  elseif($act=="edit")
    {
    $error="";
    if(!empty($new_pass))
      {
      $ltpass=mysql_result(mysql_query("select pass from chat_users where user='".strtolower($_SESSION['suser'])."'"),0,'pass');
      if(empty($old_pass)) $error.=$lang['error']['oldpass']."<br>";
      elseif($ltpass!=md5($old_pass)) $error.=$lang['error']['oldpass2']."<br>";
      elseif($new_pass!=$confirm_pass) $error.=$lang['error']['passes']."<br>";
      }
    if(empty($mail)) $error.=$lang['error']['mail']."<br>";
    elseif(!preg_match("/^[a-zA-Z0-9_]+(([a-zA-Z0-9_.-]+)?)@[a-zA-Z0-9+](([a-zA-Z0-9_.-]+)?)+\.+[a-zA-Z]{2,4}$/",$mail))
      $error.=$lang['error']['mail2']."<br>";
    if(!$name)					{$error.="Вы не ввели имя<br>";$name="";}
    if($sex!=1 && $sex!=2)			$error.="Вы не указали пол<br>";
    if(!preg_match("#^[0-9]{1,2}$#",$day_burn))	{$error.="Вы не указали день рождения<br>";$day_burn="";}
    if(!preg_match("#^[0-9]{1,2}$#",$month_burn) && $month_burn>12)
						{$error.="Вы не указали месяц рождения<br>";$month_burn="";}
    if(!preg_match("#^[0-9]{4}$#",$year_burn))	{$error.="Вы не указали год рождения<br>";$year_burn="";}
    if(!$city || ($city==-1 && !$city_oth))
						{$error.="Вы не указали свой город<br>";$city="";}
    if($grow && !preg_match("#^([0-9]{2,3})$#",$grow))
						{$error.="Вы ввели некорректный рост<br>";$grow="";}
    $query_check2=mysql_num_rows(mysql_query("select mail from chat_users where mail='$mail' and user<>'$_SESSION[suser]'"));
    if($query_check2!="0") $error.=$lang['error']['mail3']."<br>";
    if(empty($error))
      {
      $month30=array("4","6","9","11");
      $month31=array("1","3","5","7","8","10","12");
      if(in_array($month_burn,$month30) && $day_burn>30) $day_burn=30;
      elseif(in_array($month_burn,$month31) && $day_burn>31) $day_burn=31;
      elseif(my_bcmod($year_burn,4)==0 && $month_burn==2 && $day_burn>29) $day_burn=29;
      elseif(my_bcmod($year_burn,4)>0 && $month_burn==2 && $day_burn>28) $day_burn=28;
      $query_edit="update chat_users set mail='$mail',about='$about',hidemail='$hidemail',name='$name',icq='$icq',date_burn='$year_burn-$month_burn-$day_burn',sex='$sex',city='$city',city_oth='$city_oth',region='$region',grow='$grow',sign='$sign' where user='$_SESSION[suser]'";
      if(mysql_query($query_edit))
        {
        mysql_query("update chat_onliners set sex='$sex',upd='$time' where user='$_SESSION[suser]'");
        if($new_pass)
          {
          $new_pass=md5($new_pass);
          mysql_query("update chat_users set pass='$new_pass' where user='$_SESSION[suser]'");
          }
        echo "<center>".$lang['complete']['edit']."</center><br>";
        if($_FILES['image']['name'])
          {
          $action=new uploadimg;
          $action->lang=array("complete"=>array("load"=>$lang['complete']['load']),"error"=>array("ext"=>$lang['error']['ext'],"width"=>$lang['error']['width'],"height"=>$lang['error']['height'],"size"=>$lang['error']['size'],"small"=>$lang['error']['small'],"copy"=>$lang['error']['copy']));
          $action->check_empty($_FILES['image']['name'],"Аватара");
          $action->check_empty($_FILES['image']['tmp_name'],"Аватара");
          $action->check_empty($_FILES['image']['size'],"Аватара");
          if(empty($action->errors))
            {
            $arr=array("avatars", "avatars/small", "0", $widthmaxav, $heightmaxav, $sizemaxav, "150", "113", $_FILES['image']['tmp_name'], $_FILES['image']['name'],
            $_SESSION['suser'], round($_FILES['image']['size']/1024));
            list($action->path1, $action->path2, $action->load_small, $action->width_max, $action->height_max,
            $action->size_max, $action->width_to, $action->height_to, $action->image, $action->image_name_or, $action->image_name, $action->size)=$arr;
            $action->setPars();
            $action->check_pars();
            }
          if(empty($action->errors))
            {
            $action->upload();
            echo "<center>$action->total</center>";
            }
          else echo "<center>$action->errors</center>";
          }
        }
      else
        echo "<center>".$lang['complete']['errinfo']."</center>";
      }
    else
      echo "<center>".$lang['complete']['error']."<br>$error</center>";
    }
  $query_user=mysql_query("select id,user,balls,mail,about,name,icq,date_burn,sex,city,city_oth,region,grow,sign,hidemail,mess,pmess,inchat from chat_users where user='$_SESSION[suser]'") or die(mysql_error());
  list($id_user,$user,$balls,$mail,$about,$name,$icq,$date_burn,$sex,$city,$city_oth,$region,$grow,$sign,$hidemail,$mess,$pmess,$inchat)=mysql_fetch_row($query_user);
  $opt_db=$opt_mb=$opt_yb=$opt_ct=$opt_rg="";
  list($year_burn,$month_burn,$day_burn)=explode("-",$date_burn);
  for($i=1;$i<=31;$i++) 
    {
    $opt_db.="<option value=$i";
    if($i==$day_burn) $opt_db.=" selected";
    $opt_db.=">$i</option>\r\n";
    }
  for($i=1;$i<=12;$i++)
    {
    $opt_mb.="<option value=$i";
    if($i==$month_burn) $opt_mb.=" selected";
    $opt_mb.=">".cmonth($i)."</option>\r\n";
    }
  for($i=1930;$i<=date("Y");$i++) 
    {
    $opt_yb.="<option value=$i";
    if($i==$year_burn) $opt_yb.=" selected";
    $opt_yb.=">$i</option>\r\n";
    }
  $query_ct=mysql_query("select id,title from chat_citys order by title") or die(mysql_error());
  while($arr_ct=mysql_fetch_array($query_ct)) 
    {
    $opt_ct.="<option value=$arr_ct[id]";
    if($city==$arr_ct['id']) $opt_ct.=" selected";
    $opt_ct.=">$arr_ct[title]</option>\r\n";
    }
  $opt_ct.="<option value=\"-1\"";
  if($city==-1) $opt_ct.=" selected";
  $opt_ct.=">Другой</option>\r\n";
  $query_rg=mysql_query("select id,title from chat_region order by title") or die(mysql_error());
  $opt_rg="<option value=0></option>\r\n";
  while($arr_rg=mysql_fetch_array($query_rg)) 
    {
    $opt_rg.="<option value=$arr_rg[id]";
    if($region==$arr_rg['id']) $opt_rg.=" selected";
    $opt_rg.=">$arr_rg[title]</option>\r\n";
    }?>
  <form action=userinfo.php?mode=edit method=post name="form" enctype="multipart/form-data">
  <input type=hidden name=act value=edit>
  <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
  <tr>
  <td colspan=3 class=tcat><h3><?echo$lang['edit'];?></h3></td>
  </tr>
  <tr>
  <td valign=top class=alt2><b>Текущая аватара:</b><br><?
  $user2=$_SESSION['suser'];
  if(file_exists("avatars/$user2.jpg")) echo "<img src=\"avatars/$user2.jpg\">";
  elseif(file_exists("avatars/$user2.gif")) echo "<img src=\"avatars/$user2.gif\">";
  elseif(file_exists("avatars/$user2.png")) echo "<img src=\"avatars/$user2.png\">";
  else echo $lang['notfound'];?></td>
  <td class=alt2 valign=top><table cellpadding=1 cellspacing=3 border=0 width=100%>  <tr>
  <td valign=top class=tcat height=1><?echo$lang['user'];?>:</td>
  <td valign=top class=alt2 height=1><?echo$user;?></td>
  </tr>
  <tr>
  <td valign=top class=tcat height=10><?echo$lang['oldpass'];?>:</td>
  <td valign=top class=alt2 height=10><input type=password name=old_pass></td>
  </tr>
  <tr>
  <td valign=top class=tcat><?echo$lang['newpass'];?>:</td>
  <td valign=top class=alt2><input type=password name=new_pass></td>
  </tr>
  <tr>
  <td valign=top class=tcat><?echo$lang['confpass'];?>:</td>
  <td valign=top class=alt2><input type=password name=confirm_pass></td>
  </tr>
  <tr>
  <td valign=top class=tcat>Новая аватара:<br></td>
  <td valign=top class=alt2><input type=file name=image></td>
  </tr></table></td>
  </tr>
  <tr>
  <td class=alt2 colspan=2>&nbsp;</td>
  </tr>
  <tr>
  <td valign=top class=tcat>Имя:</td>
  <td class=alt2><input type=text name=name value="<?echo$name;?>"></td>
  </tr>
  <tr>
  <td valign=top class=tcat>Дата рождения: </td>
  <td class=alt2>
  <select name="day_burn" class=smallfont style="width:70px">
  <?echo$opt_db;?>
  </select>
  <select name="month_burn" class=smallfont style="width:70px">
  <?echo$opt_mb;?>
  </select>
  <select name="year_burn" class=smallfont style="width:100px">
  <?echo$opt_yb;?>
  </select></td>
  </tr>
  <tr>
  <td valign=top class=tcat>Пол: </td>
  <td class=alt2><select name="sex"><option value="1"<?if($sex==1)echo" selected";?>>Муж
  <option value="2"<?if($sex==2)echo" selected";?>>Жен</select></td>
  </tr>
  <tr>
  <td valign=top class=tcat>Город: </td>
  <td class=alt2><select name="city" onChange="if(form.city.value=='-1'){form.city_oth.style.display='';}else{form.city_oth.style.display='none'}"><?echo$opt_ct;?></select>
  <input type="text" name="city_oth" size="25" value="<?echo$city_oth;?>" style="display:none"></td>
  </tr>
  <tr>
  <td valign=top class=tcat>Район: </td>
  <td class=alt2><select name="region"><?echo$opt_rg;?></select></td>
  </tr>
  <tr>
  <td valign=top class=tcat>Рост: </td>
  <td class=alt2><input type="text" name="grow" size="25" value="<?echo$grow;?>"></td>
  </tr>
  <tr>
  <td valign=top class=tcat>E-mail: </td>
  <td class=alt2><input type="text" name="mail" size="25" value="<?echo$mail;?>"></td>
  </tr>
  <tr>
  <td valign=top class=tcat>ICQ: </td>
  <td class=alt2><input type="text" name="icq" size="25" value="<?echo$icq;?>"></td>
  </tr>
  <tr>
  <td valign=top class=tcat>Скрыть e-mail: </td>
  <td class=alt2><input type="radio" name="hidemail" value="1"<?if($hidemail==1)echo" checked";?>>Да<br>
  <input type="radio" name="hidemail" value="0"<?if($hidemail==0)echo" checked";?>>Нет</td>
  </tr>
  <tr>
  <td valign=top class=tcat>О себе: </td>
  <td class=alt2><textarea name=about cols=40 rows=5><?echo$about;?></textarea></td>
  </tr>
  <tr>
  <td valign=top class=tcat>Подпись: </td>
  <td class=alt2><textarea name=sign cols=40 rows=5><?echo$sign;?></textarea></td>
  </tr>
  <tr>
  <td colspan=2 class=tcat align=center><input type=submit value="<?echo$lang['edit2'];?>"></td>
  </tr>
  </table>
  <script language=JavaScript>if (document.form.city.value == '-1') document.form.city_oth.style.display='';</script></form>
  </body></html>
  <table border=0 width=100% class=tb><?
  $query_photos=mysql_query("select * from chat_photos where user='$_SESSION[suser]' order by filename desc");
  if(mysql_num_rows($query_photos))
    {?>
    <tr>
    <td valign=top width=100% align=center>
    <form action=userinfo.php?mode=edit method=post>
    <input type=hidden name=act value=del_ph>
    <table cellpadding=1 cellspacing=3 border=0 width=100% align=center>
    <tr>
    <td valign=top colspan=3 class=tcat><h3>Мой фотоальбом</h3></td>
    </tr><?
    $p=$d=1;
    while($array_photos=mysql_fetch_array($query_photos))
      {
      if($d==1) echo "<tr>";
      preg_match("#^([0-9]{4})([0-9]{2})([0-9]{2})#",$array_photos['filename'],$dload);
      $date_load="$dload[3].$dload[2].$dload[1]";?>
      <td valign=top align=center class=alt2>      
      <img src="photos/small/<?echo$array_photos['filename'];?>" border=0 class=imgborder vspace=5><br>
      <?echo$array_photos['title'];?> (<?echo$date_load;?>)<br>
      <?echo$array_photos['permit'];?> <b>|</b> <?echo$array_photos['size'];?> Kb<br>
      <input type=checkbox name=photos[<?echo$array_photos['id'];?>] value=1></td><?
      if($d==3){echo "</tr>";$d=1;}
      else $d++;
      $p++;
      }?>
    <tr><td colspan=3 align=center class=tcat><input type=submit value="Удалить!"></td></tr></form>
    </table>
    </td>
    </tr><?
    }?>
    </table>
    <table cellpadding=1 cellspacing=3 border=0 width=100% align=center>
    <tr>
    <td valign=top colspan=3 class=tcat><h3>Загрузить новую фотографию</h3></td>
    </tr>
    <form action=userinfo.php?mode=edit method=post enctype="multipart/form-data">
    <input type=hidden name=act value=up_photo>
    <tr>
    <td valign=top width=20% class=tcat>Название</td>
    <td valign=top width=80% class=alt2><input type=text name="title" size=25></td>
    </tr>
    <tr>
    <td valign=top width=20% class=tcat>Файл</td>
    <td valign=top width=80% class=alt2><input type=file name="newphoto" size=25></td>
    </tr>
    <tr>
    <td colspan=2 class=tcat align=center><input type=submit value="Загрузить"></td>
    </tr>
    </form>
    <tr><td>&nbsp;</td></tr>
    </table><?
  }
else
  {
  $query_user=mysql_query("select id,user,balls,mail,about,name,icq,date_burn,sex,city,city_oth,region,grow,sign,hidemail,mess,pmess,inchat,date_register,date_last,clicks from chat_users where user='$user'");
  if(mysql_num_rows($query_user)>0)
    {
    if(isset($_SESSION['suser'])&&strtolower($_SESSION['suser']!=$user))
      mysql_query("update chat_users set clicks=clicks+1 where user='$user'");
    list($id_user,$user,$balls,$mail,$about,$name,$icq,$date_burn,$sex,$city,$city_oth,$region,$grow,$sign,$hidemail,$mess,$pmess,$inchat,$date_register,$date_last,$clicks)=mysql_fetch_row($query_user);
    if($city==-1) $citystr=$city_oth;
    else $citystr=mysql_result(mysql_query("select title from chat_citys where id='$city'"),0,'title');
    if($region==0 || empty($region)) $regionstr="отсутствует";
    else $regionstr=mysql_result(mysql_query("select title from chat_region where id='$region'"),0,'title');
    $bans=mysql_result(mysql_query("select `count` from chat_banstat where type='ban' and id_user='$id_user'"),0,'count');
    $warns=mysql_result(mysql_query("select count from chat_banstat where type='warn' and id_user='$id_user'"),0,'count');
    list($year_burn,$month_burn,$day_burn)=explode("-",$date_burn);
    if(($month_burn==1 && $day_burn>=20) || ($month_burn==2 && $day_burn<=18)) $zod=1;
    if(($month_burn==2 && $day_burn>=19) || ($month_burn==3 && $day_burn<=20)) $zod=2;
    if(($month_burn==3 && $day_burn>=21) || ($month_burn==4 && $day_burn<=19)) $zod=3;
    if(($month_burn==4 && $day_burn>=20) || ($month_burn==5 && $day_burn<=20)) $zod=4;
    if(($month_burn==5 && $day_burn>=21) || ($month_burn==6 && $day_burn<=21)) $zod=5;
    if(($month_burn==6 && $day_burn>=22) || ($month_burn==7 && $day_burn<=22)) $zod=6;
    if(($month_burn==7 && $day_burn>=23) || ($month_burn==8 && $day_burn<=22)) $zod=7;
    if(($month_burn==8 && $day_burn>=23) || ($month_burn==9 && $day_burn<=22)) $zod=8;
    if(($month_burn==9 && $day_burn>=23) || ($month_burn==10 && $day_burn<=22)) $zod=9;
    if(($month_burn==10 && $day_burn>=23) || ($month_burn==11 && $day_burn<=22)) $zod=10;
    if(($month_burn==11 && $day_burn>=23) || ($month_burn==12 && $day_burn<=21)) $zod=11;
    if(($month_burn==12 && $day_burn>=22) || ($month_burn==1 && $day_burn<=19)) $zod=12;
    if($sex==1) $sexstr="мужской";
    else $sexstr="женский";
    $dateburn=dbdate($date_burn);
    $datereg=dbdate($date_register);
    if($date_last!='0000-00-00 00:00:00') $datelast=dbdate($date_last);
    else $datelast="";
    if($balls>=100&&$balls<500)
      $status="<img src=\"theme/$skin/icons/forum.gif\" width=16 height=16>Форумчанин";
    elseif($balls>=500&&$balls<700)
      $status="<img src=\"theme/$skin/icons/moder.gif\" width=16 height=16>Младший модератор";
    elseif($balls>=700&&$balls<999)
      $status="<img src=\"theme/$skin/icons/moder2.gif\" width=16 height=16>Старший модератор";
    elseif($balls==999)
      $status="<img src=\"theme/$skin/icons/admin.gif\" width=16 height=16>Администратор";
    else
      {
      $qlevel=mysql_query("select title,image from chat_level where mess<='$mess' order by mess desc limit 1");
      list($level_title,$level_image)=mysql_fetch_row($qlevel);
      $status="<img src=\"theme/$skin/icons/$level_image\" width=16 height=16>$level_title";
      }?>
    <html>
    <head>
    <title><?echo$lang['aboutuser'];?> <?echo$user;?></title>
    <meta http-equiv="content-type" Content="text/html;charset=windows-1251">
    <link href="design/main.css" type=text/css rel=stylesheet>
    </head>
    <body>
    <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
    <tr>
    <td colspan=3 class=tcat><h3><?echo$lang['info'];?></h3></td>
    </tr>
    <tr>
    <td valign=top class=alt2><b>Аватара:</b><br><?
    $user2=$user;
    if(file_exists("avatars/$user2.jpg")) echo "<img src=\"avatars/$user2.jpg\">";
    elseif(file_exists("avatars/$user2.gif")) echo "<img src=\"avatars/$user2.gif\">";
    elseif(file_exists("avatars/$user2.png")) echo "<img src=\"avatars/$user2.png\">";
    else echo $lang['notfound'];
    ?></td>
    <td valign=top class=alt2><table cellpadding=1 cellspacing=3 width=100% border=0>
    <tr>
    <td valign=top class=tcat><?echo$lang['user'];?>:</td>
    <td class=alt2><?echo$user;?></td>
    </tr>
    <tr>
    <td valign=top class=tcat>Уровень:</td>
    <td class=alt2><?echo$status;?></td>
    </tr>
    <tr>
    <td valign=top class=tcat>Дата регистрации:</td>
    <td class=alt2><?echo$datereg;?></td>
    </tr>
    <tr>
    <td valign=top class=tcat>Последний вход:</td>
    <td class=alt2><?echo$datelast;?></td>
    </tr>
    <tr>
    <td valign=top class=tcat>Кол-во банов:</td>
    <td class=alt2><?echo$bans;?></td>
    </tr>
    <tr>
    <td valign=top class=tcat>Кол-во предупреждений:</td>
    <td class=alt2><?echo$warns;?></td>
    </tr>
    </table></td>
    </tr>
    <tr>
    <td valign=top class=alt2 colspan=2>&nbsp;</td>
    </tr>
    <tr>
    <td valign=top class=tcat>Имя:</td>
    <td class=alt2><?echo$name;?></td>
    </tr>
    <tr>
    <td valign=top class=tcat>Дата рождения:</td>
    <td class=alt2><?echo$dateburn;?><br>
    <img src="design/zodiac/<?echo$zod;?>.gif"></td>
    </tr>
    <tr>
    <td valign=top class=tcat>Пол:</td>
    <td class=alt2><?echo$sexstr;?></td>
    </tr>
    <tr>
    <td valign=top class=tcat>Город/район:</td>
    <td class=alt2><?echo$citystr;?>/<?echo$regionstr;?></td>
    </tr>
    <tr>
    <td valign=top class=tcat>Рост:</td>
    <td class=alt2><?echo$grow;?></td>
    </tr>
    <tr>
    <td valign=top class=tcat><?echo$lang['email'];?>:</td>
    <td class=alt2><?if($hidemail==1) echo"<скрыто>";else{?><a href="mailto:<?echo$mail;?>"><?echo$mail;?></a><?}?></td>
    </tr>
    <tr>
    <td valign=top class=tcat><?echo$lang['about'];?>:</td>
    <td class=alt2><?echo$about;?></td>
    </tr>
    <tr>
    <td valign=top class=tcat>Подпись:</td>
    <td class=alt2><?echo$sign;?></td>
    </tr>
    <tr>
    <td valign=top class=tcat>Всего насижено:</td>
    <td class=alt2><?echo h_m($inchat);?></td>
    </tr>
    <tr>
    <td valign=top class=tcat>Неприват.сообщений:</td>
    <td class=alt2><?echo$mess;?></td>
    </tr>
    <tr>
    <td valign=top class=tcat>Приват.сообщений:</td>
    <td class=alt2><?echo$pmess;?></td>
    </tr>
    <tr>
    <td colspan=2 class=tcat>Посмотрели анкету: <?echo$clicks;?></td>
    </tr>
    </table>
    <table width=100% border=0>
    <tr>
    <td valign=top colspan=3 class=tcat><h3>Фотоальбом</h3></td>
    </tr><?
    $query_photos=mysql_query("select * from chat_photos where user='$user' order by filename desc");
    $d=1;
    while($array_photos=mysql_fetch_array($query_photos))
      {
      if($d==1) echo "<tr>";
      preg_match("#^([0-9]{4})([0-9]{2})([0-9]{2})#",$array_photos['filename'],$dload);
      $date_load="$dload[3].$dload[2].$dload[1]";?>
      <td valign=top align=center class=alt2>
      <a href="photo.php?t=b&id=<?echo$array_photos['id'];?>" target="_blank" title="Кликните, чтобы увидеть всю фотографию">
      <img src="photos/small/<?echo$array_photos['filename'];?>" border=0 class=imgborder></a><br>
      <b><?echo$array_photos['title'];?> (<?echo$date_load;?>)<br><?echo$array_photos['permit'];?>px | <?echo$array_photos['size'];?> Kb</b> <br> Просмотров: <?echo$array_photos['views'];?></td><?
      if($d==3){echo "</tr>";$d=1;}
      else $d++;
      }?>
    </table></body></html><?
    }
  else
    {
    echo "<html><head></head><body><script language=javascript>window.close();</script></body></html>";
    }
  }
?>