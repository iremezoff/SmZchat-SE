<?
define("C_MOD",1);
define("F_MOD",1);
include("inc/functions.php");
$sess_id=session_id();
if(empty($file)) $file="";
$chat_refreshtime*=1000;

if(empty($resultcnt)) $resultcnt=0;
if(!empty($_SESSION['suser']))
  {
  $checkauth=mysql_query("select sid from chat_onliners where user='$_SESSION[suser]' and sid='$sess_id'");
  $resultcnt=mysql_num_rows($checkauth);
  }

if(isset($_SESSION['suser'])&&$resultcnt==1)
  {
  include("theme/$skin/colors.php");
  if($file=="header")
    {
    include("files/header.php");
    }
  if($file=="messages")
    {
    include("files/messages.php");
    }
  elseif($file=="users")
    {
    include("files/users.php");
    }
  elseif($file=="send")
    {
    include("files/send.php");
    }
  else
    {
    include("files/main.php");
    }
  }
elseif($_SERVER['QUERY_STRING']=="reg"&&empty($_SESSION['suser']))
  {
  include("files/reg.php");
  }
elseif($_SERVER['QUERY_STRING']=="forg"&&empty($_SESSION['suser']))
  {
  include("files/forg.php");
  }
else
  {
  include("files/index.php");
  }
?>