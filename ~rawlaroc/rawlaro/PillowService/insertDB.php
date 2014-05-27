<?php
require_once("Global.php");
require_once("DBHelper.php");
require_once("DataTableHelper.php");

$SN1=$_POST["sn1"];
$SN2=$_POST["sn2"];

if($SN1!=null && $SN2!=null)
{
	$sql = "SELECT COUNT(*) FROM `DeviceMapping`";
	$sql .= " WHERE `FromSN`='".$SN1."'";
	$sql .= " OR `ToSN`='".$SN1."'";
	$Result = mysql_query_result($sql, false);
	if(count($Result)>0)
		if(count($Result[0])>0)
			$count1 = $Result[0][0];
			
	$sql = "SELECT COUNT(*) FROM `DeviceMapping`";
	$sql .= " WHERE `FromSN`='".$SN2."'";
	$sql .= " OR `ToSN`='".$SN2."'";
	$Result = mysql_query_result($sql, false);
	if(count($Result)>0)
		if(count($Result[0])>0)
			$count2 = $Result[0][0];
			
	if($count1==0 && $count2==0)
	{
		$SqlCmd = "Insert into `DeviceMapping`(`FromSN`,`ToSN`) ";
		$SqlCmd .= "values('".$SN1."','".$SN2."'),('".$SN2."','".$SN1."')";
		$Result = mysql_update_record($SqlCmd, false);
	}
}
?>
<form method="post" action="insertDB.php">
<fieldset>
  SN1:<input type="text" name="sn1"/>
</fieldset>
<fieldset>
  SN2:<input type="text" name="sn2"/>
</fieldset>
<fieldset>
  <input type="submit" value="ok" />
</fieldset>
</form>