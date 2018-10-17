<?php
ini_set('max_execution_time','200');
$handle = @fopen("./2017011921-www.quanjingke.com.txt", "r");
$fangwen=200;//单个IP访问总数大于这个数
if ($handle) {
	//START:统计每个IP访问的总数
	$iplist=array();
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        preg_match("/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/",$buffer, $matches);
        //preg_match("08/43248100.tiles/l2_r_02_03.jpg",$buffer, $matches);
       
        $ip=$matches[0];
      
        if(array_key_exists($ip,$iplist)){
        	$iplist[$ip]=$iplist[$ip]+1;
        }else{
			$iplist[$ip]=1;
        }
       
        //echo $buffer."<br><br><br><br>";
    }
    //END:统计每个IP访问的总数
  
   echo "START:访问次数排序:<br>";
   arsort($iplist);
   $count=0;
   foreach ($iplist as $key => $value) {
   		if($value>$fangwen){
   			$count++;
   			//echo "-A INPUT -s ".$key." -j DROP"."<br>";
   			echo "$key($value)<br>";
   			
   		}
   }
   echo "单个IP访问总数大于".$fangwen."一共".$count."个IP<br>";
   echo "END:访问次数排序:<br>";
 
 
   echo "<br>START:分析网段<br>";    
	krsort($iplist);
   foreach ($iplist as $key => $value) {
   		if($value>$fangwen){
   			echo $key."($value)<br>";
   		}
   }
	echo "<br>END:分析网段";

  //找出前网段三段相同的IP
  $s=array();
  foreach ($iplist as $key => $value) {
	list($a, $b, $c, $d) = explode(".", $key);
	$fff=$a.".".$b.".".$c;
  	$s[]=$key;

  }
   //print_r($iplist);
    fclose($handle);
}
?>