<?
define("C_MOD",1);
include("inc/functions.php");
$sess_id=session_id();

$title="$chat_title";

if(empty($mode)) $mode="";

if($mode=="exit" && isset($_SESSION['suser']))
  {
  $time=time();
  if(isset($_SESSION['suser']))
    {
    $message=str_replace("+u+","<b>$_SESSION[suser]</b>",$array_logout[array_rand($array_logout,1)]);
    mysql_query("insert into chat_messages (room, user, message, time) values ('$_SESSION[room]', '$bot_name', '$message', '$time')");
    $start=mysql_result(mysql_query("select start from chat_onliners where user='$_SESSION[suser]'"),0,'start');
    $tot_time=ceil(($time-$start)/60);
    mysql_query("update chat_users set inchat=inchat+'$tot_time' where user='$_SESSION[suser]'");
    }
  mysql_query("delete from chat_onliners where sid='$sess_id'");
  session_destroy();
  foreach($_SESSION as $key=>$val)
    unset($_SESSION[$key]);
  $info='<script language=javascript>parent.location.href="./index.php";</script>';
  }
elseif(isset($login)&&isset($pass))
  {
  $qlogin=mysql_escape_string(trim($login));
  $pass=trim($pass);
  if($login!="")
    {
    $authquery=mysql_query("select * from chat_users where user='$qlogin'");
    $authquery2=mysql_query("select * from chat_banlist where user='$qlogin'");
    $authresultcnt=mysql_num_rows($authquery);
    if($authresultcnt==1)
      {
      $userarr=mysql_fetch_array($authquery);
      $userarr2=mysql_fetch_array($authquery2);
      $pass=md5($pass);
      if($pass==$userarr['pass'])
        {
        $user=substr(trim($login),0,20);
	$act=mysql_num_rows(mysql_query("select * from chat_onliners where user='$user'"));
        $reb="";
        if($act>0) $reb=" (reboot)";
	mysql_query("delete from chat_onliners where user='$qlogin'");
        $old_us=mysql_query("select user,room from chat_onliners where ip='$ip'");
      	$time=time();
        /*if(mysql_num_rows($old_us)>0)
          {
          list($old_user,$old_room)=mysql_fetch_row($old_us);;
          $message=str_replace("+u+","<b>$old_user</b>",$array_logout[array_rand($array_logout,1)]);
	  mysql_query("delete from chat_onliners where ip='$ip'");
          mysql_query("insert into chat_messages (room, user, message, time) values ('$old_room', '$bot_name', '$message', '$time')");
          }*/
	if((int)$userarr2['mute_settime']>0 && (int) $userarr2['mute_time']>0)
	  {
	  if((time()-(int) $userarr2['mute_settime'])<(int)$userarr2['mute_time'])
	    $user_ban=1;
 	  else
	    $user_ban=0;
	  }
        else
	  $user_ban=0;
	$_SESSION['room']=$room;
	$_SESSION['sid']=$userarr['id'];
	$_SESSION['suser']=$userarr['user'];
	$_SESSION['skin']=$userarr['skin'];
	$_SESSION['vtime']=$userarr['time'];
	$_SESSION['smiles']=$userarr['smiles'];
	$_SESSION['skin']=$userarr['skin'];
	$_SESSION['nm']=$userarr['nm'];
	$_SESSION['send']=$userarr['send'];
	$_SESSION['focus']=$userarr['focus'];
	$_SESSION['slang']=$userarr['lang'];
	$_SESSION['lmess']=time();
	$_SESSION['status']=1;
	$_SESSION['ban']=0;
	$_SESSION['balls']=$userarr['balls'];
        $_SESSION['cnick']=$userarr['colornick'];
        $_SESSION['bnick']=$userarr['bnick'];
        $_SESSION['ctxt']=$userarr['colortxt'];
        $_SESSION['sortby']=$userarr['sortby'];
        if(empty($_SESSION['cnick'])) $_SESSION['cnick']=="000000";
        if($userarr['balls']<100)
          {
          $priv=explode("|",mysql_result(mysql_query("select priv from chat_level where mess<='$userarr[mess]' order by mess desc limit 1"),0,'priv'));
          foreach($priv as $key=>$val) $_SESSION['slevel'][$key]=$val;
          }
        else $_SESSION['slevel']=array(0=>"1","1","1","1","1","1","1","1","1","1","1","1","1","1");
        $bot_name=mysql_result(mysql_query("select botname from chat_rooms where id='$_SESSION[room]'"),0,'botname');
        @$status=mysql_result(mysql_query("select text from chat_xstatus where id='1'"),0,'text');
        $message=str_replace("+u+","<b>$userarr[user]</b>",$array_login[array_rand($array_login,1)]).$reb;
      	mysql_query("insert into chat_messages (room, user, message, time) values ('$room', '$bot_name', '$message', '$time')");
	mysql_query("insert into chat_onliners (user, room, balls, sex, ip, sid, lastactivity, xtext, lang, colornick, bnick, mess, start, upd) values ('$userarr[user]', '$room', '$userarr[balls]', '$userarr[sex]', '$ip', '$sess_id', '$time', '$status', '$userarr[lang]', '$userarr[colornick]', '$userarr[bnick]', '$userarr[mess]', '$time', '$time')") or die("1");
        mysql_query("update chat_stat set count=count+1 where type='auth'");
        mysql_query("update chat_users set auth=auth+1 where user='$_SESSION[suser]'");
        $info="<meta http-equiv=refresh content='2;url=index.php'>
        Авторизация прошла успешно! Сейчас вы будете переброшены в чат!<br>Если Вам не терпится, нажмите <a href=\"index.php\">сюда</a>";
	}
      else
        {
	$info="<span style=\"color:#ff0000\">Ошибка!</span> Неверный логин или пароль!";
	}
      }
    else
      {
      $userarr=mysql_fetch_array(mysql_query("select * from chat_users where user='guest'"));
      $user=substr(trim($login),0,20)."_guest";
      $act=mysql_num_rows(mysql_query("select * from chat_onliners where user='$user'"));
      $reb="";
      if($act>0) $reb=" (reboot)";
      mysql_query("delete from chat_onliners where user='$qlogin'");
      $_SESSION['room']=$room;
      $_SESSION['sid']=$userarr['id'];
      $_SESSION['suser']=$user;
      $_SESSION['skin']=$userarr['skin'];
      $_SESSION['vtime']=$userarr['time'];
      $_SESSION['smiles']=$userarr['smiles'];
      $_SESSION['skin']=$userarr['skin'];
      $_SESSION['nm']=$userarr['nm'];
      $_SESSION['send']=$userarr['send'];
      $_SESSION['focus']=$userarr['focus'];
      $_SESSION['slang']=$userarr['lang'];
      $_SESSION['lmess']=time();
      $_SESSION['status']=1;
      $_SESSION['ban']=0;
      $_SESSION['balls']=$userarr['balls'];
      $_SESSION['cnick']=$userarr['colornick'];
      $_SESSION['bnick']=$userarr['bnick'];
      $_SESSION['ctxt']=$userarr['colortxt'];
      $_SESSION['sortby']=$userarr['sortby'];
      if(empty($_SESSION['cnick'])) $_SESSION['cnick']=="000000";
      $priv=explode("|",mysql_result(mysql_query("select priv from chat_level where id='-1'"),0,'priv'));
      foreach($priv as $key=>$val) $_SESSION['slevel'][$key]=$val;
      $bot_name=mysql_result(mysql_query("select botname from chat_rooms where id='$_SESSION[room]'"),0,'botname');
      @$status=mysql_result(mysql_query("select text from chat_xstatus where id='1'"),0,'text');
      $message=str_replace("+u+","<b>$user</b>",$array_login[array_rand($array_login,1)]).$reb;
      mysql_query("insert into chat_messages (room, user, message, time) values ('$room', '$bot_name', '$message', '$time')");
      mysql_query("insert into chat_onliners (user, room, balls, sex, ip, sid, lastactivity, xtext, lang, colornick, bnick, mess, start, upd) values ('$user', '$room', '$userarr[balls]', '$userarr[sex]', '$ip', '$sess_id', '$time', '$status', '$userarr[lang]', '$userarr[colornick]', '$userarr[bnick]', '$userarr[mess]', '$time', '$time')") or die("1");
      mysql_query("update chat_stat set count=count+1 where type='auth'");
      $info="<meta http-equiv=refresh content='5;url=index.php'>
      Вы авторизованы как <b>гость</b>! Сейчас вы будете переброшены в чат!<br>Если Вам не терпится, нажмите <a href=\"index.php\">сюда</a>";
      }
    }
  else
    {
    $info="<span style=\"color:#ff0000\">Ошибка!</span> Не введён логин!";
    }
  }
else
  {
  $info="<meta http-equiv=refresh content='2;url=index.php'>
  Сначала нужно авторизироваться!";
  exit;
  }
include("design/header.tpl");
echo "<table cellpadding=10 cellspacing=0 border=0><tr><td>";
echo $info;
echo "</td></tr></table>";
include("design/footer.tpl");
?>