<?php
require_once("Global.php");
require_once("DBHelper.php");
require_once("DataTableHelper.php");

$MAX_RECORD_COUNT=50;

	$FromSN = $_REQUEST["FromSN"];
	
	$sql_get_couple =
		"SELECT `ToSN` FROM `DeviceMapping`"
		. "WHERE `FromSN`='".$FromSN."'"
		;
	$objects = mysql_query_result($sql_get_couple, false);	
	
	$ToSN = $objects[0][0];
	
  $sql_get_msg_sent = 
  				"SELECT `Content`,`SentTime` FROM `Messages`"
          . " WHERE `FromSN`='".$FromSN."'"
          ;
  $objects1 = mysql_query_result($sql_get_msg_sent, false);
  
  $sql_get_msg_received = 
  				"SELECT `Content`,`SentTime` FROM `Messages`"
          . " WHERE `FromSN`='".$ToSN."'"
          ;
  $objects2 = mysql_query_result($sql_get_msg_received, false);
  
  $SentMsg = array();
  $SentTime = array();
  $ReceivedMsg = array();
  $ReceivedTime = array();
  
  if(count($objects1)>=$MAX_RECORD_COUNT)
  {
  	$num1=count($objects1)-$MAX_RECORD_COUNT;
  	for($i=$num1;$i<count($objects1);$i++){
  		$SentMsg[$i-$num1]=$objects1[$i][0];
  		$SentTime[$i-$num1]=$objects1[$i][1];
  	}
  }
  else
  {
  	for($i=0;$i<count($objects1);$i++){
  		$SentMsg[$i]=$objects1[$i][0];
  		$SentTime[$i]=$objects1[$i][1];
  	}
  }
  
  if(count($objects2)>=$MAX_RECORD_COUNT)
  {
  	$num2=count($objects2)-$MAX_RECORD_COUNT;
  	for($i=$num2;$i<count($objects2);$i++){
  		$ReceivedMsg[$i-$num2]=$objects2[$i][0];
  		$ReceivedTime[$i-$num2]=$objects2[$i][1];
  	}
  }
  else
  {
  	for($i=0;$i<count($objects2);$i++){
  		$ReceivedMsg[$i]=$objects2[$i][0];
  		$ReceivedTime[$i]=$objects2[$i][1];
  	}
  }

  $json["SentMsg"] = $SentMsg;
  $json["SentTime"] = $SentTime;
  $json["ReceivedMsg"] = $ReceivedMsg;
  $json["ReceivedTime"] = $ReceivedTime;

  echo json_encode($json);
?>

