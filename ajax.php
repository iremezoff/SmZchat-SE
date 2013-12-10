<?
define("C_MOD",1);
include("inc/functions.php");

header("Content-type: text/plain; charset=windows-1251");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

if(isset($mode)&&$mode=="clogin")
  {
  $query_check=mysql_num_rows(mysql_query("select user from chat_users where user='$input'"));
  if(!preg_match("#^[a-zA-Z0-9_]+$#",$input)) echo "<img src=\"./design/images/no.gif\" width=16> Недопустимые символы";
  elseif($query_check>0) echo "<img src=\"./design/images/no.gif\" width=16> Уже зарегистрирован";
  else echo "<img src=\"./design/images/ok.gif\" width=16>";
  }
elseif(isset($mode)&&$mode=="clogin2")
  {
  $query_check=mysql_num_rows(mysql_query("select user from chat_users where user='".strtolower($input)."'"));
  if($query_check==0) echo "<img src=\"./design/images/no.gif\" width=16> Пользоватаель не найден";
  else echo "<img src=\"./design/images/ok.gif\" width=16>";
  }
elseif(isset($mode)&&$mode=="cpass")
  {
  if(strlen($input)<4 || strlen($input)>10) echo "<img src=\"./design/images/no.gif\" width=16> Некорректная длина";
  else echo "<img src=\"./design/images/ok.gif\" width=16>";
  }
elseif(isset($mode)&&$mode=="cpassc")
  {
  if($input!=$input2) echo "<img src=\"./design/images/no.gif\" width=16> Несовпадение паролей";
  else echo "<img src=\"./design/images/ok.gif\" width=16>";
  }
elseif(isset($mode)&&$mode=="cmail")
  {
  $query_check=mysql_num_rows(mysql_query("select mail from chat_users where mail='$input'"));
  if(!preg_match("#^[a-z0-9_.-]+@[a-z0-9_.-]+\.+[a-z]{2,4}$#is",$input)) echo "<img src=\"./design/images/no.gif\" width=16> Некорректный e-mail";
  elseif($query_check>0) echo "<img src=\"./design/images/no.gif\" width=16> Уже зарегистрирован";
  else echo "<img src=\"./design/images/ok.gif\" width=16>";
  }
elseif(isset($mode)&&$mode=="cgrow")
  {
  if(!$input) echo "<img src=\"./design/images/ok.gif\" width=16>";
  elseif(!preg_match("#^([0-9]{2,3})$#",$input)) echo "<img src=\"./design/images/no.gif\" width=16> Некорреткный рост";
  else echo "<img src=\"./design/images/ok.gif\" width=16>";
  }
elseif(isset($mode)&&$mode=="cicq")
  {
  if(!$input) echo "<img src=\"./design/images/ok.gif\" width=16>";
  elseif(!preg_match("#^([0-9]{4,9})$#",$input)) echo "<img src=\"./design/images/no.gif\" width=16> Некорректный ICQ";
  else echo "<img src=\"./design/images/ok.gif\" width=16>";
  }
elseif(isset($mode)&&$mode=="cname")
  {
  if(!$input) echo "<img src=\"./design/images/no.gif\" width=16> Не введено";
  else echo "<img src=\"./design/images/ok.gif\" width=16>";
  }
elseif(isset($mode)&&$mode=="cburn")
  {
  if(empty($input) || empty($input2) || empty($input3)) echo "<img src=\"./design/images/no.gif\" width=16> Указаны не вся дата";
  else echo "<img src=\"./design/images/ok.gif\" width=16>";
  }
?>