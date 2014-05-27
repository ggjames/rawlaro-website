<?php
  function mysql_open()
  {
  	//hugnii only has 3 privileges: SELECT, INSERT, UPDATE
    $dbserver = "localhost";
    $username = "rawlaroc_hugnii";
    $password = "%)L[uiq&u(7d";
    $database = "rawlaroc_hugnii";
    $connection = mysql_connect($dbserver, $username, $password);
    if($connection)
    {
      mysql_select_db($database, $connection);
      $sql = "SET NAMES UTF8";
      mysql_query($sql, $connection) or die(mysql_error());
    }
    return $connection;
  }
  function mysql_query_result($sql, $externalConnection=false)
  {
    $table = array();
    $connection = (false === $externalConnection)?
                  (mysql_open()):($externalConnection);
    if($connection)
    {
      $result = mysql_query($sql, $connection) or die(mysql_error());
	  //print_r($result);
      while($row = mysql_fetch_row($result))
      {
        $table[] = $row;
      }
      mysql_free_result($result);
      if(false === $externalConnection) mysql_close($connection);
    }
    return $table;
  }
  function mysql_update_record($sql, $externalConnection=false)
  {
    $affected_rows = 0;
    $connection = (false === $externalConnection)?
                  (mysql_open()):($externalConnection);
    if($connection)
    {
      $result = mysql_query($sql, $connection) or die(mysql_error());
      if(true === $result)
      {
        $affected_rows = mysql_affected_rows($connection);
      }
      if(false === $externalConnection) mysql_close($connection);
    }
    return $affected_rows;
  }
  
?>
