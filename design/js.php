<?//if(!isset($_SERVER['HTTP_REFERER'])) die();
define("C_MOD",1);
include("../inc/functions.php");?>
var xmlDoc = null;
var  txITOG = "";
var _Browser = "unknown";
_userAgent = navigator.userAgent.toLowerCase(); 
     if (_userAgent.indexOf("opera") > -1)      _Browser = "Opera"; 
else if (_userAgent.indexOf("msie") > -1)       _Browser = "Internet Explorer"; 
else if (_userAgent.indexOf("netscape/7") > -1) _Browser = "Netscape Navigator 7"; 
else if (_userAgent.indexOf("netscape/8") > -1) _Browser = "Netscape Navigator 8"; 
else if (_userAgent.indexOf("firefox") > -1)    _Browser = "Firefox"; 
function readFile(file)
{
 if (window.XMLHttpRequest) { xmlDoc = new XMLHttpRequest(); }
 else if (window.ActiveXObject)
   {
   try { xmlDoc = new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) { try { xmlDoc = new ActiveXObject("Microsoft.XMLHTTP"); } catch (e) {} }
   }

 xmlDoc.open( "GET", file, true );

 if (_Browser == "Firefox") { xmlDoc.overrideMimeType('text/xml;charset=windows-1251'); }

 xmlDoc.setRequestHeader("Version", "HTTP/1.1");
 xmlDoc.setRequestHeader("Content-type", "text/plain; charset=windows-1251");
 xmlDoc.setRequestHeader("Accept-Language", "ru, en");
 xmlDoc.setRequestHeader("Accept-Charset", "windows-1251;q=1");
 xmlDoc.setRequestHeader("Pragma", "no-cache");
 xmlDoc.setRequestHeader("Cache-Control", "no-cache");


 if (typeof window.ActiveXObject!='undefined') { xmlDoc.onreadystatechange = process; } else { xmlDoc.onload = process; }
 xmlDoc.send( null );
}

function process()
{
 if ( xmlDoc.readyState == 4 )
 {
 txITOG = xmlDoc.responseText;
 }
}

function get(input,iform,iform2,iform3)
  {
  if (txITOG == "" )
    {
    if (input == 'cburn') setTimeout ("get('"+input+"','"+iform+"','"+iform2+"','"+iform3+"')", 100);
    else if (input == 'cpassc') setTimeout ("get('"+input+"','"+iform+"','"+iform2+"')", 100);
    else setTimeout ("get('"+input+"','"+iform+"')", 100);
    return false;
    }
  document.getElementById(input).innerHTML = txITOG;
  txITOG = "";
  }


function checks(input,iform,iform2,iform3)
  {
  if (input == 'cburn') readFile('./ajax.php?mode='+input+'&input='+iform+'&input2='+iform2+'&input3='+iform3);
  else if (input == 'cpassc') readFile('./ajax.php?mode='+input+'&input='+iform+'&input2='+iform2);
  else readFile('./ajax.php?mode='+input+'&input='+iform);
  get(input,iform,iform2,iform3);
  }

    function check(){
    var error  = 1 ;
    <?if($type_str!=1){?>if (form.user_key.value == "") { Mess('Вы не ввели 6-значное число','user_key'); return 0;}<?}?>
    if (form.login.value == "") { Mess('Укажите ваш логин','login'); return 0;}
    if (form.pass.value == "") { Mess('Укажите пароль','pass'); return 0;}
    if (form.confirm_pass.value == "") { Mess('Укажите повторный пароль','confirm_pass'); return 0;}
    if (form.name.value == "") { Mess('Укажите ваше имя','name'); return 0;}
    if (!checkml (form.mail.value))  {Mess ("Укажите корректный e-mail",'mail');return 0;}
    if (form.sex.value == "") { Mess('Укажите ваш пол','sex'); return 0;}
    if (form.city.value == "") { Mess('Укажите ваш город','city'); return 0;}
    return error;
    }


    function check2(){
    var error  = 1 ;
    if (form.userto_new.value == "") { Mess('Укажите ник адресата','userto_new'); return 0;}
    else if (form.userto_new.value == "<?echo empty($_SESSION['suser'])?"":$_SESSION['suser'];?>") { Mess('Вы пишите самому себе?','userto_new'); return 0;}
    if (form.theme_new.value == "") { Mess('Укажите тему сообщения','theme_new'); return 0;}
    if (form.text_new.value == "") { Mess('Укажите текст сообщения','text_new'); return 0;}
    return error;
    }

    function Mess (mms,str){
    form.elements[str].focus () ;
    alert (mms) ;
    }

    function checkml (str){
    var i ;
    if ((str.indexOf ("@", 0) != -1) && (str.indexOf (".", 0) != -1)){
      i = str.length - str.lastIndexOf (".") - 1 ;
      if (i && (i <= 3) && (str.indexOf ("@", 0) != 0) && ((str.indexOf ("@", 0) + 1) < str.lastIndexOf (".")))
        return 1 ;
    }
    return 0 ;
    }

function add_user(nick,field)
  {
  opener.document.form.elements[field].value=nick;
  opener.document.getElementById('clogin2').innerHTML='<img src="./design/images/ok.gif" width=16>';
  opener.focus();
  window.close();
  return;
  }