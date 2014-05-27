<?php
//Global Variable
$PleaseLogin="PleaseLogin";
$Key="12345678";

//****************************************************************
//Crypto Library
function Encrypt($key, $string) {
  //$key = "12345678";
  $cipher_alg = MCRYPT_TRIPLEDES;
  $iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher_alg,MCRYPT_MODE_ECB), MCRYPT_RAND);

  $encrypted_string = mcrypt_encrypt($cipher_alg, $key, $string, MCRYPT_MODE_ECB, $iv);
  return base64_encode($encrypted_string);
 }
 
 function Decrypt($key, $string) {
   $string = base64_decode($string);
   //$key = "12345678";
   $cipher_alg = MCRYPT_TRIPLEDES;
   $iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher_alg,MCRYPT_MODE_ECB), MCRYPT_RAND);
   $decrypted_string = mcrypt_decrypt($cipher_alg, $key, $string, MCRYPT_MODE_ECB, $iv);
   return trim($decrypted_string);
 }

 //****************************************************************
 
 //Check Session Data, If not login, echo "PleaseLogin" to client.
 //PC will
 
 //TODO: for Debug Environment
 if( $_SESSION["SN"]=="")
 	$_SESSION["SN"]="TestSN"; 
 
 
 if( $_SESSION["SN"]=="")
 {
 	echo $PleaseLogin;
 	exit();
 }
 
 //echo "SN:".$_SESSION["SN"]."<BR />";
?>