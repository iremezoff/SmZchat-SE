<?
if(!defined("A_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

  if(isset($up_photo))
    {
    $count_photos=mysql_result(mysql_query("select count(id) from chat_photos where id='$id_user'"),0,'count(id)');
    if(($count_photos+1)>$max_photos) echo "Вы исчерпали лимит максимального числа загружаемых фотографий!";
    elseif(!$title) echo "Вы не указали название фотографии.";
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
          mysql_query("insert into chat_photos values ('','$id_user','$title','$action->image_name','".$action->width."x".$action->height."','$action->size','0')");
          echo "$action->total";
          }
        else
          {
          echo "$action->errors";
          }
        }
      else
        {
        echo "Произошла ошибка! Невозможно загрузить фотографию на сервер.";
        }
      }
    }
  elseif(isset($del_ph))
    {
    if(count($photos)>0)
      {
      foreach($photos as $key=>$val)
        {
        if($val==1)
          {
          $path_img=mysql_result(mysql_query("select filename from chat_photos where id='$key' and id='$id_user'"),0,'filename');
          mysql_query("delete from chat_photos where id='$key' and id='$id_user'");
          @unlink("./photos/$path_img");
          @unlink("./photos/small/$path_img");
          }
        }
      echo "Выбранные фотографии успешно удалены!";
      }
    else
      {
      echo "Не удалено ни одной фотографии!";
      }
    }
  elseif(isset($edituser))
    {
    $error="";
    if(!empty($new_pass))
      {
      if($new_pass!=$confirm_pass) $error.=$lang['error']['passes']."<br>";
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
    $query_check2=mysql_num_rows(mysql_query("select mail from chat_users where mail='$mail' and id<>'$id_user'"));
    if($query_check2!="0") $error.=$lang['error']['mail3']."<br>";
    if(empty($error))
      {
      $month30=array("4","6","9","11");
      $month31=array("1","3","5","7","8","10","12");
      if(in_array($month_burn,$month30) && $day_burn>30) $day_burn=30;
      elseif(in_array($month_burn,$month31) && $day_burn>31) $day_burn=31;
      elseif(my_bcmod($year_burn,4)==0 && $month_burn==2 && $day_burn>29) $day_burn=29;
      elseif(my_bcmod($year_burn,4)>0 && $month_burn==2 && $day_burn>28) $day_burn=28;
      $query_edit="update chat_users set mail='$mail',about='$about',hidemail='$hidemail',name='$name',icq='$icq',date_burn='$year_burn-$month_burn-$day_burn',sex='$sex',city='$city',city_oth='$city_oth',region='$region',grow='$grow',sign='$sign',inchat='$inchat',mess='$mess',pmess='$pmess' where id='$id_user'";
      if(mysql_query($query_edit))
        {
        mysql_query("update chat_onliners set sex='$sex',upd='$time' where id='$id_user'");
        if($new_pass)
          {
          $new_pass=md5($new_pass);
          mysql_query("update chat_users set pass='$new_pass' where id='$id_user'");
          }
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
            $id_user, round($_FILES['image']['size']/1024));
            list($action->path1, $action->path2, $action->load_small, $action->width_max, $action->height_max,
            $action->size_max, $action->width_to, $action->height_to, $action->image, $action->image_name_or, $action->image_name, $action->size)=$arr;
            $action->setPars();
            $action->check_pars();
            }
          if(empty($action->errors))
            {
            $action->upload();
            echo "$action->total";
            }
          else echo "$action->errors";
          }
        echo $lang['complete']['edit']."<br>";
        }
      else
        echo $lang['complete']['errinfo'];
      }
    else
      echo $lang['complete']['error']."<br>$error";
    }
  elseif($operat=="del"&&$id)
    {
    $query_deluser="delete from chat_users where id='$id'";
    @$nick=mysql_result(mysql_query("select user from chat_users where id='$id'"),'0','user');
    if(mysql_query($query_deluser) && !empty($nick))
      {
      $query_photo=mysql_query("select filename from chat_photos where user='$nick'");
      while($arr_photo=mysql_fetch_array($query_photo))
        {
        @unlink("./photos/$filename");
        @unlink("./photos/small/$filename");
        }
      echo "Пользователь успешно удалён.";
      }  
    else 
      {
      echo "Произошла ошибка при удалении пользователя из базы.<br>";
      }
    }
  else
    {
    if($act!="edit")
      {?>
      <table cellpadding=1 cellspacing=3 width=100% border=0>
      <tr>
      <td valign=top class=tcat colspan=2 align=center>Поиск пользователя</td>
      </tr>
      <form action=adm.php method=get>
      <input type=hidden name=mode value=users>
      <input type=hidden name=act value=search>
      <tr> 
      <td valign=top class=alt2 width=20%><b>Логин</b></td>
      <td valign=top class=alt2 width=80%><input type=text name=login size=25> <input type=button onclick="submit();" value="Искать!"></td>
      </tr>
      </form>
      <form action=adm.php method=get>
      <input type=hidden name=mode value=users>
      <input type=hidden name=act value=search>
      <tr> 
      <td valign=top class=alt2 width=20%><b>Отсутствующие за последние месяцы:</b></td>
      <td valign=top class=alt2 width=80%><input type=text name=month size=25> <input type=button onclick="submit();" value="Искать!"></td>
      </tr>
      </form>
      <tr>
      <td valign=top class=tcat colspan=2 align=center>&nbsp;</td>
      </tr>
      </table><?
      }
    if($act=="edit")
      {
      $query_user=mysql_query("select id,user,balls,mail,about,name,icq,date_burn,sex,city,city_oth,region,grow,sign,hidemail,mess,pmess,inchat from chat_users where id='$id'") or die(mysql_error());
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
      <form action=adm.php?mode=users method=post name="form" enctype="multipart/form-data">
      <input type=hidden name=id_user value=<?echo$id_user;?>>
      <table cellpadding=1 cellspacing=3 width=100% border=0 align=center>
      <tr>
      <td colspan=3 class=tcat><h3><?echo$lang['edit'];?></h3></td>
      </tr>
      <tr>
      <td valign=top class=alt2><b>Текущая аватара:</b><br><?
      $user2=strtolower($id_user);
      if(file_exists("avatars/$user2.jpg")) echo "<img src=\"avatars/$user2.jpg\">";
      elseif(file_exists("avatars/$user2.gif")) echo "<img src=\"avatars/$user2.gif\">";
      elseif(file_exists("avatars/$user2.png")) echo "<img src=\"avatars/$user2.png\">";
      else echo $lang['notfound'];?></td>
      <td class=alt2 valign=top><table cellpadding=1 cellspacing=3 border=0 width=100%>  <tr>
      <td valign=top class=tcat height=1><?echo$lang['user'];?>:</td>
      <td valign=top class=alt2 height=1><?echo$user;?></td>
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
      <td valign=top class=tcat>Всего насижено: </td>
      <td class=alt2><input type="text" name="inchat" size="25" value="<?echo$inchat;?>"> <?echo h_m($inchat);?></td>
      </tr>
      <tr>
      <td valign=top class=tcat>Неприват.сообщений: </td>
      <td class=alt2><input type="text" name="mess" size="25" value="<?echo$mess;?>"></td>
      </tr>
      <tr>
      <td valign=top class=tcat>Приват.сообщений: </td>
      <td class=alt2><input type="text" name="pmess" size="25" value="<?echo$pmess;?>"></td>
      </tr>
      <tr>
      <td colspan=2 class=tcat align=center><input type=submit name=edituser value="Редактировать"></td>
      </tr>
      </table>
      <script language=JavaScript>if (document.form.city.value == '-1') document.form.city_oth.style.display='';</script></form>
      </body></html>
      <table border=0 width=100% class=tb><?
      $query_photos=mysql_query("select * from chat_photos where user='$id_user' order by filename desc");
      if(mysql_num_rows($query_photos))
        {?>
        <tr>
        <td valign=top width=100% align=center>
        <form action=adm.php?mode=users method=post>
        <input type=hidden name=id_user value=<?echo$id_user;?>>
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
        <tr><td colspan=3 align=center class=tcat><input type=submit name=del_ph value="Удалить!"></td></tr></form>
        </table>
        </td>
        </tr><?
        }?>
      </table>
      <table cellpadding=1 cellspacing=3 border=0 width=100% align=center>
      <tr>
      <td valign=top colspan=3 class=tcat><h3>Загрузить новую фотографию</h3></td>
      </tr>
      <form action=adm.php?mode=users method=post enctype="multipart/form-data">
      <input type=hidden name=id_user value=<?echo$id_user;?>>
      <tr>
      <td valign=top width=20% class=tcat>Название</td>
      <td valign=top width=80% class=alt2><input type=text name="title" size=25></td>
      </tr>
      <tr>
      <td valign=top width=20% class=tcat>Файл</td>
      <td valign=top width=80% class=alt2><input type=file name="newphoto" size=25></td>
      </tr>
      <tr>
      <td colspan=2 class=tcat align=center><input type=submit name=up_photo value="Загрузить"></td>
      </tr>
      </form>
      <tr><td>&nbsp;</td></tr>
      </table><?
      }
    elseif($act=="search")
      {
      if(isset($login))
        {
        $query_users=mysql_query("select * from chat_users where id>'0' and user LIKE '%$login%' order by date_register");
        $count=mysql_num_rows($query_users);
        if($count>0)
          {?>
          <table cellpadding=1 cellspacing=3 width=100% border=0>
          <tr>
          <td valign=top class=tcat colspan=7 align=center>Пользователи</td>
          </tr>
          <tr>
          <td valign=top class=tcat align=center width=5%>#</td>
          <td valign=top class=tcat align=center>Логин</td>
          <td valign=top class=tcat align=center>Имя</td>
          <td valign=top class=tcat align=center>E-mail</td>
          <td valign=top class=tcat align=center>Дата регистрации</td>
          <td valign=top class=tcat align=center>Последний вход</td>
          <td valign=top class=tcat align=center>Удаление</td>
          </tr>
          <tr>
          <td valign=top class=alt2 colspan=7><span class=smallfont>Всего: <?echo$count;?></span></td>
          <?
          while($array_users=mysql_fetch_array($query_users))
            {?>
            <tr>
            <td valign=top class=alt2 align=center><?echo$array_users['id'];?></td>
            <td valign=top class=alt2 align=center><a href="adm.php?mode=users&act=edit&id=<?echo$array_users['id'];?>"><b><?echo$array_users['user'];?></b></a></td>
            <td valign=top class=alt2 align=center><?echo$array_users['name'];?></td>
            <td valign=top class=alt2 align=center><?echo$array_users['mail'];?></td>
            <td valign=top class=alt2 align=center><?echo dbdate($array_users['date_register']);?></td>
            <td valign=top class=alt2 align=center><?echo ($array_users['date_last']=='0000-00-00 00:00:00')?"":dbdate($array_users['date_last']);?></td>
            <td valign=top class=alt2 align=center><a href="adm.php?mode=users&operat=del&id=<?echo$array_users['id'];?>" onClick="return confirm('Вы действительно хотите удалить этого пользователя?');">удалить</a></td>
            </tr><?
            }?>
          </table><?
          }
        else
          {
          echo "Пользователей с похожим логином в базе не найдено.<br>";
          }
        }
      elseif(isset($month))
        {
        $query_users=mysql_query("select * from chat_users where id>'0' and date_last<date_sub(now(),interval '$month' month) order by date_last");
        $count=mysql_num_rows($query_users);
        if($count>0)
          {?>
          <table cellpadding=1 cellspacing=3 width=100% border=0>
          <tr>
          <td valign=top class=tcat colspan=7 align=center>Пользователи</td>
          </tr>
          <tr>
          <td valign=top class=tcat align=center width=5%>#</td>
          <td valign=top class=tcat align=center>Логин</td>
          <td valign=top class=tcat align=center>Имя</td>
          <td valign=top class=tcat align=center>E-mail</td>
          <td valign=top class=tcat align=center>Дата регистрации</td>
          <td valign=top class=tcat align=center>Последний вход</td>
          <td valign=top class=tcat align=center>Удаление</td>
          </tr>
          <tr>
          <td valign=top class=alt2 colspan=7><span class=smallfont>Всего: <?echo$count;?></span></td>
          <?
          while($array_users=mysql_fetch_array($query_users))
            {?>
            <tr>
            <td valign=top class=alt2 align=center><?echo$array_users['id'];?></td>
            <td valign=top class=alt2 align=center><a href="adm.php?mode=users&act=edit&id=<?echo$array_users['id'];?>"><b><?echo$array_users['user'];?></b></a></td>
            <td valign=top class=alt2 align=center><?echo$array_users['name'];?></td>
            <td valign=top class=alt2 align=center><?echo$array_users['mail'];?></td>
            <td valign=top class=alt2 align=center><?echo dbdate($array_users['date_register']);?></td>
            <td valign=top class=alt2 align=center><?echo ($array_users['date_last']=='0000-00-00 00:00:00')?"":dbdate($array_users['date_last']);?></td>
            <td valign=top class=alt2 align=center><a href="adm.php?mode=users&operat=del&id=<?echo$array_users['id'];?>" onClick="return confirm('Вы действительно хотите удалить этого пользователя?');">удалить</a></td>
            </tr><?
            }?>
          </table><?
          }
        else
          {
          echo "Пользователей, не посещающих чат за указанное время, в базе не найдено.<br>";
          }
        }
      }
    else
      {
      $count=mysql_result(mysql_query("select count(id) from chat_users where id>'0'"),0,'count(id)');
      $pages=ceil($count/$maxinp);
      if($page=="" || $page=="0") $lim="0";
      else $lim=($page-1)*$maxinp;
      $query_users=mysql_query("select * from chat_users where id>'0' order by date_last, id limit $lim, $maxinp");?>
      <table cellpadding=1 cellspacing=3 width=100% border=0>
      <tr>
      <td valign=top class=tcat colspan=7 align=center>Пользователи</td>
      </tr>
      <tr>
      <td valign=top class=tcat align=center width=5%>#</td>
      <td valign=top class=tcat align=center>Логин</td>
      <td valign=top class=tcat align=center>Имя</td>
      <td valign=top class=tcat align=center>E-mail</td>
      <td valign=top class=tcat align=center>Дата регистрации</td>
      <td valign=top class=tcat align=center>Последний вход</td>
      <td valign=top class=tcat align=center>Удаление</td>
      </tr>
      <tr>
      <td valign=top class=alt2 colspan=7><span class=smallfont>Всего: <?echo$count;?>. Страницы: <?pages($count);?></span></td>
      <?
      while($array_users=mysql_fetch_array($query_users))
        {?>
        <tr>
        <td valign=top class=alt2 align=center><?echo$array_users['id'];?></td>
        <td valign=top class=alt2 align=center><a href="adm.php?mode=users&act=edit&id=<?echo$array_users['id'];?>"><b><?echo$array_users['user'];?></b></a></td>
        <td valign=top class=alt2 align=center><?echo$array_users['name'];?></td>
        <td valign=top class=alt2 align=center><?echo$array_users['mail'];?></td>
        <td valign=top class=alt2 align=center><?echo dbdate($array_users['date_register']);?></td>
        <td valign=top class=alt2 align=center><?echo ($array_users['date_last']=='0000-00-00 00:00:00')?"":dbdate($array_users['date_last']);?></td>
        <td valign=top class=alt2 align=center><a href="adm.php?mode=users&operat=del&id=<?echo$array_users['id'];?>" onClick="return confirm('Вы действительно хотите удалить этого пользователя?');">удалить</a></td>
        </tr><?
        }?>
      </table><?
      }
    }
?>