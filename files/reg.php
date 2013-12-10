<?
if(!defined("F_MOD"))
  {
  echo "<meta http-equiv='refresh' content='0; url=/index.php'>";
  exit;
  }

session_register("registr_key");

  function form_new_user($login="",$name="",$day_burn="",$month_burn="",$year_burn="",$sex=1,$city="",$city_oth="",$region="",$grow="",$mail="",$icq="",$hidemail=1,$about="",$sign="")
    {
    global $confirm_pass,$type_str;

    if(!$city) $city=1;
    elseif($city_oth) $city=-1;

    $opt_db=$opt_mb=$opt_yb=$opt_ct=$opt_rg="";
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
    $query_ct=mysql_query("select id,title from chat_citys order by title");
    while($arr_ct=mysql_fetch_array($query_ct)) 
      {
      $opt_ct.="<option value=$arr_ct[id]";
      if($city==$arr_ct['id']) $opt_ct.=" selected";
      $opt_ct.=">$arr_ct[title]</option>\r\n";
      }
    $opt_ct.="<option value=-1";
    if($city==-1) $opt_ct.=" selected";
    $opt_ct.=">Другой</option>\r\n";
    $query_rg=mysql_query("select id,title from chat_region order by title");
    $opt_rg.="<option value=0></option>\r\n";
    while($arr_rg=mysql_fetch_array($query_rg)) 
      {
      $opt_rg.="<option value=$arr_rg[id]";
      if($region==$arr_rg['id']) $opt_rg.=" selected";
      $opt_rg.=">$arr_rg[title]</option>\r\n";
      }?>
   <script language=JavaScript type=text/javascript src="design/js.php"></script><br>
   <form action="index.php?reg" method="post" name="form">
   <input type="hidden" name="action" value="reg"> 
   <table table cellpadding=3 cellspacing=0 width=100% border=0 class=tb>
   <tr>
   <td width=100% colspan=2><b class=b>Регистрация</b><br>
   Поля, отмеченные * обязательны для заполнения</td>
   </tr>
   <tr>
   <td width=40%>* Логин:<br> </td>
   <td width=60%><input type="text" name="login" size="25" value="<?echo$login;?>" onchange="checks('clogin',document.form.login.value);"> <span id="clogin" style="color:#ff0000;font-weight:bold;font-size:11px"></span></td>
   </tr>
   <tr>
   <td></td>
   <td class=smallfont>Должен состоять только из букв латинского алфавита и знака _</td>
   </tr>
   <tr>
   <td>* Пароль:<br></td>
   <td><input type="password" name="pass" size="25" maxlength="100" onchange="checks('cpass',document.form.pass.value);"> <span id="cpass" style="color:#ff0000;font-weight:bold;font-size:11px"></span></td>
   </tr>
   <tr>
   <td></td>
   <td class=smallfont>Пароль должен быть длинной не менее 4 и не более 10 символов</td>
   </tr>
   <tr>
   <td>* Повторите пароль: </td>
   <td><input type="password" name="confirm_pass" size="25" maxlength="100" onchange="checks('cpassc',document.form.pass.value,document.form.confirm_pass.value);"> <span id="cpassc" style="color:#ff0000;font-weight:bold;font-size:11px"></span></td>
   </tr>
   <tr>
   <td>* Имя: </td>
   <td><input type="text" name="name" size="25" value="<?echo$name;?>" onchange="checks('cname',document.form.name.value);"> <span id="cname" style="color:#ff0000;font-weight:bold;font-size:11px"></span></td>
   </tr>
   <tr>
   <td>* E-mail: </td>
   <td><input type="text" name="mail" size="25" value="<?echo$mail;?>" onchange="checks('cmail',document.form.mail.value);"> <span id="cmail" style="color:#ff0000;font-weight:bold;font-size:11px"></span></td>
   </tr>
   <tr>
   <td>* Пол: </td>
   <td><select name="sex" class=smallfont><option value="1"<?if($sex==1)echo" selected";?>>Муж
   <option value="2"<?if($sex==2)echo" selected";?>>Жен</select></td>
   </tr>
   <tr>
   <td>* Город: </td>
   <td><select name="city" class="smallfont" onchange="if(form.city.value=='-1'){form.city_oth.style.display='';}else{form.city_oth.style.display='none';}"><?echo$opt_ct;?></select><br>
   <input type="text" name="city_oth" size="25" value="<?echo$city_oth;?>" style="display:none"></td>
   </tr>
   <tr>
   <td>&nbsp;&nbsp;Район: </td>
   <td><select name="region" class="smallfont">
   <?echo$opt_rg;?></select></td>
   </tr>
   <tr>
   <td>* Дата рождения: </td>
   <td>
   <select name="day_burn" class=smallfont style="width:70px" onchange="checks('cburn',form.day_burn.value,form.month_burn.value,form.year_burn.value);">
   <option value="">  </option>
   <?echo$opt_db;?>
   </select>
   <select name="month_burn" class=smallfont style="width:70px" onchange="checks('cburn',form.day_burn.value,form.month_burn.value,form.year_burn.value);">
   <option value="">  </option>
   <?echo$opt_mb;?>
   </select>
   <select name="year_burn" class=smallfont style="width:100px" onchange="checks('cburn',form.day_burn.value,form.month_burn.value,form.year_burn.value);">
   <option value="">  </option>
   <?echo$opt_yb;?>
   </select>  <span id="cburn" style="color:#ff0000;font-weight:bold;font-size:11px"></span></td>
   </tr>
   <tr>
   <td>&nbsp;&nbsp;Рост (см): </td>
   <td><input type="text" name="grow" size="25" value="<?echo$grow;?>" onchange="checks('cgrow',document.form.grow.value);"> <span id="cgrow" style="color:#ff0000;font-weight:bold;font-size:11px"></span></td>
   </tr>
   <tr>
   <td>&nbsp;&nbsp;ICQ: </td>
   <td><input type="text" name="icq" size="25" value="<?echo$icq;?>" onchange="checks('cicq',document.form.icq.value);"> <span id="cicq" style="color:#ff0000;font-weight:bold;font-size:11px"></span></td>
   </tr>
   <tr>
   <td>&nbsp;&nbsp;Скрыть e-mail: </td>
   <td><input type="radio" name="hidemail" value="1"<?if($hidemail==1)echo" checked";?>>Да<br>
   <input type="radio" name="hidemail" value="0"<?if($hidemail==0)echo" checked";?>>Нет</td>
   </tr>
   <tr>
   <td>&nbsp;&nbsp;О себе: </td>
   <td><textarea name=about cols=50 rows=5><?echo$about;?></textarea></td>
   </tr>
   <tr>
   <td>&nbsp;&nbsp;Подпись: </td>
   <td><textarea name=sign cols=50 rows=5><?echo$sign;?></textarea></td>
   </tr>
   <?
   if($type_str!=1){?>
   <tr>
   <td valign=top width=30%>* Введите указанную на картинке строку:</td>
   <td class=smallfont><img src="img.php"><br><input type="text" name="user_key" size="6" maxlength="6"><br>
   Если строка читается неразборчиво, обновите страницу
   </td>
   </tr>
   <?}?>
   <tr>
   <td></td>
   <td><input type="button" value="Регистрация" onClick="if (check()) submit();"></td>
   </tr>
   </table></form><?
   if(isset($_POST['action'])&&$_POST['action']=="reg")
     echo "<script language=JavaScript>if (document.form.city.value == '-1') document.form.city_oth.style.display='';</script>";
   }


function reg_new_user($chat_title)
  {
  global $login,$pass,$confirm_pass,$mail,$icq,$name,$sex,$day_burn,$month_burn,$year_burn,$city,$city_oth,$region,$grow,$sign,$hidemail,$about,$user_key,$type_str;

  $error="";

  if($type_str!=1 && md5($user_key)!=$_SESSION['registr_key'])      
						$error.="Вы ввели неверное 6-ти значное число<br>";
  if($pass=="")					$error.="Вы не ввели пароль<br>";
  elseif(strlen($pass)<4 || strlen($pass)>10)	$error.="Пароль должен быть от 4 до 10 символов<br>";
  elseif($pass!=$confirm_pass)			$error.="Пароль и повторный пароль не совпадают<br>";
  if(!$login)					{$error.="Вы не ввели логин<br>";$login="";}
  elseif(!preg_match("#^[a-zA-Z0-9_]+$#",$login))
						{$error.="Логин может состоять только из латинских букв, цифр и знака _<br>";$login="";}
  if(!$mail)					{$error.="Вы не ввели e-mail<br>";$mail="";}
  elseif(!preg_match("#^[a-z0-9_.-]+@[a-z0-9_.-]+\.+[a-z]{2,4}$#is",$mail))
						{$error.="Вы ввели некорректный e-mail<br>";$mail="";}
  if($icq && !preg_match("#^([0-9]{4,9})$#",$icq))
						{$error.="Вы ввели некорреткный ICQ<br>";$icq="";}
  if(!$name)					{$error.="Вы не ввели имя<br>";$name="";}
  if($sex!=1 && $sex!=2)			$error.="Вы не указали пол<br>";
  if(!preg_match("#^[0-9]{1,2}$#",$day_burn) && $day_burn>31)
						{$error.="Вы не указали день рождения<br>";$day_burn="";}
  if(!preg_match("#^[0-9]{1,2}$#",$month_burn) && $month_burn>12)
						{$error.="Вы не указали месяц рождения<br>";$month_burn="";}
  if(!preg_match("#^[0-9]{4}$#",$year_burn) && $year_burn>date("Y"))
						{$error.="Вы не указали год рождения<br>";$year_burn="";}
  if(!$city || ($city==-1 && !$city_oth))
						{$error.="Вы не указали свой город<br>";$city="";}
  if($grow && !preg_match("#^([0-9]{2,3})$#",$grow))
						{$error.="Вы ввели некорректный рост<br>";$grow="";}

  $query_check1=mysql_num_rows(MYSQL_QUERY("SELECT user FROM chat_users WHERE user='$login'"));
  if($query_check1!="0")			{$error.="Пользователь с таким логином уже зарегистрирован!<br>";$login="";}

  $query_check2=mysql_num_rows(MYSQL_QUERY("SELECT mail FROM chat_users WHERE mail='$mail'"));
  if($query_check2!="0")			{$error.="Пользователь с таким e-mail уже зарегистрирован!<br>";$mail="";}

  if(empty($error))
    {
    $month30=array("4","6","9","11");
    $month31=array("1","3","5","7","8","10","12");
    if(in_array($month_burn,$month30) && $day_burn>30) $day_burn=30;
    elseif(in_array($month_burn,$month31) && $day_burn>31) $day_burn=31;
    elseif(my_bcmod($year_burn,4)==0 && $month_burn==2 && $day_burn>29) $day_burn=29;
    elseif(my_bcmod($year_burn,4)>0 && $month_burn==2 && $day_burn>28) $day_burn=28;
    $date=date("Y-m-d");
    $pass_sh=md5($pass);
    $query_reg="INSERT INTO chat_users VALUES('', '$login', '$pass_sh', '0', '$mail', '$about','$name','$icq','$year_burn-$month_burn-$day_burn','$sex','$city','$city_oth','$region','$grow','$sign','default','1','down','down','hm','1','russian','000000','000000','0','default','$hidemail','0','0','0','0','0',now(),'0000-00-00','0')";
    if(mysql_query($query_reg))
      {
      $newid=mysql_result(mysql_query("select max(id) from chat_users"),0,'max(id)');
      mysql_query("insert into chat_banstat values ('','$newid','ban','0'), ('','$newid','warn','0')");
      echo "<script language=javascript>alert('Регистрация прошла успешно!');
      window.close();</script>";
      }
    else echo "Произошла ошибка:".mysql_error();

    }
  else
    {
    $url="javascript:history.go(-1)";
    echo "<b>Произошла ошибка!</b><br>$error";
    form_new_user($login,$name,$day_burn,$month_burn,$year_burn,$sex,$city,$city_oth,$region,$grow,$mail,$icq,$hidemail,$about,$sign);
    }
  }

$title="Регистрация";
include("design/header.tpl");
if(empty($action)) $action="";

if(empty($_SESSION['suser']))
  {
  if($action=="reg")
    {
    reg_new_user($chat_title);
    }
  else
    {
    form_new_user();
    }
  }
else
  {
  $url="index.php";
  $info="Вы уже зарегистрированы!";
  echo $info;
  }
include("design/footer.tpl");
?>