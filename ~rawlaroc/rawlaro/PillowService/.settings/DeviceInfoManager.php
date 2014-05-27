


<?php
//param:action for query action when posting data
//laoli
//$SqlCmd=iconv("big5","UTF-8",$SqlCmd);	 Big5 to utf8

require_once("Global.php");
require_once("DBHelper.php");
require_once("DataTableHelper.php");

class eActionType{
	const InsertOrUpdate = 0;
	const Query = 1;
	const Delete = 2;
}
//=================DB sample=======================
//$SqlCmd = "Insert Into `Messages` (`FromIP`, `FromSN`, `Content`) Values('123','456','this is test content')";
//if ( mysql_update_record($SqlCmd, false))
//{
//	echo "success!";
//}
//
//$SqlCmd = "Select `LoginTime` From `DeviceInfo`";
//$SqlRet = mysql_query_result($SqlCmd, false);
//if ( $SqlRet )
//{
//	echo json_encode($SqlRet);
//	print_r($SqlRet);
//}
//==================================================

//TEST using get
//$ActionType = $_GET["ActionType"];$SN=$_GET['SN'];$Enable=$_GET['Enable'];
//$EnglishName=$_GET['EnglishName'];$Email=urldecode($_GET['Email']);
//$PhoneNumber=$_GET['PhoneNumber'];
//$Address=iconv("big5","UTF-8",urldecode($_GET['Address']));
//$ChineseName = iconv("big5","UTF-8",urldecode($_GET['ChineseName']));

//echo json_decode("{\"ActionType\":0,\"DeviceID\":null,\"IsEnabled\":true,\"ChineseName\":\"簡\",\"EnglishName\":\"Jian\",\"Address\":\"台北縣三重市新生街98號\",\"LoginTime\":\"2010-10-07T01:33:38.5250945+08:00\",\"DeviceLoginTime\":\"2000-01-01T00:00:00\",\"PhoneNumber\":\"88612345\",\"Email\":\"xxx@gmail.com\",\"IP\":null,\"SN\":\"JianTestSn\",\"PortName\":\"COM5\",\"DeviceName\":\"抱枕三\"}");
//echo json_decode("{\"ActionType\":0,\"DevuceUD\":null}");

$SN = $_POST['SN'];
if ($SN != "")
{
	$ActionType = $_POST["ActionType"];$Enable=$_POST['Enable'];
	$EnglishName=$_POST['EnglishName'];$Email=urldecode($_POST['Email']);
	$PhoneNumber=$_POST['PhoneNumber'];$IP = $_SERVER["REMOTE_ADDR"];
	$Address=$_POST["Address"];	$ChineseName=$_POST["ChineseName"];

	switch ($ActionType){
		case eActionType::InsertOrUpdate:
			$SqlCmd = "select * from `DeviceInfo` where `SN` = '".$SN."'";
			$SNCount = mysql_query_scalar($SqlCmd);
			if ($SNCount <> -1 ){
				if ($SNCount == "0")
				$SqlCmd = "insert into `DeviceInfo`(`SN`,`isEnabled`,`ChineseName`,`EnglishName`,`Address`,`PhoneNumber`,`Email`,`IP`) values('".$SN." ', true , '".$ChineseName."','".$EnglishName."','".$Address."','".$PhoneNumber."','".$Email."','".$IP."')";
				else
				$SqlCmd = "update `DeviceInfo` set `isEnabled`=true,`ChineseName`='".$ChineseName."',`EnglishName`='".$EnglishName."',`Address`='".$Address."',`PhoneNumber`='".$PhoneNumber."',`Email`='".$Email."',`IP`='".$IP."' where `SN`='".$SN."'";
				$SqlRet = mysql_update_record($SqlCmd, false);
				if ( $SqlRet ) echo "\n",$SqlRet;
				else echo $SqlRet;
			}
			else
			echo "-1";
			exit();
			break;

		case eActionType::Query:
			$SqlCmd = "select * from `DeviceInfo` where `SN`='".$SN."'";
			//			$dt = new DataTable;
			//			$dt->dataBind(queryDB($SqlCmd));
			//			$dt->addField($fieldName);
			//			$dt->showDataTable();
			//			echo json_encode(queryDB($SqlCmd));
			$SqlCmd = "SELECT `DeviceInfoID` , `SN` , Cast( `IsEnabled` AS unsigned ) , `LoginTime` , `ChineseName` , `EnglishName` , `Address` , `PhoneNumber` , `Email` , `IP` , `DeviceID` ,CASE `DeviceLoginTime`WHEN '0000-00-00 00:00:00'THEN '\"2000-01-01 00:00:00\"'ELSE `\"DeviceLoginTime\"`END FROM `DeviceInfo`WHERE `SN` = '";
			$SqlCmd .= $SN."'";
			$reuslt = mysql_query_json($SqlCmd, false,$ActionType);
			print $reuslt;
			exit();
			break;

		case eActionType::Delete:
			$SqlCmd = "delete from `DeviceInfo` where `SN`='".$SN."'";
			$SqlRet = mysql_update_record($SqlCmd, false);
			echo $SqlRet;
			exit();
			break;
	}
}

function queryDB($SqlCmd)
{
	$SqlRet = mysql_query_result($SqlCmd, false);
	if ( $SqlRet )
	{
		return $SqlRet;
	} else
	{
		return "-1";
	}
}
?>



<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
</head>
<body>
<FORM method="post">
<table class="style1">
	<tr>
		<td class="style2">ActionType</td>
		<td><select name="ActionType" id="ActionType">
			<option value=1>Query</option>
			<option value=0>Insert Or Update</option>
			<option value=2>Delete</option>
		</select></td>
	</tr>
	<tr>
		<td class="style2">SN</td>
		<td><input name="SN" type="text" value="LocalSN1" /></td>
	</tr>
	<tr>
		<td class="style2">Chinese</td>
		<td><input name="ChineseName" type="text" value="簡晨洋" /></td>
	</tr>
	<tr>
		<td class="style2">English</td>
		<td><input name="EnglishName" type="text" value="Kil" /></td>
	</tr>
	<tr>
		<td class="style2">Email</td>
		<td><input name="Email" type="text" value="laoli@gmail" /></td>
	</tr>
	<tr>
		<td class="style2">Address</td>
		<td><input name="Address" type="text" value="XX路AB向" /></td>
	</tr>
	<tr>
		<td class="style2">Phone</td>
		<td><input name="PhoneNumber" type="text" value="012345" /></td>
	</tr>
	<tr>
		<td class="style2">&nbsp;</td>
		<td><input name="Submit1" type="submit" value="submit" /></td>
	</tr>
</table>
</FORM>
</body>
</html>
