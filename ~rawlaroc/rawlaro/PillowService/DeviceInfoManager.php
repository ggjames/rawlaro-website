<?php
//laoli
//$SqlCmd=iconv("big5","UTF-8",$SqlCmd);	 Big5 to utf8

require_once("Global.php");
require_once("DBHelper.php");

class eActionType{
	const InsertOrUpdate = 1;
	const Query = 2;
	const Delete = 3;
}

class DeviceInfo{
	public $ActionType,$isEnable,$LoginTime,$DeviceName,$IP,$PortName;
	public $SN;
	public	$ChineseName;
	public $EnglishName;
	public $Address;
	public $PhoneNumber;
	public $Email;
	public $DeviceID;
	public $UserPassword;
	public $UserBirthday  ;
	public $UserTimezone;
	public $IsMale  ;
	public $UserAllowViewOnline;
	public $UserMSN;
}
$di = new DeviceInfo();

$di->ActionType = $_REQUEST['ActionType'];
$di->SN = $_REQUEST['SN'];
$di->ChineseName = $_REQUEST['ChineseName'];
$di->EnglishName = $_REQUEST['EnglishName'];
$di->Address = $_REQUEST['Address'];
$di->PhoneNumber = $_REQUEST['PhoneNumber'];
$di->Email = $_REQUEST['Email'];
$di->DeviceID = $_REQUEST['DeviceID'];
$di->UserPassword  = $_REQUEST['UserPassword'];
$di->UserBirthday  = $_REQUEST['UserBirthday'];
$di->UserTimezone  = $_REQUEST['UserTimezone'];
$di->IsMale  = $_REQUEST['IsMale'];
$di->UserAllowViewOnline  = $_REQUEST['UserAllowViewOnline'];
$di->UserMSN  = $_REQUEST['UserMSN'];

$di->IP = $_SERVER["REMOTE_ADDR"];

if ($di->SN != "")
{
	//Check password first!
	$SqlCmd="select `UserPassword` from `DeviceInfo` where `SN` = '".$di->SN."'";
	$Password = mysql_query_result($SqlCmd);
	if ($di->UserPassword == $Password[0][0])
	{
		
		switch ($di->ActionType){

			case eActionType::InsertOrUpdate:
				$SqlCmd = "select * from `DeviceInfo` where `SN` = '".$di->SN."'";
				$SNCount = mysql_query_scalar($SqlCmd);
				if ($SNCount <> -1 ){
					if ($SNCount == "0")
					{
						$SqlCmd =  "insert into `DeviceInfo`(`SN`,`isEnabled`,`ChineseName`,`EnglishName`,`Address`,`PhoneNumber`,`Email`,`IP`) ";
						$SqlCmd .= "values('".$di->SN." ', true , '".$di->ChineseName."','".$di->EnglishName."','".$di->Address."','".$di->PhoneNumber."','".$di->Email."','".$di->IP."')";
					}
					else
					{
						$SqlCmd = "update `DeviceInfo` SET";
						$SqlCmd .= "`isEnabled`=true,";
						$SqlCmd .= "`LoginTime`='".$di->LoginTime."', ";
						$SqlCmd .= "`ChineseName`='".$di->ChineseName."', ";
						$SqlCmd .= "`EnglishName`='".$di->EnglishName."', ";
						$SqlCmd .= "`Address`='".$di->Address."', ";
						$SqlCmd .= "`PhoneNumber`='".$di->PhoneNumber."', ";
						$SqlCmd .= "`Email`='".$di->Email."', ";
						$SqlCmd .= "`IP`='".$di->IP."' ";
						$SqlCmd .= "WHERE `SN`='".$di->SN."'";
					}
					$Result = mysql_update_record($SqlCmd, false);
				}
				else
				$Result= "-1";
				break;

			case eActionType::Query:
				$SqlCmd = "SELECT `DeviceInfoID` , `SN` , Cast( `IsEnabled` AS unsigned ) AS `IsEnabled` , `LoginTime` , `ChineseName` , `EnglishName` , `Address` , `PhoneNumber` , `Email` , `IP` , `DeviceID` , ";
				$SqlCmd .= "CASE `DeviceLoginTime` ";
				$SqlCmd .= "WHEN '0000-00-00 00:00:00' ";
				$SqlCmd .= "THEN \"2000-01-01 00:00:00\" ";
				$SqlCmd .= "ELSE \"`DeviceLoginTime`\" ";
				$SqlCmd .= "END AS `DeviceLoginTime` ";
				$SqlCmd .= "FROM `DeviceInfo` ";
				$SqlCmd .= "WHERE `SN` = 'LocalSN1' ";
				$Result = mysql_query_object($SqlCmd);
				$Result->ActionType = 2;	$Result->IsEnabled = 1;
				echo $Result->ChineseName;
				break;

			case eActionType::Delete:
				$SqlCmd = "delete from `DeviceInfo` where `SN`='".$di->SN."'";
				$Result = mysql_update_record($SqlCmd, false);
				break;

		}

	} else
	{
		echo "Error Password!";
	}

}

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Untitled Page</title>
<style type="text/css">
.style1 {
	width: 100%;
}
</style>
</head>
<body>
<form
	action="http://66.147.242.160/~rawlaroc/rawlaro/PillowService/DeviceInfoManager.php"
	method="post">
<table class="style1">
	<tr>
		<td>Select Action:</td>
		<td><input name="ActionType" type="radio" value="1" checked="checked" />Insert</td>
		<td></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="ActionType" type="radio" value="2" />Query</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="ActionType" type="radio" value="3" />Delete</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>SN:</td>
		<td><input name="SN" type="text"  value="LocalSN1"/></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input name="UserPassword" type="text" /></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>ChineseName:</td>
		<td><input name="ChineseName" type="<?php echo $Result->ChineseName;?>>" /></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>EnglishName:</td>
		<td><input name="EnglishName" type="text" /></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Address:</td>
		<td><input name="Address" type="text" /></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>PhoneNumber:</td>
		<td><input name="PhoneNumber" type="text" /></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Email:</td>
		<td><input name="Email" type="text" /></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>UserBirthday:</td>
		<td><input name="$UserBirthday" type="text" /></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>UserMSN:</td>
		<td><input name="$UserMSN" type="text" /></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="Submit1" type="submit" value="submit" /></td>
		<td>&nbsp;</td>
	</tr>
</table>
</form>
</body>
</html>
