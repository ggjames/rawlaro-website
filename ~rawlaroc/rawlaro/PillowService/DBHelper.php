<?php
require_once("include/mysql.php");
include("mysql2json.class.php");

// function mysql_open()
// {
// 	$dbserver = "localhost";
// 	$username = "rawlaroc_jian";
// 	$password = "test";
// 	$database = "rawlaroc_jian";
// 	$connection = mysql_connect($dbserver, $username, $password);
// 	if($connection)
// 	{
// 		mysql_select_db($database, $connection);
// 		$sql = "SET NAMES UTF8";
// 		mysql_query($sql, $connection) or die(mysql_error());
// 	}
// 	return $connection;
// }

//for get record count
function mysql_query_scalar($sql, $externalConnection=false,$def="")
{
	$table = array();
	$connection = (false === $externalConnection)?
	(mysql_open()):($externalConnection);
	if($connection)
	$rs = mysql_query($sql,$connection) or die(mysql_error().$sql);
	//		=========return row param===========
	//		if (mysql_num_rows($rs)) {
	//			$r = mysql_fetch_row($rs);
	//			mysql_free_result($rs);
	//			return $r[0];
	//		}

	//return row count
	$row_num = mysql_num_rows($rs);
	return $row_num;
}


//for getting object to do json encode 
function mysql_query_object($sql, $externalConnection=false)
{
	$table = array();
	$connection = (false === $externalConnection)?
	(mysql_open()):($externalConnection);
	if($connection)
	{
		$result = mysql_query($sql, $connection) or die(mysql_error());
		$array=mysql_fetch_object($result);
		mysql_free_result($result);
		if(false === $externalConnection) mysql_close($connection);
	}
	return $array;
}

//for getting Mutiple objects to do json encode  from Messages
function mysql_query_objects($sql, $externalConnection=false)
{
	$table = array();
	$connection = (false === $externalConnection)?
	(mysql_open()):($externalConnection);
	if($connection)
	{
		$result = mysql_query($sql, $connection) or die(mysql_error());
		while($array=mysql_fetch_object($result))
		{
			$table[] = $array; 
		}
		mysql_free_result($result);
		if(false === $externalConnection) mysql_close($connection);
	}
	return $table;
}

//for select 
// function mysql_query_result($sql, $externalConnection=false)
// {
// 	$table = array();
// 	$connection = (false === $externalConnection)?
// 	(mysql_open()):($externalConnection);
// 	if($connection)
// 	{
// 		$result = mysql_query($sql, $connection) or die(mysql_error());
// 		while($row = mysql_fetch_array($result))
// 		{
// 			$table[] = $row;
// 		}
// 		mysql_free_result($result);
// 		if(false === $externalConnection) mysql_close($connection);
// 	}
// 	return $table;
// }

//for update or insert 
// function mysql_update_record($sql, $externalConnection=false)
// {
// 	$connection = (false === $externalConnection)?
// 	(mysql_open()):($externalConnection);
// 	if($connection)
// 	{
// 		$result = mysql_query($sql, $connection) or die(mysql_error());
// 		$affected_rows = mysql_affected_rows();
		
// 		if(false === $externalConnection) mysql_close($connection);
// 	}
// 	return $affected_rows;
// }

function mysql_update_message($ip, $SN, $content, $sentTime, $externalConnection=false)
{
	$affected_rows = 0;
	$connection = (false === $externalConnection)?
	(mysql_open()):($externalConnection);
	if($connection)
	{
		$escaped_content = mysql_real_escape_string($content,$connection);
		$sql = "Insert into `Messages`(`FromIP`,`FromSN`,`Content`,`SentTime`) ";
		$sql .= "values('".$ip."',";
		$sql .= "'".$SN."',";
		$sql .= "'".$escaped_content."',";
		$sql .= "'".$sentTime."')";
		$result = mysql_update_record($sql, $connection) or die(mysql_error());
// 		if(true === $result)
// 		{
// 			$affected_rows = mysql_affected_rows($connection);
// 		}
		if(false === $externalConnection) mysql_close($connection);
	}
	//return $affected_rows;
	return $result;
}
?>
