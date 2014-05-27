<?php
require_once("Global.php");
require_once("DBHelper.php");
require_once("DataTableHelper.php");

//Ecrypt key
$key = "12345678";
//Unit is sec.
$MaxWaitingSecondsWhenPoll = 30;
//�C���N�ݤ@��DB
$PollDBIntervalSec = 2;

class eActionType
{
	//All the pc poll using Receive to get any pending messages.
	const Poll=1;
	//After the device received the messages, pc must ack to server
	//it must request this page to void not receving the message again.
	const Ack=2;
	//When the pc receive the message from the device, it will send message to this page.
	const Send=3;
}

$JsonObject = $_REQUEST['JsonObject'];
$dm = (object)json_decode(stripslashes ($JsonObject));
//��w�]�S���[�K�A�ݬ�Ū��Ū���� ActionType �Ѥ��X�ӥN�?�[�K�L
if  ($dm->ActionType == "" )
//if  ($dm->ActionType == "")
$isEncrypted = true;
else
$isEncrypted = false;

//�p�G�O���[�K�L���ܡA�N�n�ѱK
if($isEncrypted)
{
	$JsonObject = stripslashes(Decrypt($key, $JsonObject));
	$dm = (object)json_decode($JsonObject);
	//�p�G�٬O�Ѥ��X�ӡA�N����ǰe��ƿ�~��
	if($dm->ActionType == "")
	{
		echo "No ActionType!";
		exit();
	}
}

//**********************************************************************************************
//���\��o DeviceMessage ����A�}�l�B�z

$dm->IP = $_SERVER["REMOTE_ADDR"];

switch ($dm->ActionType)
{
	//PC�nmessage�����A
	case eActionType::Poll:
		$MaxPollCount = $MaxWaitingSecondsWhenPoll / $PollDBIntervalSec;
		$SqlCmd = "SELECT * ";
		$SqlCmd .= "FROM `Messages` ";
		$SqlCmd .= "INNER JOIN `DeviceMapping` ON `Messages`.`FromSN` = `DeviceMapping`.`FromSN` ";
		$SqlCmd .= "WHERE `DeviceMapping`.`ToSN` = '".$dm->SN."' ";
		$SqlCmd .= "AND `MessageServerID` NOT ";
		$SqlCmd .= "IN ( ";
		$SqlCmd .= "SELECT `MessageServerID` ";
		$SqlCmd .= "FROM `MessageRecieved` ";
		$SqlCmd .= ") ";
		for ($i = 0; $i < $MaxPollCount; $i++)
		{
			$DataCount = mysql_query_scalar($SqlCmd);
			if ($DataCount <> "0")
			{
				$Result = json_encode((mysql_query_objects($SqlCmd)));
				break ;
			}
			usleep($PollDBIntervalSec * 1000000);
			if ($i ===$MaxPollCount-1)
			$Result = "[]";
		}
		break;

	//PC�����T���^���message�����A
	case eActionType::Ack:
		//get time function:
		//print(strftime("%Y-%m-%d %H:%M:%S ",time()));
		//�P�_server�O�_���e�X�o��message�A�p�Gmessages table���o���N��Server���e�X�o�����
		$SqlCmd = "select * from `Messages` where `MessageServerID` = '".$dm->MessageServerID."'";
		$SNCount = mysql_query_scalar($SqlCmd);
		if ($SNCount <> "0")
		{
			//�Y���o��message�A�A�ˬd�O�_�w�gack
			$SqlCmd = "select * from `MessageRecieved` where `MessageServerID` = '".$dm->MessageServerID."' and `ToSN` = '".$dm->SN."'";
			$SNCount = mysql_query_scalar($SqlCmd);
			//SNCount = -1 error
			if ($SNCount <> -1 ){
				if ($SNCount == "0")
				{
					$SqlCmd =  "insert into `MessageRecieved`(`MessageServerID`,`ToIP`,`ToSN`,`ReceivedTime`) ";
					$SqlCmd .= "values('".$dm->MessageServerID."',";
					$SqlCmd .= "'".$dm->IP."',";
					$SqlCmd .= "'".$dm->ToSN."',";
					$SqlCmd .= "'".$dm->ReceivedTime."')";
				}
				else
				{
					$SqlCmd = "update `MessageRecieved` set ";
					$SqlCmd .= "`MessageServerID`='".$dm->MessageServerID."',";
					$SqlCmd .= "`ToIP`='".$dm->IP."',";
					$SqlCmd .= "`ToSN`='".$dm->ToSN."',";
					$SqlCmd .= "`ReceivedTime`='".$dm->ReceivedTime."'";
					$SqlCmd .= " Where `MessageServerID` = '".$dm->MessageServerID;
				}
				$Result = mysql_update_record($SqlCmd, false);
			}
		}
		break;

	//�@�xPC�qdevice����T���A�n�e�Xmessage��server�����A
	case eActionType::Send:
// 		$SqlCmd = "Insert into `Messages`(`FromIP`,`FromSN`,`Content`,`SentTime`) ";
// 		$SqlCmd .= "values('".$dm->IP."',";
// 		$SqlCmd .= "'".$dm->SN."',";
// 		$SqlCmd .= "'".$dm->Content."',";
// 		$SqlCmd .= "'".$dm->SentTime."')";
// 		$Result = mysql_update_record($SqlCmd, false);
		$Result = mysql_update_message($dm->IP, $dm->SN, $dm->Content, $dm->SentTime, false);
		break;

}
echo ($isEncrypted==true)?(Encrypt($key, $Result)):($Result);

?>

