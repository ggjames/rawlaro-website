<?php
//laoli
//$SqlCmd=iconv("big5","UTF-8",$SqlCmd);	 Big5 to utf8

require_once("Global.php");
require_once("DBHelper.php");

//Encrypt Key
$key = "12345678";


//使用這個變數測試就可以知道 \" 到底是誰加的，直接在瀏覽器網址哪邊打 ?TestParam="  就會看到印出來的有 \"
//所以就要想辦法去把他replace掉，有兩個方式，一個是使用 str_replace 一個是用 stripslashes
//str_replace("\\\\","\\",str_replace("\\\"","\"", $TestParam));
$FromSN=$_REQUEST["FromSN"];
$ToSN=$_REQUEST["ToSN"];

$SqlCmd = "select * from `DeviceMapping` Where `ToSN` = '".$ToSN."' And `FromSN` = '".$FromSN."'";
$SNCount = mysql_query_scalar($SqlCmd);
if ($SNCount <> -1 ){
	if ($SNCount == "0") 
	{
		$Result= "0";
	}
	else 
	{
		$Result= "1";
	}
}
else
{
	$Result= "0";
}
$json["Result"] = $Result;
//echo ($isEncrypted==true)?(Encrypt($key, $Result)):($Result);
echo json_encode($json);
?>
