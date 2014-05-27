<?php
class DeviceMessage {
  public $FromSN, $ToSN, $Content;
  public $SentTime;
}

$dm= new DeviceMessage();
$dm->FromSN="LocalSN1";
$dm->ToSN="LocalSN2";
$dm->Content="HAHA";
$dm->SentTime=date("Y/m/d H:i:s");

$dm2= new DeviceMessage();
$dm2->FromSN="LocalSN3";
$dm2->ToSN="LocalSN4";
$dm2->Content="HAHA2";
$dm2->SentTime=date("Y/m/d H:i:s");

$Ret=array($dm,$dm2);

echo ("DeviceMessage Array:". json_encode($Ret));
echo "<BR />";
echo ("DeviceMessage:". json_encode($dm));

?>