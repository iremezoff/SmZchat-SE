<?
define("C_MOD",1);
include("inc/functions.php");
include("theme/$skin/colors.php");

$sess_id=session_id();

if(isset($_SESSION['suser']))
  {
  $qlogin=mysql_escape_string(trim($_SESSION['suser']));
  $checkauth=mysql_query("select sid from chat_onliners where user='$qlogin' and sid='$sess_id'");
  $resultcnt=mysql_num_rows($checkauth);
  mysql_free_result($checkauth);
  if($resultcnt==1)
    {
    if(!empty($unban)&&$_SESSION['balls']>=500)
      {
      if(empty($ips)) {$ips="";$add="";}
      else $add="or ip='$ips'";
      if(empty($unban)) $unban="";
      if(empty($error))
        {
        $mod=mysql_result(mysql_query("select ban from chat_banlist where user='".urldecode($unban)."'"),0,'ban');
        if($mod=="$bot_name")  $balls_mod=999;
        else
          $balls_mod=mysql_result(mysql_query("select balls from chat_users where user='$mod'"),0,'balls');
        if($_SESSION['balls']>=$balls_mod)
          {
          $time=time();
          mysql_query("delete from chat_banlist where user='".urldecode($unban)."' $add");
          mysql_query("update chat_onliners set ban='0',upd='$time' where user='".urldecode($unban)."' $add");
          mysql_query("insert into chat_messages (room, user, message, time) values ('$_SESSION[room]','$bot_name','С участника <b>".urldecode($unban)."</b> был снят бан модератором <b>$_SESSION[suser]</b>.','$time')") or die(mysql_error());
          mysql_query("insert into chat_logs values ('','$unban','$_SESSION[suser]','<b>снят бан</b>','$time')");
          }
        else
          {
          $bans=mysql_num_rows(mysql_query("select * from chat_banlist where user='".mysql_escape_string(urldecode($_SESSION['suser']))."'"));
          if($bans>0)
            {
            mysql_query("update chat_banlist set user='$_SESSION[suser]',ban='$bot_name',type='1',reson='7',mute_time=mute_time+'60' where user='".urldecode($_SESSION['suser'])."'");
            }
          else
            mysql_query("insert into chat_banlist values ('','$_SESSION[suser]','','$bot_name','1','7','60','".time()."')");
          $ban_time_t="1 минуту";
          $time=time();
          mysql_query("update chat_onliners set ban='1',upd='$time' where user='".urldecode($_SESSION['suser'])."'");
          mysql_query("insert into chat_messages (room, user, message, time) values ('$_SESSION[room]','$bot_name','Участник <b>".urldecode($_SESSION['suser'])."</b> был забанен модератором <b>$bot_name</b> за <b>попытку разбанить бан старшего модератора</b> на <b>$ban_time_t</b>.','$time')") or die(mysql_error());
          mysql_query("insert into chat_logs values ('','$_SESSION[suser]','$bot_name','забанен за <b>попытку разбанить бан старшего модератора</b> на <b>$ban_time_t</b>','$time')");
          }
          $text='<script language=javascript>window.close();</script>';
        } 
      }
    elseif(!empty($ignore)&&$_SESSION['sid']>-1)
      {
      if(strtolower($ignore)!=strtolower($_SESSION['suser']))
        {
        $time=time();
        mysql_query("update chat_onliners set upd='$time' where user='".urldecode($_SESSION['suser'])."'") or die(mysql_error());
        mysql_query("insert into chat_ignore values ('','$_SESSION[suser]','$ignore')");
        mysql_query("insert into chat_messages (room,user,userto,message,private,color,time) values ('$_SESSION[room]','$bot_name','$_SESSION[suser]','Участник $ignore добавлен вами в список игнора. Теперь вы неувидите его сообщений.','1','#ff0000','$time'),('$_SESSION[room]','$bot_name','$ignore','Участник $_SESSION[suser] добавил вас в список игнора. Теперь он неувидит ваших сообщений.','1','#ff0000','$time')");
        $text='<script language=javascript>window.close();</script>';
        }
      }
    elseif(!empty($unignore)&&$_SESSION['sid']>-1)
      {
      if(strtolower($unignore)!=strtolower($_SESSION['suser']))
        {
        $time=time();
        mysql_query("update chat_onliners set upd='$time' where user='".urldecode($_SESSION['suser'])."'") or die(mysql_error());
        mysql_query("delete from chat_ignore where user='$_SESSION[suser]' and ignores='$unignore'");
        mysql_query("insert into chat_messages (room,user,userto,message,private,color,time) values ('$_SESSION[room]','$bot_name','".urldecode($_SESSION['suser'])."','Участник $unignore удалён вами из списка игнора.','1','#ff0000','$time'),('$_SESSION[room]','$bot_name','$unignore','Участник $_SESSION[suser] снял с вас игнор.','1','#ff0000','$time')");
        $text='<script language=javascript>window.close();</script>';
        }
      }
    elseif(!empty($sort)&&$_SESSION['sid']>-1)
      {
      if($sort=="nick" || $sort=="sex" || $sort=="default") $_SESSION['sortby']=$sort;
      else $_SESSION['sortby']="default";
      $_SESSION['upd']=0;
      mysql_query("update chat_users set sortby='$_SESSION[sortby]' where user='".addslashes($_SESSION['suser'])."'") or die(mysql_error());
      }
    elseif(!empty($ch_status))
      {
      $xstatus=mysql_query("select id,text from chat_xstatus where image='$setstatus'");
      list($id_x,$text_x)=mysql_fetch_row($xstatus);
      if(empty($id_x)) $id_x=1;
      if($_SESSION['slevel'][10]==1 && ($xtext || empty($text_x))) $text_x=$xtext;
      $time=time();
      $_SESSION['status']=$id_x;
      $_SESSION['lmess']=$time;
      mysql_query("update chat_onliners set status='$id_x',xtext='$text_x',upd='$time' where user='".addslashes($_SESSION['suser'])."'");
      }
    elseif(!empty($ch_style)&&$_SESSION['sid']>-1)
      {
      $time=time();
      if(isset($setbnick)&&$setbnick==1&&$_SESSION['slevel'][9]==1) $_SESSION['bnick']=1;
      else $_SESSION['bnick']=0;
      if(isset($setcnick)&&$_SESSION['slevel'][3]==1) $_SESSION['cnick']=$setcnick;
      else $_SESSION['cnick']='000000';
      mysql_query("update chat_onliners set colornick='$_SESSION[cnick]',bnick='$_SESSION[bnick]',upd='$time' where user='".addslashes($_SESSION['suser'])."'");
      mysql_query("update chat_users set colornick='$_SESSION[cnick]',bnick='$_SESSION[bnick]' where user='".addslashes($_SESSION['suser'])."'") or die(mysql_error());
      $text='<script language=javascript>parent.document.location.href="index.php";</script>';
      }
    elseif(!empty($ch_opts)&&$_SESSION['sid']>-1)
      {
      define("SK_MOD",1);
      include("theme/skins.php");
      if(isset($setsmiles)) $_SESSION['smiles']=$setsmiles;
      else $_SESSION['smiles']=0;
      if(isset($settime)&&($settime=="hm"||$settime=="hms"||$settime=="ms")) $_SESSION['vtime']=$settime;
      else $_SESSION['vtime']="hm";
      if(isset($setskin)&&in_array($setskin,$skins)) $_SESSION['skin']=$setskin;
      else $_SESSION['skin']="default";
      if(isset($setnm)&&($setnm=="up"||$setnm=="down")) $_SESSION['nm']=$setnm;
      else $_SESSION['nm']="down";
      if(isset($setsend)&&($setsend=="up"||$setsend=="down")) $_SESSION['send']=$setsend;
      else $_SESSION['send']="down";
      if(isset($setfocus)&&($setfocus=="0"||$setfocus=="1")) $_SESSION['focus']=$setfocus;
      else $_SESSION['focus']="1";
      mysql_query("update chat_onliners set lang='$_SESSION[slang]' where user='".addslashes($_SESSION['suser'])."'");
      mysql_query("update chat_users set skin='$_SESSION[skin]',time='$_SESSION[vtime]',nm='$_SESSION[nm]',send='$_SESSION[send]',smiles='$_SESSION[smiles]',focus='$_SESSION[focus]' where user='".addslashes($_SESSION['suser'])."'") or die(mysql_error());
      $text='<script language=javascript>parent.document.location.href="index.php";</script>';
      }
    elseif(!empty($ch_room))
      {
      if($_SESSION['room']!=$setroom)
        {
        $time=time();
        $check=mysql_num_rows(mysql_query("select id from chat_rooms where id='$setroom'"));
        if($check<1)
          $setroom=mysql_result(mysql_query("select min(id) from chat_rooms"),0,'min(id)');
        $room_name=mysql_result(mysql_query("select name from chat_rooms where id='$setroom'"),0,'name');
        $rooml_name=mysql_result(mysql_query("select name from chat_rooms where id='$_SESSION[room]'"),0,'name');
        $bot_name2=mysql_result(mysql_query("select botname from chat_rooms where id='$setroom'"),0,'botname');
        mysql_query("update chat_onliners set room='$setroom',upd='$time' where user='$_SESSION[suser]'");
        mysql_query("insert into chat_messages (room,user,message,time) values ('$_SESSION[room]','$bot_name','Участник <b>$_SESSION[suser]</b> сбежал в комнату <b>$room_name</b>.','$time'), ('$setroom','$bot_name2','Участник <b>$_SESSION[suser]</b> прибежал из комнаты <b>$rooml_name</b>.','$time')");
        $_SESSION['room']=$setroom;
        $text='<script language=javascript>parent.document.location.href="index.php";</script>';
        }
      }
    elseif(!empty($new_mess))
      {
      if($_SESSION['ban']!=1)
        {
        $_SESSION['lmess']=time();
        $arr1=array(":::","|");
        $arr2=array(" :: ","/\\");
        $msg=$msg2=mysql_escape_string(strip_tags(trim(str_replace($arr1,$arr2,$message))));
        $arr_msg=explode(" ",$msg);
        $fontcolor=$_POST['color'];
        if($_SESSION['ctxt']!=$fontcolor)
          {
          $_SESSION['ctxt']=$fontcolor; 
          mysql_query("update chat_users set colortxt='$_SESSION[ctxt]' where user='$_SESSION[suser]'");
          }
        $sendername=mysql_escape_string($_SESSION['suser']);
        if($_SESSION['slevel'][9]==1 && isset($_SESSION['bnick']) && $_SESSION['bnick']==1) $bnick=1;
        else $bnick=0;
        if($_SESSION['slevel'][3]==1 && isset($_SESSION['cnick'])) $cnick=$_SESSION['cnick'];
        else $cnick=substr($color['defcolor'],1);
        $senderto=mysql_escape_string($to);
        $time=time();
        $msg_d=$msg;
        $csm=0;
        $nomsg=0;
        if(isset($private)&&$private==1&&!empty($senderto)) $is_private=1;
        else $is_private=0;
        if($_SESSION['smiles']==1)
          {
          foreach($codes_sm as $key=>$val)
            {
            $csm+=substr_count($msg_d,$val);
            $msg_d=str_replace($val,"",$msg_d);
            }
          $msg=str_replace($codes_sm,$urls_sm,$msg);
          }
	if($_SESSION['balls']==999) include("inc/999.php");
	if($_SESSION['balls']>=500) include("inc/500.php");
	include("inc/0.php");
        $msg=preg_replace('/\\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]/i','<a href="\\0" target="_blank">\\0</a>',$msg);
        if(empty($to) && $private==1) $csm=$max_sm+1;
        $text='<script language=javascript>window.parent.frames["send"].document.getElementById("s").disabled = false;mess_focus();';
        if($csm<=$max_sm)
          {
          if($nomsg!=1)
            {
            mysql_query("insert into chat_messages (room, user, userto, message, time, private, color, bnick, cnick) VALUES ('$_SESSION[room]', '$sendername', '$senderto', '$msg', '$time', '$is_private', '#$fontcolor', '$bnick', '#$cnick')") or die(mysql_error());
            $words=0;
            foreach($arr_msg as $val) if(strlen($val)>=$wlen) $words++;
            $len=strlen($msg);
            mysql_query("update chat_stat set count=count+1 where type='mess_day' or type='mess_week' or type='mess_month' or type='mess_year' or type='mess_total'") or die(mysql_error());
            mysql_query("update chat_stat set count=count+'$len' where type='size_day' or type='size_week' or type='size_month' or type='size_year' or type='size_total'") or die(mysql_error());
            if($is_private==1)
              {
              mysql_query("update chat_users set pmess=pmess+1,words=words+'$words' where user='$_SESSION[suser]'");
              }
            else
              {
              mysql_query("update chat_users set mess=mess+1,words=words+'$words' where user='$_SESSION[suser]'"); 
              mysql_query("update chat_onliners set mess=mess+1 where user='$_SESSION[suser]'");
              $time_mess=date("H:i:s");
              $messlog="$time_mess [$sendername";
              if($senderto) $messlog.=" » $senderto";
              $messlog.="] $msg2";
              mysql_query("insert into chat_messlogs values ('','$_SESSION[room]','$messlog');");
              }
            }
          $text.='window.parent.frames["send"].document.getElementById("message").value="";';
          }
        $text.='</script>';
        }
      }
    else
      {}
    }
  }
?>
<html>
<head></head>
<body>
<script id="JS"></script>
<script language="JavaScript">
document.getElementById("JS").text = window.parent.document.getElementById("JS_all").text;
</script>
<?echo empty($text)?"":$text;?>
</body>
</html>