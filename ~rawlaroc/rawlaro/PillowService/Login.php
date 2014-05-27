<?php
//The pc will send it SN to login, SN is stored in the session.
if(!empty($_GET["SN"]))
	$_SESSION["SN"]=$_GET["SN"];
else if(!empty($_POST["SN"]))
	$_SESSION["SN"]=$_POST["SN"];
	

?>