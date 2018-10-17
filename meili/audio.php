<?php
require_once('getid3/getid3.php');
$getID3 = new getID3();
$FileInfo = $getID3->analyze('before.m4a');
echo $FileInfo['quicktime']['ftyp']['signature'];

echo "<br />";
$afterInfo = $getID3->analyze('after.m4a');
echo $afterInfo['quicktime']['ftyp']['signature'];;

//文件信息取得时间长度为：$FileInfo['playtime_seconds']
?>