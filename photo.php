<?
define("C_MOD",1);

include("inc/functions.php");

$count_check=mysql_num_rows(mysql_query("select * from chat_photos where id='$id'"));
if($count_check<1) $id=mysql_result(mysql_query("select max(id) from chat_photos"),0,'max(id)');

$query_photo=mysql_query("select filename from chat_photos where id='$id'");
list($path_img)=mysql_fetch_row($query_photo);

if($t=="b")
  {
  $path="./photos/".$path_img;
  $query_upd=mysql_query("update chat_photos set views=views+1 where id='$id'");
  }
elseif($t=="s") $path="./photos/small/".$path_img;

if (!file_exists($path)) $error=0;
$img=fopen("$path","rb");

$list=explode(".",$path_img);

$ext=strtolower(end($list));

if($ext=="jpg") Header("Content-type: image/pjpeg");

elseif($ext=="gif") Header("Content_type: image/gif");

elseif($ext=="png") Header("Content-type: image/png");

else Header("Content-type: unknown/unknown");

fpassthru($img);
?>